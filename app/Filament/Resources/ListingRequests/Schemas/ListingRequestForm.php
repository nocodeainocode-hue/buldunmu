<?php

namespace App\Filament\Resources\ListingRequests\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ListingRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
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
                TextInput::make('category_id')
                    ->numeric(),
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
