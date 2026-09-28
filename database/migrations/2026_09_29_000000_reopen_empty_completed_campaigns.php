<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('campaigns')
            ->where('status', 'completed')
            ->whereNotIn('id', DB::table('campaign_items')->select('campaign_id'))
            ->update(['status' => 'draft']);
    }

    public function down(): void
    {
        // The original state cannot be safely distinguished from a legitimate draft.
    }
};
