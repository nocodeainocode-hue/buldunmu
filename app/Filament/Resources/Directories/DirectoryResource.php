<?php

namespace App\Filament\Resources\Directories;

use App\Filament\Resources\Directories\Pages\CreateDirectory;
use App\Filament\Resources\Directories\Pages\EditDirectory;
use App\Filament\Resources\Directories\Pages\ListDirectories;
use App\Filament\Resources\Directories\Schemas\DirectoryForm;
use App\Filament\Resources\Directories\Tables\DirectoriesTable;
use App\Models\Directory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DirectoryResource extends Resource
{
    protected static ?string $model = Directory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Rehberler';

    protected static ?string $pluralModelLabel = 'Rehberler';

    protected static ?string $modelLabel = 'Rehber';
    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DirectoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DirectoriesTable::configure($table);
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
            'index' => ListDirectories::route('/'),
            'create' => CreateDirectory::route('/create'),
            'edit' => EditDirectory::route('/{record}/edit'),
        ];
    }
}
