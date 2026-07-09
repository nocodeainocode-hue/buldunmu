<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Blog Yazisi')
                    ->schema([
                        Select::make('directories')
                            ->label('Rehberler')
                            ->relationship('directories', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->minItems(1)
                            ->rules(['required', 'array', 'min:1'])
                            ->validationMessages(['required'=>'En az bir rehber secmelisiniz','min'=>'En az bir rehber secmelisiniz'])
                            ->helperText('En az bir rehber secmelisiniz'),
                        Grid::make(2)->schema([
                            TextInput::make('title')->label('Baslik')->required()->live(onBlur: true)
                                ->afterStateUpdated(fn($s, callable $set) => $set('slug', Str::slug($s))),
                            TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true),
                        ]),
                        Textarea::make('excerpt')->label('Ozet')->rows(2)->columnSpanFull(),
                        RichEditor::make('content')->label('Icerik')->required()->columnSpanFull(),
                        FileUpload::make('image')->label('Gorsel')->image()->disk('public')->directory('posts'),
                    ]),
                Section::make('Yayin')->schema([
                    Grid::make(2)->schema([
                        Select::make('status')->label('Durum')->options(['draft'=>'Taslak','published'=>'Yayinda'])->default('draft'),
                        DateTimePicker::make('published_at')->label('Yayin Tarihi')->default(now()),
                    ]),
                ]),
            ]);
    }
}