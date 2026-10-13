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
        static $directory = null;
        $directory = Directory::firstOrCreate(['domain' => 'rozet.test'], ['name' => 'Rozet', 'slug' => 'rozet', 'status' => 'active']);
        $category = Category::firstOrCreate(['slug' => 'dis-klinigi'], ['name' => 'Diş Kliniği', 'status' => 'active']);
        $city = City::firstOrCreate(['slug' => 'tekirdag'], ['name' => 'Tekirdağ']);

        return ListingRequest::create($attributes + [
            'company_name' => 'Firma', 'phone' => '0282 123 45 67', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'new', 'source' => 'owner_registration', 'is_partial' => false,
        ]);
    }

    public function test_the_menu_badge_counts_only_completed_new_requests(): void
    {
        $this->assertNull(ListingRequestResource::getNavigationBadge());

        // Yarım kalan (1. adımda bırakılan) başvurular rozeti artırmaz.
        $this->request(['company_name' => 'Yarım Kalan 1', 'is_partial' => true]);
        $this->request(['company_name' => 'Yarım Kalan 2', 'is_partial' => true]);
        $this->assertNull(ListingRequestResource::getNavigationBadge());

        $this->request(['company_name' => 'Tamamlanan 1']);
        $this->request(['company_name' => 'Tamamlanan 2']);
        $this->assertSame('2', ListingRequestResource::getNavigationBadge());

        // İncelenmiş başvuru sayılmaz.
        $this->request(['company_name' => 'Onaylanmış', 'status' => 'approved']);
        $this->assertSame('2', ListingRequestResource::getNavigationBadge());
    }

    public function test_partial_requests_are_still_in_the_list(): void
    {
        $this->request(['company_name' => 'Yarım Firma', 'is_partial' => true]);

        $this->assertSame(1, ListingRequest::withoutGlobalScope('directory')->where('is_partial', true)->count());
        $this->assertNull(ListingRequestResource::getNavigationBadge());
    }
}
