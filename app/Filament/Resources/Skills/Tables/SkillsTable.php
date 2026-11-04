<?php

namespace App\Filament\Resources\Skills\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('icon')
                    ->label('Icon')
                    ->disk('public')
                    ->visibility('public')
                    ->square()
                    ->size(38),

                TextColumn::make('name_en')
                    ->label('Name (EN)')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name_ar')
                    ->label('الاسم (AR)')
                    ->searchable()
                    ->placeholder('لم تتم الترجمة'),

                TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

            ])
            ->filters([

                SelectFilter::make('category')
                    ->options([
                        'backend' => 'Backend',
                        'frontend' => 'Frontend',
                        'tools' => 'Tools',
                    ]),

            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}