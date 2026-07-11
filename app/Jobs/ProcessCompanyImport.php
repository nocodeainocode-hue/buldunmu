<?php

namespace App\Jobs;

use App\Models\CompanyImportBatch;
use App\Services\CompanyImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCompanyImport implements ShouldQueue
{
    use Queueable;

    public int $timeout = 1800;
    public int $tries = 2;

    public function __construct(public int $batchId) {}

    public function handle(CompanyImportService $service): void
    {
        $batch = CompanyImportBatch::findOrFail($this->batchId);
        $service->process($batch);
    }
}
