<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('English Content')
                ->schema([

                    TextInput::make('title_en')
                        ->label('Service Title (EN)')
                        ->required()
                        ->maxLength(150),

                    Textarea::make('description_en')
                        ->label('Description (EN)')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),

                ])->columns(2),

            Section::make('Arabic Content')
                ->schema([

                    TextInput::make('title_ar')
                        ->label('اسم الخدمة بالعربية')
                        ->required()
                        ->maxLength(150)
                        ->extraInputAttributes([
                            'dir' => 'rtl',
                        ]),

                    Textarea::make('description_ar')
                        ->label('وصف الخدمة بالعربية')
                        ->required()
                        ->rows(4)
                        ->extraInputAttributes([
                            'dir' => 'rtl',
                        ])
                        ->columnSpanFull(),

                ])->columns(2),

            Section::make('Display Settings')
                ->schema([

                    FileUpload::make('icon')
                        ->label('Service Icon')
                        ->image()
                        ->acceptedFileTypes([
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                        ])
                        ->disk('public')
                        ->directory('services')
                        ->visibility('public')
                        ->maxSize(2048),

                    TextInput::make('sort_order')
                        ->label('Display Order')
                        ->integer()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Show on website')
                        ->default(true),

                ])->columns(2),

        ]);
    }
}