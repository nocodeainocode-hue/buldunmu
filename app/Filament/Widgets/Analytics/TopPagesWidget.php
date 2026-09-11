<?php

namespace App\Filament\Widgets\Analytics;

use App\Filament\Pages\AnalyticsDashboard;
use App\Models\PageView;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopPagesWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = [
        'md' => 1,
    ];

    protected static ?string $heading = 'En Popüler Sayfalar';

    public static function canView(): bool
    {
        return request()?->route()?->getController() instanceof AnalyticsDashboard;
    }

    public function table(Table $table): Table
    {
        $directoryId = $this->getPageDirectoryId();

        return $table
            ->query(
                PageView::withoutGlobalScope('directory')
                    ->select('path')
                    ->selectRaw('COUNT(*) as views')
                    ->when($directoryId, fn (Builder $q) => $q->directory($directoryId))
                    ->groupBy('path')
                    ->orderByDesc('views')
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\TextColumn::make('path')
                    ->label('Sayfa')
                    ->searchable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('views')
                    ->label('Görüntülenme')
                    ->sortable()
                    ->alignEnd(),
            ])
            ->paginated(false);
    }

    private function getPageDirectoryId(): ?int
    {
        $directoryId = $this->pageFilters['directoryFilter'] ?? null;

        return filled($directoryId) ? (int) $directoryId : null;
    }
}
