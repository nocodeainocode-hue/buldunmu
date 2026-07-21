<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Analytics\PageViewStats;
use App\Filament\Widgets\Analytics\TrafficChartWidget;
use App\Filament\Widgets\Analytics\TopCompaniesWidget;
use App\Filament\Widgets\Analytics\TopPagesWidget;
use App\Models\Directory;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class AnalyticsDashboard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Analitik';

    protected static ?string $title = 'Analitik Paneli';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.analytics-dashboard';

    public ?int $directoryFilter = null;

    public ?array $filters = [];

    public function mount(): void
    {
        $this->form->fill([
            'directoryFilter' => session('current_directory_id'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('directoryFilter')
                    ->label('Rehber Filtresi')
                    ->placeholder('Tüm Rehberler')
                    ->options(fn () => Directory::orderBy('name')->pluck('name', 'id')->toArray())
                    ->default(session('current_directory_id'))
                    ->nullable()
                    ->live()
                    ->afterStateUpdated(function (?string $state): void {
                        $this->directoryFilter = $state ? (int) $state : null;
                    }),
            ])
            ->statePath('filters');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PageViewStats::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            TrafficChartWidget::class,
            TopCompaniesWidget::class,
            TopPagesWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }

    public function getFooterWidgetsColumns(): int | array
    {
        return [
            'md' => 3,
        ];
    }
}
