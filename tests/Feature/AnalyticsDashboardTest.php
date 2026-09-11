<?php

namespace Tests\Feature;

use App\Filament\Widgets\Analytics\PageViewStats;
use App\Filament\Widgets\Analytics\TopCompaniesWidget;
use App\Filament\Widgets\Analytics\TopPagesWidget;
use App\Filament\Widgets\Analytics\TrafficChartWidget;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\PageView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_open_analytics_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/analytics-dashboard')
            ->assertOk()
            ->assertSee('Analitik Paneli');
    }

    public function test_widgets_read_the_reactive_filament_page_filter(): void
    {
        foreach ([PageViewStats::class, TrafficChartWidget::class, TopCompaniesWidget::class, TopPagesWidget::class] as $widgetClass) {
            $widget = app($widgetClass);
            $widget->pageFilters = ['directoryFilter' => '42'];
            $method = new ReflectionMethod($widget, 'getPageDirectoryId');

            $this->assertSame(42, $method->invoke($widget));
        }
    }

    public function test_all_directories_chart_ignores_the_admin_session_scope(): void
    {
        $first = Directory::create([
            'name' => 'Birinci Rehber',
            'slug' => 'birinci-rehber',
            'domain' => 'birinci.test',
            'status' => 'active',
        ]);
        $second = Directory::create([
            'name' => 'İkinci Rehber',
            'slug' => 'ikinci-rehber',
            'domain' => 'ikinci.test',
            'status' => 'active',
        ]);

        foreach ([$first, $second] as $directory) {
            PageView::withoutGlobalScope('directory')->create([
                'path' => '/',
                'ip_hash' => hash('sha256', (string) $directory->id),
                'directory_id' => $directory->id,
                'created_at' => now(),
            ]);
        }

        app()->instance('currentDirectory', $first);
        $widget = app(TrafficChartWidget::class);
        $widget->pageFilters = ['directoryFilter' => null];
        $method = new ReflectionMethod($widget, 'getData');
        $data = $method->invoke($widget);

        $this->assertSame(2, array_sum($data['datasets'][0]['data']));

        $widget->pageFilters = ['directoryFilter' => $second->id];
        $data = $method->invoke($widget);

        $this->assertSame(1, array_sum($data['datasets'][0]['data']));
    }

    public function test_top_companies_query_does_not_use_a_postgres_incompatible_having_alias(): void
    {
        $directory = Directory::create([
            'name' => 'Test Rehber',
            'slug' => 'test-rehber',
            'domain' => 'test.local',
            'status' => 'active',
        ]);
        $category = Category::create([
            'name' => 'Genel',
            'slug' => 'genel',
            'status' => 'active',
            'directory_id' => null,
        ]);
        $city = City::create([
            'name' => 'Tekirdağ',
            'slug' => 'tekirdag',
            'directory_id' => null,
        ]);
        $company = Company::create([
            'name' => 'Test Firma',
            'category_id' => $category->id,
            'city_id' => $city->id,
            'directory_id' => $directory->id,
            'status' => 'active',
        ]);
        PageView::withoutGlobalScope('directory')->create([
            'path' => '/firma/'.$company->slug,
            'ip_hash' => hash('sha256', 'test'),
            'company_id' => $company->id,
            'directory_id' => $directory->id,
            'created_at' => now(),
        ]);

        app()->instance('currentDirectory', $directory);
        $widget = app(TopCompaniesWidget::class);
        $method = new ReflectionMethod($widget, 'getTopCompaniesQuery');
        $query = $method->invoke($widget, $directory->id);

        $this->assertStringNotContainsString(' having ', strtolower($query->toSql()));
        $this->assertSame($company->id, $query->first()?->id);
    }

    public function test_top_pages_query_uses_an_aggregate_record_key_and_disables_filament_key_sort(): void
    {
        $directory = Directory::create([
            'name' => 'Analitik Rehber',
            'slug' => 'analitik-rehber',
            'domain' => 'analitik.test',
            'status' => 'active',
        ]);

        foreach (['/', '/', '/firmalar'] as $index => $path) {
            PageView::withoutGlobalScope('directory')->create([
                'path' => $path,
                'ip_hash' => hash('sha256', (string) $index),
                'directory_id' => $directory->id,
                'created_at' => now(),
            ]);
        }

        app()->instance('currentDirectory', $directory);
        $widget = app(TopPagesWidget::class);
        $method = new ReflectionMethod($widget, 'getTopPagesQuery');
        $query = $method->invoke($widget, $directory->id);
        $records = $query->get();

        $this->assertStringContainsString('MIN(page_views.id) as id', $query->toSql());
        $this->assertCount(2, $records);
        $this->assertSame(2, (int) $records->firstWhere('path', '/')->views);
        $this->assertNotNull($records->first()->id);
        $widget->bootedInteractsWithTable();
        $this->assertFalse($widget->getTable()->hasDefaultKeySort());
    }
}
