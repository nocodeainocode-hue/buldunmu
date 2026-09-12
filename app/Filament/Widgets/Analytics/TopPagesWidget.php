<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\PageView;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
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
        $panel = Filament::getPanel('admin');
        $user = auth($panel->getAuthGuard())->user();

        return $user instanceof FilamentUser && $user->canAccessPanel($panel);
    }

    protected static bool $isDiscovered = false;

    public function table(Table $table): Table
    {
        $directoryId = $this->getPageDirectoryId();

        return $table
            ->query($this->getTopPagesQuery($directoryId))
            ->defaultKeySort(false)
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

    protected function getTopPagesQuery(?int $directoryId): Builder
    {
        return PageView::withoutGlobalScope('directory')
            ->select('path')
            ->selectRaw('MIN(page_views.id) as id, COUNT(*) as views')
            ->when($directoryId, fn (Builder $query) => $query->directory($directoryId))
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(15);
    }

    private function getPageDirectoryId(): ?int
    {
        $directoryId = $this->pageFilters['directoryFilter'] ?? null;

        return filled($directoryId) ? (int) $directoryId : null;
    }
}
