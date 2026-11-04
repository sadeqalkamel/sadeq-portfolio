<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->visibility('public')
                    ->imageWidth(80)
                    ->imageHeight(50),

                TextColumn::make('title')
    ->label('Project Title (EN)')
    ->searchable()
    ->sortable(),

TextColumn::make('title_ar')
    ->label('اسم المشروع (AR)')
    ->placeholder('لم تتم الترجمة')
    ->searchable()
    ->toggleable(),

                TextColumn::make('technologies')
                    ->label('Technologies')
                    ->badge(),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y')
                    ->sortable(),

            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}