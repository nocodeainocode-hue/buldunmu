<?php

namespace App\Filament\Widgets\Analytics;

use App\Models\Company;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopCompaniesWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = [
        'md' => 1,
    ];

    protected static ?string $heading = 'En Popüler Firmalar (Top 10)';

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
            ->query($this->getTopCompaniesQuery($directoryId))
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

    protected function getTopCompaniesQuery(?int $directoryId): Builder
    {
        return Company::withoutGlobalScope('directory')
            ->when($directoryId, fn ($companies) => $companies->where('directory_id', $directoryId))
            ->whereHas('pageViews', function ($query) use ($directoryId): void {
                $query->withoutGlobalScope('directory')
                    ->when($directoryId, fn ($pageViews) => $pageViews->directory($directoryId));
            })
            ->withCount(['pageViews as views_count' => function ($q) use ($directoryId) {
                $q->withoutGlobalScope('directory');
                if ($directoryId) {
                    $q->directory($directoryId);
                }
            }])
            ->orderByDesc('views_count')
            ->limit(10);
    }

    private function getPageDirectoryId(): ?int
    {
        $directoryId = $this->pageFilters['directoryFilter'] ?? null;

        return filled($directoryId) ? (int) $directoryId : null;
    }
}
