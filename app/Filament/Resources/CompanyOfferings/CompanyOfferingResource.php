<?php

namespace App\Filament\Resources\CompanyOfferings;

use App\Filament\Resources\CompanyOfferings\Pages\CreateCompanyOffering;
use App\Filament\Resources\CompanyOfferings\Pages\EditCompanyOffering;
use App\Filament\Resources\CompanyOfferings\Pages\ListCompanyOfferings;
use App\Models\CompanyOffering;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompanyOfferingResource extends Resource
{
    protected static ?string $model = CompanyOffering::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquaresPlus;
    protected static ?string $modelLabel = 'Ürün veya Hizmet';
    protected static ?string $pluralModelLabel = 'Ürün ve Hizmetler';
    protected static bool $shouldRegisterNavigation = false;

    public static function getEloquentQuery(): Builder
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        return parent::getEloquentQuery()
            ->where('directory_id', $directory?->id ?? 0);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('company_id')
                ->label('Firma')
                ->relationship('company', 'name')
                ->searchable()
                ->required()
                ->disabledOn('edit')
                ->helperText('Seçili rehberdeki firmalardan birini seçin. Vitrin yalnız aktif premium firmada görünür.'),
            Grid::make(2)->schema([
                Select::make('type')->label('Tür')->options(['service' => 'Hizmet', 'product' => 'Ürün'])->required(),
                TextInput::make('name')->label('Ad')->required()->maxLength(180),
            ]),
            Textarea::make('description')->label('Açıklama')->rows(4)->maxLength(3000),
            Grid::make(2)->schema([
                TextInput::make('price')->label('Fiyat (TL)')->numeric()->minValue(0),
                TextInput::make('sort_order')->label('Sıralama')->numeric()->default(0)->minValue(0),
            ]),
            FileUpload::make('image_path')->label('Görsel')->image()->disk('public')->directory('company-offerings')->maxSize(4096),
            Select::make('status')->label('Durum')->options(['active' => 'Yayında', 'draft' => 'Taslak'])->default('active')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')->label('Görsel'),
                TextColumn::make('company.name')->label('Firma')->searchable(),
                TextColumn::make('name')->label('Ad')->searchable(),
                TextColumn::make('type')->label('Tür')->formatStateUsing(fn (string $state) => $state === 'product' ? 'Ürün' : 'Hizmet')->badge(),
                TextColumn::make('status')->label('Durum')->badge(),
                TextColumn::make('price')->label('Fiyat')->money('TRY'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyOfferings::route('/'),
            'create' => CreateCompanyOffering::route('/create'),
            'edit' => EditCompanyOffering::route('/{record}/edit'),
        ];
    }
}
