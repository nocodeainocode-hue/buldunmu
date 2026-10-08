#!/usr/bin/env bash
set -euo pipefail

# Run as root on the application server after the registrar delegates the group.
# Each completed domain is safe to skip on a subsequent run.

group="${1:-}"
if [[ "$group" != first && "$group" != remaining ]]; then
    echo 'Usage: scripts/install_directory_origin.sh first|remaining' >&2
    exit 2
fi
if [[ "$(id -u)" -ne 0 ]]; then
    echo 'Run as root.' >&2
    exit 2
fi

cd /var/www/firmarehberi
token_file=/root/.cloudflare.ini
if [[ ! -f "$token_file" ]]; then
    echo "Missing Cloudflare credentials: $token_file" >&2
    exit 2
fi

mapfile -t domains < <(grep -vE '^[[:space:]]*(#|$)' ops/cloudflare-domains-2026-10.txt)
if [[ "${#domains[@]}" -ne 43 ]]; then
    echo "Expected 43 batch domains; found ${#domains[@]}" >&2
    exit 2
fi
if [[ "$group" == first ]]; then
    start=0
    end=29
else
    start=29
    end=43
fi

for ((index=start; index<end; index++)); do
    domain="${domains[index]}"
    if [[ ! "$domain" =~ ^[a-z0-9-]+\.com\.tr$ || "$domain" == nearbiz.com.tr ]]; then
        echo "Unsafe domain entry: $domain" >&2
        exit 2
    fi

    echo "[$((index + 1))/43] $domain: checking certificate"
    cert="/etc/letsencrypt/live/$domain/fullchain.pem"
    cert_ok=false
    if [[ -f "$cert" ]] && openssl x509 -in "$cert" -noout -checkend 2592000 >/dev/null 2>&1 \
        && openssl x509 -in "$cert" -noout -checkhost "$domain" | grep -q 'does match' \
        && openssl x509 -in "$cert" -noout -checkhost "www.$domain" | grep -q 'does match'; then
        cert_ok=true
    fi
    if [[ "$cert_ok" != true ]]; then
        certbot certonly --dns-cloudflare \
            --dns-cloudflare-credentials "$token_file" \
            --dns-cloudflare-propagation-seconds 30 \
            --non-interactive --agree-tos --cert-name "$domain" \
            --force-renewal -d "$domain" -d "www.$domain"
    fi
    if [[ ! -f "$cert" ]] \
        || ! openssl x509 -in "$cert" -noout -checkhost "$domain" | grep -q 'does match' \
        || ! openssl x509 -in "$cert" -noout -checkhost "www.$domain" | grep -q 'does match'; then
        echo "Certificate does not cover $domain and www.$domain" >&2
        exit 1
    fi

    site="/etc/nginx/sites-available/fh-directory-$domain.conf"
    enabled="/etc/nginx/sites-enabled/fh-directory-$domain.conf"
    if [[ ! -e "$site" ]]; then
        cat > "$site" <<EOF
server {
    listen 80;
    server_name $domain www.$domain;
    return 301 https://\$host\$request_uri;
}

server {
    listen 443 ssl http2;
    server_name $domain www.$domain;
    ssl_certificate /etc/letsencrypt/live/$domain/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$domain/privkey.pem;
    root /var/www/firmarehberi/public;
    index index.php;
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / { try_files \$uri \$uri/ /index.php?\$query_string; }
    location ~ \\.php$ {
        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }
    location ~ /\\.(?!well-known).* { deny all; }
}
EOF
    fi
    if [[ ! -L "$enabled" ]]; then
        if [[ -e "$enabled" ]]; then
            echo "Existing non-symlink config: $enabled" >&2
            exit 1
        fi
        ln -s "$site" "$enabled"
    elif [[ "$(readlink -f "$enabled")" != "$site" ]]; then
        echo "Existing symlink points elsewhere: $enabled" >&2
        exit 1
    fi

    nginx -t
    systemctl reload nginx
    php artisan directories:activate-batch-domain "$domain"
    curl --fail --silent --show-error --max-time 20 \
        --resolve "$domain:443:45.143.4.26" "https://$domain/" -o /dev/null
    echo "[$((index + 1))/43] $domain: HTTPS ready and directory active"
done
