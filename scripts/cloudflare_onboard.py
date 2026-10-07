#!/usr/bin/env python3
"""Create Cloudflare zones and DNS records, then export registrar nameservers.

This is deliberately a two-stage rollout: records are DNS-only until the
origin has a valid certificate and the nameserver switch is complete.
Existing DNS records are never changed by this tool.
"""

import argparse
import csv
import ipaddress
import json
import os
import re
import sys
import time
from pathlib import Path
from urllib.error import HTTPError, URLError
from urllib.parse import urlencode
from urllib.request import Request, urlopen


API_ROOT = "https://api.cloudflare.com/client/v4"
DOMAIN_RE = re.compile(r"^[a-z0-9](?:[a-z0-9-]*[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)+$")
CSV_FIELDS = ["domain", "zone_status", "dns_status", "nameserver_1", "nameserver_2", "note"]


class CloudflareError(RuntimeError):
    pass


def load_domains(path: Path) -> list[str]:
    domains = []
    seen = set()
    for number, raw in enumerate(path.read_text(encoding="utf-8").splitlines(), 1):
        domain = raw.strip().lower()
        if not domain or domain.startswith("#"):
            continue
        if not DOMAIN_RE.fullmatch(domain) or not domain.endswith(".com.tr"):
            raise ValueError(f"{path}:{number}: invalid .com.tr domain: {raw!r}")
        if domain in seen:
            raise ValueError(f"{path}:{number}: duplicate domain: {domain}")
        seen.add(domain)
        domains.append(domain)
    if not domains:
        raise ValueError(f"{path}: no domains found")
    return domains


def token_from_file(path: Path) -> str:
    """Read a Certbot Cloudflare INI or a simple token assignment, never log it."""
    for raw in path.read_text(encoding="utf-8").splitlines():
        line = raw.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        if key.strip() in {"dns_cloudflare_api_token", "CLOUDFLARE_API_TOKEN"}:
            token = value.strip().strip('"\'')
            if token:
                return token
    raise ValueError(f"{path}: no Cloudflare API token assignment found")


class Cloudflare:
    def __init__(self, token: str, account_id: str):
        self.token = token
        self.account_id = account_id

    def request(self, method: str, path: str, *, params=None, body=None):
        url = API_ROOT + path
        if params:
            url += "?" + urlencode(params)
        data = json.dumps(body).encode("utf-8") if body is not None else None
        request = Request(
            url,
            data=data,
            method=method,
            headers={
                "Authorization": f"Bearer {self.token}",
                "Content-Type": "application/json",
                "Accept": "application/json",
                "User-Agent": "firmarehberi-cloudflare-onboard/1.0",
            },
        )
        for attempt in range(4):
            try:
                with urlopen(request, timeout=30) as response:
                    payload = json.load(response)
                    status = response.status
            except HTTPError as exc:
                status = exc.code
                try:
                    payload = json.load(exc)
                except (ValueError, UnicodeDecodeError):
                    payload = {"errors": [{"message": f"HTTP {status}"}]}
            except URLError as exc:
                raise CloudflareError(f"network error: {exc.reason}") from exc
            if status == 429 and attempt < 3:
                time.sleep(min(2 ** attempt * 2, 16))
                continue
            if status >= 400 or not payload.get("success"):
                errors = payload.get("errors") or []
                detail = "; ".join(str(item.get("message", item)) for item in errors)
                raise CloudflareError(f"HTTP {status}: {detail or 'Cloudflare API error'}")
            return payload.get("result")
        raise CloudflareError("rate limit retry exhausted")

    def zone(self, domain: str):
        result = self.request("GET", "/zones", params={"name": domain, "per_page": 50})
        exact = [zone for zone in result if zone.get("name") == domain]
        if len(exact) > 1:
            raise CloudflareError(f"multiple zones returned for {domain}")
        return exact[0] if exact else None

    def create_zone(self, domain: str):
        return self.request(
            "POST", "/zones",
            body={"name": domain, "account": {"id": self.account_id}, "type": "full"},
        )

    def records(self, zone_id: str, name: str):
        result = self.request(
            "GET", f"/zones/{zone_id}/dns_records",
            params={"name": name, "per_page": 100},
        )
        return [record for record in result if record.get("name") == name]

    def create_record(self, zone_id: str, *, record_type: str, name: str, content: str):
        return self.request(
            "POST", f"/zones/{zone_id}/dns_records",
            body={
                "type": record_type,
                "name": name,
                "content": content,
                "ttl": 1,
                "proxied": False,
            },
        )


def ensure_record(client: Cloudflare, zone_id: str, *, record_type: str, name: str, content: str):
    records = client.records(zone_id, name)
    if records:
        if len(records) == 1 and records[0].get("type") == record_type and records[0].get("content", "").rstrip(".") == content:
            return "present"
        details = ", ".join(f"{record.get('type')} {record.get('content')}" for record in records)
        raise CloudflareError(f"{name}: existing DNS record differs ({details}); no changes made")
    client.create_record(zone_id, record_type=record_type, name=name, content=content)
    return "created"


def save_rows(path: Path, rows: list[dict]):
    path.parent.mkdir(parents=True, exist_ok=True)
    temporary = path.with_name(path.name + ".tmp")
    with temporary.open("w", encoding="utf-8-sig", newline="") as handle:
        writer = csv.DictWriter(handle, fieldnames=CSV_FIELDS)
        writer.writeheader()
        writer.writerows(rows)
    temporary.replace(path)


def main(argv=None):
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("domains", type=Path, help="one .com.tr domain per line")
    parser.add_argument("--origin-ip", required=True, help="public IPv4 of the web server")
    parser.add_argument("--output", type=Path, default=Path("output/cloudflare-nameservers.csv"))
    parser.add_argument("--apply", action="store_true", help="create zones and missing DNS records")
    parser.add_argument("--token-file", type=Path, help="existing Certbot Cloudflare INI or token assignment file")
    parser.add_argument("--preserve-dns", action="append", default=[], metavar="DOMAIN",
                        help="leave DNS unchanged for an existing zone (repeatable)")
    args = parser.parse_args(argv)

    try:
        domains = load_domains(args.domains)
        ip = ipaddress.ip_address(args.origin_ip)
        if ip.version != 4 or not ip.is_global:
            raise ValueError("--origin-ip must be a public IPv4 address")
        if set(args.preserve_dns) - set(domains):
            raise ValueError("--preserve-dns must name a domain from the input file")
    except (OSError, ValueError) as exc:
        parser.error(str(exc))

    print(f"{len(domains)} domain(s), origin {ip}; {'APPLY' if args.apply else 'PLAN ONLY'}")
    if not args.apply:
        for domain in domains:
            print(domain)
        print("No API calls or changes made. Add --apply to create zones and DNS records.")
        return 0

    token = os.environ.get("CLOUDFLARE_API_TOKEN", "").strip()
    if not token and args.token_file:
        try:
            token = token_from_file(args.token_file)
        except (OSError, ValueError) as exc:
            parser.error(str(exc))
    account_id = os.environ.get("CLOUDFLARE_ACCOUNT_ID", "").strip()
    if not token or not re.fullmatch(r"[a-fA-F0-9]{32}", account_id):
        parser.error("set CLOUDFLARE_API_TOKEN or --token-file, and CLOUDFLARE_ACCOUNT_ID in the environment")

    client = Cloudflare(token, account_id)
    rows = []
    failures = 0
    for index, domain in enumerate(domains, 1):
        row = dict.fromkeys(CSV_FIELDS, "")
        row["domain"] = domain
        try:
            zone = client.zone(domain)
            if zone:
                if zone.get("account", {}).get("id") != account_id:
                    raise CloudflareError("zone belongs to another account; no changes made")
                row["zone_status"] = "existing"
            else:
                zone = client.create_zone(domain)
                row["zone_status"] = "created"
            nameservers = zone.get("name_servers") or []
            if len(nameservers) < 2:
                raise CloudflareError("Cloudflare did not return two nameservers")
            row["nameserver_1"], row["nameserver_2"] = nameservers[:2]
            if row["zone_status"] == "existing" and domain in args.preserve_dns:
                row["dns_status"] = "untouched"
                row["note"] = "Existing zone: verify its DNS manually"
            else:
                apex = ensure_record(client, zone["id"], record_type="A", name=domain, content=str(ip))
                www = ensure_record(client, zone["id"], record_type="CNAME", name=f"www.{domain}", content=domain)
                row["dns_status"] = f"@ A {apex}; www CNAME {www} (DNS only)"
            print(f"[{index}/{len(domains)}] {domain}: {row['zone_status']}; {row['dns_status']}")
        except CloudflareError as exc:
            failures += 1
            row["zone_status"] = row["zone_status"] or "error"
            row["dns_status"] = row["dns_status"] or "error"
            row["note"] = str(exc)
            print(f"[{index}/{len(domains)}] {domain}: ERROR {exc}", file=sys.stderr)
        rows.append(row)
        save_rows(args.output, rows)
    print(f"Wrote {args.output} ({len(rows) - failures} ok, {failures} errors)")
    return 1 if failures else 0


if __name__ == "__main__":
    sys.exit(main())
