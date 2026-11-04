<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Skill Information / بيانات المهارة')
                ->schema([

                    TextInput::make('name_en')
                        ->label('Skill Name (English)')
                        ->required()
                        ->maxLength(120),

                    TextInput::make('name_ar')
                        ->label('اسم المهارة بالعربية')
                        ->required()
                        ->maxLength(120)
                        ->extraInputAttributes([
                            'dir' => 'rtl',
                        ]),

                    Select::make('category')
                        ->label('Category / التصنيف')
                        ->options([
                            'backend' => 'Backend',
                            'frontend' => 'Frontend',
                            'tools' => 'Tools',
                        ])
                        ->required()
                        ->native(false),

                    TextInput::make('sort_order')
                        ->label('Display Order / الترتيب')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required(),

                ])
                ->columns(2),

            Section::make('Icon & Visibility / الشعار والظهور')
                ->schema([

                    FileUpload::make('icon')
                        ->label('Technology Icon / شعار التقنية')
                        ->image()
                        ->acceptedFileTypes([
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                        ])
                        ->disk('public')
                        ->directory('skills')
                        ->visibility('public')
                        ->maxSize(2048),

                    Toggle::make('is_active')
                        ->label('Visible on website / إظهار في الموقع')
                        ->default(true),

                ])
                ->columns(2),

        ]);
    }
}