<?php

namespace Tests\Feature;

use App\Filament\Resources\ListingRequests\ListingRequestResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\ListingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingRequestNavigationBadgeTest extends TestCase
{
    use RefreshDatabase;

    private function request(array $attributes = []): ListingRequest
    {
        $directory = Directory::firstOrCreate(['domain' => 'rozet.test'], ['name' => 'Rozet', 'slug' => 'rozet', 'status' => 'active']);
        $category = Category::firstOrCreate(['slug' => 'dis-klinigi'], ['name' => 'Diş Kliniği', 'status' => 'active']);
        $city = City::firstOrCreate(['slug' => 'tekirdag'], ['name' => 'Tekirdağ']);

        return ListingRequest::create($attributes + [
            'company_name' => 'Firma', 'phone' => '0282 123 45 67', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'new', 'source' => 'owner_registration', 'is_partial' => false,
        ]);
    }

    public function test_the_menu_badge_counts_every_unreviewed_request_including_partial_ones(): void
    {
        $this->assertNull(ListingRequestResource::getNavigationBadge());

        // 1. adımda bırakılan başvurular da rozete eklenir (yönetici bunları da firma olarak ekleyip sonuçlandırır).
        $this->request(['company_name' => 'Yarım Kalan', 'is_partial' => true]);
        $this->assertSame('1', ListingRequestResource::getNavigationBadge());

        $this->request(['company_name' => 'Tamamlanan']);
        $this->assertSame('2', ListingRequestResource::getNavigationBadge());
    }

    public function test_reviewed_requests_are_not_counted(): void
    {
        $this->request(['company_name' => 'Onaylanmış', 'status' => 'approved']);
        $this->request(['company_name' => 'Reddedilmiş', 'status' => 'rejected', 'is_partial' => true]);

        $this->assertNull(ListingRequestResource::getNavigationBadge());

        $this->request(['company_name' => 'Bekleyen']);
        $this->assertSame('1', ListingRequestResource::getNavigationBadge());
    }
}
