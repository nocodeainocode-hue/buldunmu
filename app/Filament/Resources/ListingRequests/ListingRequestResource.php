<?php

namespace App\Filament\Resources\ListingRequests;

use App\Filament\Resources\ListingRequests\Pages\CreateListingRequest;
use App\Filament\Resources\ListingRequests\Pages\EditListingRequest;
use App\Filament\Resources\ListingRequests\Pages\ListListingRequests;
use App\Filament\Resources\ListingRequests\Schemas\ListingRequestForm;
use App\Filament\Resources\ListingRequests\Tables\ListingRequestsTable;
use App\Models\ListingRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ListingRequestResource extends Resource
{
    protected static ?string $model = ListingRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'company_name';

    public static function form(Schema $schema): Schema
    {
        return ListingRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ListingRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListListingRequests::route('/'),
            'create' => CreateListingRequest::route('/create'),
            'edit' => EditListingRequest::route('/{record}/edit'),
        ];
    }
}
