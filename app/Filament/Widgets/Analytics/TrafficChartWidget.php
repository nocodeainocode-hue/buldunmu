<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Widgets\ChartWidget;

class TrafficChartWidget extends ChartWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Son 30 Günlük Trafik';

    public static function canView(): bool
    {
        return request()?->route()?->getController() instanceof \App\Filament\Pages\AnalyticsDashboard;
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

            $count = PageView::query()
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
        $page = $this->getPage();

        if ($page && property_exists($page, 'directoryFilter')) {
            return $page->directoryFilter;
        }

        return null;
    }
}
