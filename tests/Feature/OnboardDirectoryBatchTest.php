<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardDirectoryBatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_batch_preview_and_apply_create_only_new_passive_directories(): void
    {
        $existing = Directory::create([
            'domain' => 'nearbiz.com.tr',
            'name' => 'Existing Nearbiz',
            'slug' => 'nearbiz',
            'template' => 'corporate',
            'status' => 'active',
        ]);

        $this->artisan('directories:onboard-batch')->assertSuccessful();
        $this->assertDatabaseCount('directories', 1);

        $this->artisan('directories:onboard-batch', ['--apply' => true])->assertSuccessful();
        $this->assertDatabaseCount('directories', 44);
        $this->assertDatabaseHas('directories', [
            'domain' => 'tekirdagfirmarehberi.com.tr',
            'name' => 'Tekirdağ Firma Rehberi',
            'status' => 'passive',
        ]);
        $this->assertDatabaseHas('site_settings', [
            'directory_id' => Directory::where('domain', 'firmakonum.com.tr')->value('id'),
            'site_name' => 'Firma Konum',
        ]);

        $this->artisan('directories:onboard-batch', ['--apply' => true])->assertSuccessful();
        $this->assertDatabaseCount('directories', 44);
        $this->assertSame('Existing Nearbiz', $existing->fresh()->name);
        $this->assertSame('active', $existing->fresh()->status);
        $this->assertSame('corporate', $existing->fresh()->template);
        $this->assertSame('Existing Nearbiz', SiteSetting::withoutGlobalScope('directory')->where('directory_id', $existing->id)->value('site_name'));
    }
}
