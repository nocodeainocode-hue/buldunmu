<?php

namespace Tests\Feature;

use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SyncTurkeyDistrictsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_syncs_shared_districts_and_skips_unknown_cities(): void
    {
        $city = City::create(['name' => 'Adana', 'slug' => 'adana']);

        Http::fake([
            'raw.githubusercontent.com/*' => Http::response([
                [
                    'name' => ['local' => 'Seyhan'],
                    'parent' => ['name' => ['local' => 'Adana']],
                ],
                [
                    'name' => ['local' => 'Bilinmeyen İlçe'],
                    'parent' => ['name' => ['local' => 'Bilinmeyen İl']],
                ],
            ]),
        ]);

        $this->artisan('catalog:sync-districts')
            ->expectsOutput('1 ilçe ortak kataloğa eklendi veya güncellendi.')
            ->expectsOutput('1 kayıt eşleşen ortak şehir bulunamadığı için atlandı.')
            ->assertSuccessful();

        $this->assertDatabaseHas('districts', [
            'city_id' => $city->id,
            'name' => 'Seyhan',
            'slug' => 'seyhan',
            'directory_id' => null,
        ]);
    }
}
