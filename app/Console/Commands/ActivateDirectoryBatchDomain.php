<?php

namespace App\Console\Commands;

use App\Models\Directory;
use Illuminate\Console\Command;

class ActivateDirectoryBatchDomain extends Command
{
    protected $signature = 'directories:activate-batch-domain {domain}';

    protected $description = 'Activate one domain from the October 2026 directory batch after origin setup';

    public function handle(): int
    {
        $domain = strtolower(trim((string) $this->argument('domain')));
        $batch = array_filter(array_map('trim', file(base_path('ops/cloudflare-domains-2026-10.txt')) ?: []));

        if (! in_array($domain, $batch, true)) {
            $this->error('Domain is not in the October 2026 batch.');

            return self::FAILURE;
        }

        $directory = Directory::query()->where('domain', $domain)->first();
        if (! $directory) {
            $this->error("Directory not found: {$domain}");

            return self::FAILURE;
        }

        if ($directory->status === 'active') {
            $this->info("Already active: {$domain}");

            return self::SUCCESS;
        }

        $directory->status = 'active';
        $directory->save();
        $this->info("Activated: {$domain}");

        return self::SUCCESS;
    }
}
