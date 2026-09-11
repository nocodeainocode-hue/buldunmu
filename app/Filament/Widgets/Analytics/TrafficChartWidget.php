<?php

namespace App\Filament\Widgets\Analytics;

use App\Filament\Pages\AnalyticsDashboard;
use App\Models\PageView;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class TrafficChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Son 30 Günlük Trafik';

    public static function canView(): bool
    {
        return request()?->route()?->getController() instanceof AnalyticsDashboard;
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $directoryId = $this->getPageDirectoryId();

        $data = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $labels[] = $date->format('d M');

            $count = PageView::withoutGlobalScope('directory')
                ->when($directoryId, fn ($q) => $q->directory($directoryId))
                ->whereDate('created_at', $date)
                ->count();

            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Görüntülenme',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function getPageDirectoryId(): ?int
    {
        $directoryId = $this->pageFilters['directoryFilter'] ?? null;

        return filled($directoryId) ? (int) $directoryId : null;
    }
}
