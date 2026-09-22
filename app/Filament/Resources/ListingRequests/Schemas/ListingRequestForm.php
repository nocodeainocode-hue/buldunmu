<?php

namespace App\Filament\Resources\ListingRequests\Schemas;

use App\Models\Category;
use App\Models\Directory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ListingRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('directory_id')
                    ->label('Rehber')
                    ->options(fn (): array => Directory::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('company_name')
                    ->label('Firma Adı')
                    ->required(),
                TextInput::make('contact_name'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('whatsapp'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('website')
                    ->url(),
                Select::make('category_id')
                    ->label('Kategori')
                    ->options(fn (): array => Category::withoutGlobalScope('directory')
                        ->where('status', 'active')
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload(),
                TextInput::make('requested_category')
                    ->label('Firma Sahibinin Kategori Talebi')
                    ->maxLength(120),
                TextInput::make('city_id')
                    ->numeric(),
                TextInput::make('district_id')
                    ->numeric(),
                Textarea::make('message')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('new'),
            ]);
    }
}
