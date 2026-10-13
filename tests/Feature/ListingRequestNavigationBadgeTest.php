<?php

namespace Tests\Feature;

use App\Filament\Resources\ListingRequests\ListingRequestResource;
use App\Filament\Resources\ListingRequests\Pages\ListListingRequests;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\ListingRequest;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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

    private function admin(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_the_menu_badge_counts_only_new_completed_applications(): void
    {
        $this->assertNull(ListingRequestResource::getNavigationBadge());

        // 1. adımda kalan yarım başvurular rozete eklenmez.
        $this->request(['company_name' => 'Yarım 1', 'is_partial' => true]);
        $this->request(['company_name' => 'Yarım 2', 'is_partial' => true]);
        $this->assertNull(ListingRequestResource::getNavigationBadge());

        $this->request(['company_name' => 'Yeni 1']);
        $this->request(['company_name' => 'Yeni 2']);
        $this->assertSame('2', ListingRequestResource::getNavigationBadge());

        // İncelenmiş başvuru sayılmaz.
        $this->request(['company_name' => 'Onaylı', 'status' => 'approved']);
        $this->assertSame('2', ListingRequestResource::getNavigationBadge());
    }

    public function test_a_partial_application_can_be_added_as_a_company(): void
    {
        $partial = $this->request(['company_name' => 'Yarım Klinik', 'is_partial' => true]);
        $this->admin();

        Livewire::test(ListListingRequests::class)
            ->assertTableActionVisible('approve_partial', $partial)
            ->assertTableActionVisible('close_partial', $partial)
            ->callTableAction('approve_partial', $partial, ['category_id' => $partial->category_id])
            ->assertHasNoTableActionErrors();

        $company = Company::withoutGlobalScopes()->where('name', 'Yarım Klinik')->firstOrFail();
        $this->assertSame('active', $company->status);
        $this->assertSame($partial->directory_id, $company->directory_id);
        $this->assertSame('approved', $partial->fresh()->status);
        $this->assertNull(ListingRequestResource::getNavigationBadge());
    }

    public function test_a_partial_application_can_be_closed_one_by_one_or_in_bulk(): void
    {
        $one = $this->request(['company_name' => 'Kapatılacak 1', 'is_partial' => true]);
        $two = $this->request(['company_name' => 'Kapatılacak 2', 'is_partial' => true]);
        $three = $this->request(['company_name' => 'Kapatılacak 3', 'is_partial' => true]);
        $complete = $this->request(['company_name' => 'Tamamlanmış']);
        $this->admin();

        Livewire::test(ListListingRequests::class)
            ->callTableAction('close_partial', $one)
            ->assertHasNoTableActionErrors()
            ->callTableBulkAction('close_partials', [$two, $three, $complete]);

        $this->assertSame('rejected', $one->fresh()->status);
        $this->assertSame('rejected', $two->fresh()->status);
        $this->assertSame('rejected', $three->fresh()->status);
        // Toplu işlem yalnızca yarım başvuruları kapatır; tamamlanmış başvuruya dokunmaz.
        $this->assertSame('new', $complete->fresh()->status);
    }

    public function test_complete_applications_do_not_get_the_partial_actions(): void
    {
        $complete = $this->request(['company_name' => 'Tamamlanmış']);
        $this->admin();

        Livewire::test(ListListingRequests::class)
            ->assertTableActionHidden('approve_partial', $complete)
            ->assertTableActionHidden('close_partial', $complete)
            ->assertTableActionVisible('approve', $complete);
    }
}
