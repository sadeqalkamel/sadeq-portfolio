<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessages extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Messages')

            ->query(
                ContactMessage::query()
                    ->latest('created_at')
            )

            ->columns([

                TextColumn::make('name')
                    ->label('Sender')
                    ->searchable(),

                TextColumn::make('subject')
                    ->label('Subject')
                    ->limit(50)
                    ->searchable(),

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
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

            ])

            ->defaultSort('created_at', 'desc')

            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)

            ->recordUrl(
                fn (ContactMessage $record): string =>
                    ContactMessageResource::getUrl(
                        'view',
                        ['record' => $record]
                    )
            );
    }
}