<?php

namespace App\Filament\Resources\ClaimInvites\Pages;

use App\Filament\Resources\ClaimInvites\ClaimInviteResource;
use App\Services\ClaimInviteGenerator;
use App\Models\Category;
use App\Models\City;
use App\Models\ClaimInvite;
use App\Models\Directory;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListClaimInvites extends ListRecords
{
    protected static string $resource = ClaimInviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Davet oluştur')
                ->icon('heroicon-o-plus')
                ->modalHeading('Yeni davet partisi')
                ->modalDescription('Seçtiğiniz rehberdeki, henüz davet edilmemiş ve cep numarası olan firmalar için bağlantı ve hazır mesaj üretilir.')
                ->form([
                    Select::make('directory_id')->label('Rehber')->required()->searchable()
                        ->options(fn () => Directory::orderBy('name')->pluck('name', 'id')->all()),
                    Select::make('city_id')->label('Şehir (isteğe bağlı)')->searchable()
                        ->options(fn () => City::withoutGlobalScope('directory')->whereNull('directory_id')->orderBy('name')->pluck('name', 'id')->all()),
                    Select::make('category_id')->label('Kategori (isteğe bağlı)')->searchable()
                        ->options(fn () => Category::withoutGlobalScope('directory')->whereNull('directory_id')->orderBy('name')->pluck('name', 'id')->all()),
                    TextInput::make('limit')->label('Kaç firma?')->numeric()->minValue(1)->maxValue(500)->default(50)->required()
                        ->helperText('Önce küçük bir partiyle (50) deneyip tıklama oranına bakmanızı öneririz.'),
                    Toggle::make('mobile_only')->label('Yalnızca cep numaraları (WhatsApp için)')->default(true),
                    Textarea::make('template')->label('Mesaj şablonu')->rows(5)->required()
                        ->default(ClaimInvite::DEFAULT_TEMPLATE)
                        ->helperText('Kullanılabilir alanlar: {firma}, {rehber}, {link}. "İstemiyorum" satırını silmeyin.'),
                ])
                ->action(function (array $data): void {
                    $created = app(ClaimInviteGenerator::class)->generate(
                        (int) $data['directory_id'],
                        isset($data['city_id']) ? (int) $data['city_id'] : null,
                        isset($data['category_id']) ? (int) $data['category_id'] : null,
                        (int) $data['limit'],
                        (bool) ($data['mobile_only'] ?? true),
                        (string) $data['template'],
                    );

                    Notification::make()
                        ->title($created > 0 ? "{$created} davet hazırlandı" : 'Uygun firma bulunamadı')
                        ->body($created > 0 ? 'Listede her satırdaki WhatsApp düğmesiyle gönderebilirsiniz.' : 'Filtreleri gevşetin ya da firmaların daha önce davet edilmediğinden emin olun.')
                        ->{$created > 0 ? 'success' : 'warning'}()
                        ->send();
                }),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['all' => Tab::make('Tümü')->badge(ClaimInvite::count())];

        foreach (ClaimInvite::STATUSES as $status => $label) {
            $tabs[$status] = Tab::make($label)
                ->badge(ClaimInvite::where('status', $status)->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status));
        }

        return $tabs;
    }
}
