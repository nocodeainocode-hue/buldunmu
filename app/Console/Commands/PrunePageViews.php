<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrunePageViews extends Command
{
    protected $signature = 'pageviews:prune {--days= : Saklanacak gün sayısı (varsayılan config/performance.php)} {--dry-run}';

    protected $description = 'Eski sayfa görüntüleme kayıtlarını parça parça siler';

    public function handle(): int
    {
        $days = max(7, (int) ($this->option('days') ?: config('performance.page_view_retention_days')));
        $cutoff = now()->subDays($days);

        if ($this->option('dry-run')) {
            $this->info(DB::table('page_views')->where('created_at', '<', $cutoff)->count()." kayıt silinecek ({$days} günden eski).");

            return self::SUCCESS;
        }

        $total = 0;
        do {
            $ids = DB::table('page_views')->where('created_at', '<', $cutoff)->limit(5000)->pluck('id');
            $deleted = $ids->isEmpty() ? 0 : DB::table('page_views')->whereIn('id', $ids)->delete();
            $total += $deleted;
        } while ($deleted > 0);

        $this->info("{$total} eski kayıt silindi ({$days} günden eski).");

        return self::SUCCESS;
    }
}
