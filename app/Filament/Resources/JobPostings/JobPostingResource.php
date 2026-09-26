<?php

namespace App\Filament\Resources\JobPostings;

use App\Filament\Resources\JobPostings\Pages\CreateJobPosting;
use App\Filament\Resources\JobPostings\Pages\EditJobPosting;
use App\Filament\Resources\JobPostings\Pages\ListJobPostings;
use App\Models\JobPosting;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;
    protected static ?string $modelLabel = 'İş İlanı';
    protected static ?string $pluralModelLabel = 'İş İlanları';
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
                ->helperText('Admin, seçili rehberdeki premium olmayan firma için de ilan yayımlayabilir.'),
            TextInput::make('title')->label('İlan Başlığı')->required()->maxLength(180),
            Textarea::make('description')->label('İş Tanımı ve Başvuru Koşulları')->required()->rows(10)->maxLength(20000),
            Grid::make(2)->schema([
                Select::make('employment_type')->label('Çalışma Şekli')->options([
                    'full_time' => 'Tam zamanlı',
                    'part_time' => 'Yarı zamanlı',
                    'contract' => 'Sözleşmeli',
                    'internship' => 'Staj',
                ])->default('full_time')->required(),
                TextInput::make('location')->label('Çalışma Konumu')->maxLength(180),
                TextInput::make('apply_email')->label('Başvuru E-postası')->email()->maxLength(255)
                    ->required(fn (Get $get) => blank($get('apply_url'))),
                TextInput::make('apply_url')->label('Başvuru Bağlantısı')->url()->maxLength(500)
                    ->required(fn (Get $get) => blank($get('apply_email'))),
                Select::make('status')->label('Durum')->options(['draft' => 'Taslak', 'published' => 'Yayında'])->default('published')->required(),
                DateTimePicker::make('expires_at')->label('Son Başvuru Tarihi')->default(now()->addDays(30)),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('İlan')->searchable(),
                TextColumn::make('company.name')->label('Firma')->searchable(),
                TextColumn::make('employment_type')->label('Çalışma Şekli')->badge(),
                TextColumn::make('status')->label('Durum')->badge(),
                TextColumn::make('admin_published')->label('Admin Yayını')->formatStateUsing(fn (bool $state) => $state ? 'Evet' : 'Hayır')->badge(),
                TextColumn::make('expires_at')->label('Son Başvuru')->date('d.m.Y')->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobPostings::route('/'),
            'create' => CreateJobPosting::route('/create'),
            'edit' => EditJobPosting::route('/{record}/edit'),
        ];
    }
}
