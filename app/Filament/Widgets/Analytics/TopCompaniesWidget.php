<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\Company;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopCompaniesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'md' => 1,
    ];

    protected static ?string $heading = 'En Popüler Firmalar (Top 10)';

    public static function canView(): bool
    {
        return request()?->route()?->getController() instanceof \App\Filament\Pages\AnalyticsDashboard;
    }

    public function table(Table $table): Table
    {
        $directoryId = $this->getPageDirectoryId();

        $query = Company::query()
            ->withCount(['pageViews as views_count' => function ($q) use ($directoryId) {
                if ($directoryId) {
                    $q->directory($directoryId);
                }
            }])
            ->having('views_count', '>', 0)
            ->orderByDesc('views_count')
            ->limit(10);

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Firma')
                    ->searchable()
                    ->url(fn (Company $record) => route('filament.admin.resources.companies.edit', $record)),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Görüntülenme')
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false);
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
