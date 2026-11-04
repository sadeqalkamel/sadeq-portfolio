<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('English Content')
                ->schema([

                    TextInput::make('title')
                        ->label('Project Title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(
                            function (
                                Get $get,
                                Set $set,
                                ?string $old,
                                ?string $state
                            ) {
                                if (
                                    ($get('slug') ?? '') !==
                                    Str::slug($old ?? '')
                                ) {
                                    return;
                                }

                                $set(
                                    'slug',
                                    Str::slug($state ?? '')
                                );
                            }
                        ),

                    TextInput::make('slug')
                        ->label('Project Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Textarea::make('short_description')
                        ->label('Short Description')
                        ->rows(3)
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Full Description')
                        ->rows(6)
                        ->columnSpanFull(),

                ])->columns(2),
            Section::make('Arabic Content')
    ->description('Project information in Arabic')
    ->schema([

        TextInput::make('title_ar')
            ->label('اسم المشروع بالعربية')
            ->placeholder('أدخل اسم المشروع')
            ->maxLength(255)
            ->extraInputAttributes([
                'dir' => 'rtl',
                'style' => 'text-align: right;',
            ]),

        Textarea::make('short_description_ar')
            ->label('الوصف المختصر بالعربية')
            ->placeholder('اكتب وصفًا مختصرًا للمشروع')
            ->rows(3)
            ->extraInputAttributes([
                'dir' => 'rtl',
                'style' => 'text-align: right;',
            ])
            ->columnSpanFull(),

        Textarea::make('description_ar')
            ->label('الوصف الكامل بالعربية')
            ->placeholder('اكتب تفاصيل المشروع باللغة العربية')
            ->rows(6)
            ->extraInputAttributes([
                'dir' => 'rtl',
                'style' => 'text-align: right;',
            ])
            ->columnSpanFull(),

    ])
    ->columns(2),

            Section::make('Project Media & Technologies')
                ->schema([

                    FileUpload::make('cover_image')
                        ->label('Project Cover Image')
                        ->image()
                        ->disk('public')
                        ->directory('projects')
                        ->maxSize(3072),

                    TagsInput::make('technologies')
                        ->label('Technologies Used')
                        ->placeholder('Add technology')
                        ->default([]),

                ])->columns(2),

            Section::make('Project Links')
                ->schema([

                    TextInput::make('github_url')
                        ->label('GitHub URL')
                        ->url(),

                    TextInput::make('live_url')
                        ->label('Live Demo URL')
                        ->url(),

                ])->columns(2),

            Section::make('Publishing Settings')
                ->schema([

                    Toggle::make('is_published')
                        ->label('Published')
                        ->default(false),

                    Toggle::make('is_featured')
                        ->label('Featured Project')
                        ->default(false),

                    TextInput::make('sort_order')
                        ->label('Display Order')
                        ->numeric()
                        ->minValue(0)
                        ->default(0),

                ])->columns(3),

        ]);
    }
}