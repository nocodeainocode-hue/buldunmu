<?php

namespace App\Filament\Resources\Districts\Schemas;

use App\Models\City;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DistrictForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('city_id')
                    ->label('Şehir')
                    ->options(function () {
                        $dirId = app()->bound('currentDirectory') ? app('currentDirectory')->id : null;

                        return City::withoutGlobalScope('directory')
                            ->where(function (Builder $query) use ($dirId): void {
                                $query->whereNull('cities.directory_id');

                                if ($dirId) {
                                    $query->orWhere('cities.directory_id', $dirId);
                                }
                            })
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('İlçe Adı')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),
            ]);
    }
}
