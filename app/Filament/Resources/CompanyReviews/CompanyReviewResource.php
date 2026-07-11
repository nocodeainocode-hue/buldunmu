<?php

namespace App\Filament\Resources\CompanyReviews;

use App\Filament\Resources\CompanyReviews\Pages\CreateCompanyReview;
use App\Filament\Resources\CompanyReviews\Pages\EditCompanyReview;
use App\Filament\Resources\CompanyReviews\Pages\ListCompanyReviews;
use App\Filament\Resources\CompanyReviews\Schemas\CompanyReviewForm;
use App\Filament\Resources\CompanyReviews\Tables\CompanyReviewsTable;
use App\Models\CompanyReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompanyReviewResource extends Resource
{
    protected static ?string $model = CompanyReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Yorumlar';
    protected static ?string $modelLabel = 'Firma Yorumu';
    protected static ?string $pluralModelLabel = 'Yorumlar';
    protected static string|\UnitEnum|null $navigationGroup = 'Firma Yönetimi';
    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return CompanyReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompanyReviewsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompanyReviews::route('/'),
            'create' => CreateCompanyReview::route('/create'),
            'edit' => EditCompanyReview::route('/{record}/edit'),
        ];
    }
}
