<?php

namespace App\Jobs;

use App\Models\CompanyImportBatch;
use App\Services\CompanyImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RollbackCompanyImport implements ShouldQueue
{
    use Queueable;

    public int $timeout = 1800;

    public function __construct(public int $batchId) {}

    public function handle(CompanyImportService $service): void
    {
        $service->rollback(CompanyImportBatch::findOrFail($this->batchId));
    }
}
