<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndex('directories', ['domain'], 'directories_domain_idx');
        $this->addIndex('site_settings', ['directory_id'], 'site_settings_directory_idx');
        $this->addIndex('companies', ['directory_id', 'status'], 'companies_directory_status_idx');
        $this->addIndex('companies', ['directory_id', 'category_id', 'status'], 'companies_directory_category_status_idx');
        $this->addIndex('companies', ['directory_id', 'city_id', 'status'], 'companies_directory_city_status_idx');
        $this->addIndex('companies', ['directory_id', 'district_id', 'status'], 'companies_directory_district_status_idx');
        $this->addIndex('listing_requests', ['directory_id', 'status'], 'listing_requests_directory_status_idx');
        $this->addIndex('campaigns', ['status', 'start_date'], 'campaigns_status_start_idx');
        $this->addIndex('campaign_items', ['campaign_id', 'status', 'scheduled_for'], 'campaign_items_publish_idx');
    }

    public function down(): void
    {
        $this->dropIndex('directories', 'directories_domain_idx');
        $this->dropIndex('site_settings', 'site_settings_directory_idx');
        $this->dropIndex('companies', 'companies_directory_status_idx');
        $this->dropIndex('companies', 'companies_directory_category_status_idx');
        $this->dropIndex('companies', 'companies_directory_city_status_idx');
        $this->dropIndex('companies', 'companies_directory_district_status_idx');
        $this->dropIndex('listing_requests', 'listing_requests_directory_status_idx');
        $this->dropIndex('campaigns', 'campaigns_status_start_idx');
        $this->dropIndex('campaign_items', 'campaign_items_publish_idx');
    }

    private function addIndex(string $table, array $columns, string $name): void
    {
        if (! Schema::hasTable($table) || $this->indexExists($table, $name)) {
            return;
        }

        Schema::table($table, fn (Blueprint $blueprint) => $blueprint->index($columns, $name));
    }

    private function dropIndex(string $table, string $name): void
    {
        if (! Schema::hasTable($table) || ! $this->indexExists($table, $name)) {
            return;
        }

        Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropIndex($name));
    }

    private function indexExists(string $table, string $name): bool
    {
        return collect(Schema::getIndexes($table))->contains(fn (array $index): bool => $index['name'] === $name);
    }
};
