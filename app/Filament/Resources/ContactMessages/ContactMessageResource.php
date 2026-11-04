<?php

namespace App\Filament\Resources\ContactMessages;

use App\Filament\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Filament\Resources\ContactMessages\Tables\ContactMessagesTable;
use App\Models\ContactMessage;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $recordTitleAttribute = 'subject';

    protected static ?string $navigationLabel = 'Messages';

    protected static ?string $modelLabel = 'Message';

    protected static ?string $pluralModelLabel = 'Messages';

    protected static string | BackedEnum | null $navigationIcon =
        'heroicon-o-envelope';

    public static function table(Table $table): Table
    {
        return ContactMessagesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Visitor Message')
                ->schema([

                    TextEntry::make('name')
                        ->label('Sender'),

                    TextEntry::make('email')
                        ->label('Email')
                        ->copyable(),

                    TextEntry::make('subject')
                        ->label('Subject')
                        ->columnSpanFull(),

                    TextEntry::make('message')
                        ->label('Message')
                        ->columnSpanFull(),

                    TextEntry::make('locale')
                        ->label('Language'),

                    TextEntry::make('created_at')
                        ->label('Received')
                        ->dateTime(),

                    TextEntry::make('read_at')
                        ->label('Read At')
                        ->dateTime()
                        ->placeholder('Unread'),

                ])
                ->columns(2),

        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactMessages::route('/'),

            'view' => ViewContactMessage::route('/{record}'),
        ];
    }
}