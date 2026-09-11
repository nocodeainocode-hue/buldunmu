<?php

namespace Tests\Feature;

use App\Filament\Widgets\Analytics\PageViewStats;
use App\Filament\Widgets\Analytics\TopCompaniesWidget;
use App\Filament\Widgets\Analytics\TopPagesWidget;
use App\Filament\Widgets\Analytics\TrafficChartWidget;
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
}
