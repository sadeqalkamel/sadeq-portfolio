<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('name')
                    ->label('Sender')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(45),

                TextColumn::make('status')
                    ->label('Status')
                    ->state(
                        fn (ContactMessage $record): string =>
                            $record->read_at
                                ? 'Read'
                                : 'Unread'
                    )
                    ->badge()
                    ->color(
                        fn (ContactMessage $record): string =>
                            $record->read_at
                                ? 'success'
                                : 'warning'
                    ),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),

            ])

            ->filters([

                Filter::make('unread')
                    ->label('Unread Only')
                    ->query(
                        fn (Builder $query): Builder =>
                            $query->whereNull('read_at')
                    ),

            ])

            ->defaultSort('created_at', 'desc')

            ->recordActions([

                ViewAction::make()
                    ->label('View'),

                Action::make('markRead')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(
                        fn (ContactMessage $record): bool =>
                            $record->read_at === null
                    )
                    ->action(function (
                        ContactMessage $record
                    ): void {
                        $record->read_at = now();
                        $record->save();
                    }),

                Action::make('markUnread')
                    ->label('Mark Unread')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')
                    ->visible(
                        fn (ContactMessage $record): bool =>
                            $record->read_at !== null
                    )
                    ->action(function (
                        ContactMessage $record
                    ): void {
                        $record->read_at = null;
                        $record->save();
                    }),

                DeleteAction::make()
                    ->label('Delete')
                    ->requiresConfirmation(),

            ]);
    }
}