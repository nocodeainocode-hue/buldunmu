<?php

namespace App\Filament\Resources\MembershipPlans;

use App\Filament\Resources\MembershipPlans\Pages\CreateMembershipPlan;
use App\Filament\Resources\MembershipPlans\Pages\EditMembershipPlan;
use App\Filament\Resources\MembershipPlans\Pages\ListMembershipPlans;
use App\Filament\Resources\MembershipPlans\Schemas\MembershipPlanForm;
use App\Filament\Resources\MembershipPlans\Tables\MembershipPlansTable;
use App\Models\MembershipPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MembershipPlanResource extends Resource
{
    protected static ?string $model = MembershipPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    protected static ?string $navigationLabel = 'Üyelik Paketleri';
    protected static ?string $modelLabel = 'Üyelik Paketi';
    protected static ?string $pluralModelLabel = 'Üyelik Paketleri';
    protected static string|\UnitEnum|null $navigationGroup = 'Site Yönetimi';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MembershipPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MembershipPlansTable::configure($table);
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
            'index' => ListMembershipPlans::route('/'),
            'create' => CreateMembershipPlan::route('/create'),
            'edit' => EditMembershipPlan::route('/{record}/edit'),
        ];
    }
}
