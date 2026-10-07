import csv
import tempfile
import unittest
from pathlib import Path

from scripts.cloudflare_onboard import CloudflareError, ensure_record, load_domains, token_from_file


class FakeCloudflare:
    def __init__(self, records):
        self.existing = records
        self.created = []

    def records(self, zone_id, name):
        return self.existing

    def create_record(self, zone_id, **kwargs):
        self.created.append((zone_id, kwargs))


class CloudflareOnboardTest(unittest.TestCase):
    def test_domain_list_matches_directory_batch_and_excludes_nearbiz(self):
        root = Path(__file__).resolve().parents[2]
        domains = set(load_domains(root / "ops/cloudflare-domains-2026-10.txt"))
        with (root / "ops/directory-batch-2026-10.csv").open(encoding="utf-8", newline="") as handle:
            new_domains = {row["domain"] for row in csv.DictReader(handle)}
        self.assertEqual(43, len(domains))
        self.assertEqual(new_domains, domains)
        self.assertNotIn("nearbiz.com.tr", domains)

    def test_domain_file_rejects_duplicate_and_invalid_entries(self):
        with tempfile.TemporaryDirectory() as directory:
            path = Path(directory) / "domains.txt"
            path.write_text("example.com.tr\nexample.com.tr\n", encoding="utf-8")
            with self.assertRaisesRegex(ValueError, "duplicate"):
                load_domains(path)
            path.write_text("example.com.tr\ninvalid.example.org\n", encoding="utf-8")
            with self.assertRaisesRegex(ValueError, "invalid"):
                load_domains(path)

    def test_reads_existing_certbot_token_without_printing_it(self):
        with tempfile.TemporaryDirectory() as directory:
            path = Path(directory) / "cloudflare.ini"
            path.write_text("# Certbot credentials\ndns_cloudflare_api_token = hidden-value\n", encoding="utf-8")
            self.assertEqual("hidden-value", token_from_file(path))

    def test_existing_record_is_never_overwritten(self):
        client = FakeCloudflare([{"type": "A", "name": "example.com.tr", "content": "203.0.113.5"}])
        with self.assertRaises(CloudflareError):
            ensure_record(client, "zone", record_type="A", name="example.com.tr", content="45.143.4.26")
        self.assertEqual([], client.created)

    def test_missing_record_is_created_dns_only(self):
        client = FakeCloudflare([])
        result = ensure_record(client, "zone", record_type="A", name="example.com.tr", content="45.143.4.26")
        self.assertEqual("created", result)
        self.assertEqual(
            [("zone", {"record_type": "A", "name": "example.com.tr", "content": "45.143.4.26"})],
            client.created,
        )


if __name__ == "__main__":
    unittest.main()
