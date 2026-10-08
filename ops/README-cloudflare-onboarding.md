# Cloudflare domain onboarding (October 2026)

`cloudflare-domains-2026-10.txt` contains the 43 domains to onboard.
`directory-batch-2026-10.csv` specifies their names and themes.
`nearbiz.com.tr` was explicitly excluded because it serves a different live
site. The Cloudflare script looks up every zone before creating it and does
not overwrite any existing DNS record.

## Prerequisites

- Python 3.9+ on the machine running the script.
- Cloudflare account ID `50b810a01ff23a6bfbf1c4d75e8b6fe2`, verified in the dashboard.
- The existing account token `api2` has Zone Read and DNS Write on all zones in
  this account. Use its existing server-side secret file if available; the
  dashboard does not redisplay token values. If zone creation is rejected,
  use a token with Zone Edit as well.
- The origin IPv4 address is currently `45.143.4.26`; verify it before applying.
- The owner confirmed these new domains have no email or other DNS services.

Keep the token out of shell history and the Git repository. If it is already
in a Certbot Cloudflare INI or an `.env` assignment file, use that path:

```bash
cd /var/www/firmarehberi
export CLOUDFLARE_ACCOUNT_ID=50b810a01ff23a6bfbf1c4d75e8b6fe2
python3 scripts/cloudflare_onboard.py ops/cloudflare-domains-2026-10.txt --origin-ip 45.143.4.26
python3 scripts/cloudflare_onboard.py ops/cloudflare-domains-2026-10.txt --origin-ip 45.143.4.26 --token-file /path/to/existing/cloudflare.ini --apply
unset CLOUDFLARE_ACCOUNT_ID
```

The token file is only read locally. If the existing secret is an environment
variable instead, export `CLOUDFLARE_API_TOKEN` and omit `--token-file`.

The plan command makes no API calls. The apply command creates missing full
zones and `@` A plus `www` CNAME records, initially **DNS only**. It writes
`output/cloudflare-nameservers.csv` after every domain. `nearbiz.com.tr` is
absent from both batch files. It is safe to rerun after a partial
failure: existing matching records are accepted, and conflicting records are
reported without overwriting them.

The CSV lists the two Cloudflare nameservers for each zone. Before changing
nameservers at the registrar, verify every row has two nameservers and no error.
If DNSSEC is enabled at the registrar, remove old DS records before switching
nameservers and configure DNSSEC again after Cloudflare is active.

Create the 43 new directory records in the shared admin database as passive:

```bash
php artisan directories:onboard-batch
php artisan directories:onboard-batch --apply
```

The first command previews the names and themes. The second is idempotent and
does not modify any existing directory. Each new directory
gets its own site settings record. Keep them passive until their hostnames work.

The NS change alone does not make a new directory site ready. After delegation
is visible at the `.tr` registry, run the `origin-first` or `origin-remaining`
phase of the onboarding workflow. These phases use Certbot's Cloudflare DNS
authenticator to issue an apex and `www` certificate for each domain, add a
separate Nginx site without touching existing sites, test and reload Nginx,
activate its directory, and verify direct HTTPS at the origin. The script is
safe to rerun after a partial failure. Run `origin-first` only for the first
29 domains and `origin-remaining` only after the last 14 are delegated.

DNS records remain DNS only while origin setup proceeds. Once direct HTTPS
works, Cloudflare proxy and Full (strict) can be enabled and the public site
checked. Cloudflare's Full (strict) mode requires a valid origin certificate
for the requested hostname.
