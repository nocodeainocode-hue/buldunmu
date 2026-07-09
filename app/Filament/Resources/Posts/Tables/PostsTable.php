<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Gorsel')->circular()->disk('public'),
                TextColumn::make('title')->label('Baslik')->searchable()->sortable(),
                TextColumn::make('directories.name')->label('Rehberler')->badge()->separator(','),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn($s)=>$s==='published'?'Yayinda':'Taslak')
                    ->color(fn($s)=>$s==='published'?'success':'gray'),
                TextColumn::make('published_at')->label('Tarih')->dateTime('d.m.Y')->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('directories')->label('Rehber')->relationship('directories','name'),
                SelectFilter::make('status')->label('Durum')->options(['draft'=>'Taslak','published'=>'Yayinda']),
            ]);
    }
}