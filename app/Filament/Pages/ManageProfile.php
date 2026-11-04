<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/** @property-read Schema $form */
class ManageProfile extends Page
{
    protected string $view = 'filament.pages.manage-profile';

    protected static ?string $navigationLabel = 'My Profile';

    public ?array $data = [];

    public function getTitle(): string
    {
        return 'Manage My Profile';
    }

    public function mount(): void
    {
        $this->form->fill(
            $this->getRecord()?->attributesToArray()
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([

                    Section::make('English Content')
                        ->schema([
                            TextInput::make('first_name_en')
                                ->label('First Name')
                                ->required()
                                ->maxLength(100),

                            TextInput::make('last_name_en')
                                ->label('Last Name')
                                ->required()
                                ->maxLength(100),

                            TextInput::make('job_title_en')
                                ->label('Job Title')
                                ->maxLength(180),

                            Textarea::make('hero_description_en')
                                ->label('Hero Description')
                                ->rows(3)
                                ->columnSpanFull(),

                            Textarea::make('about_en')
                                ->label('About Me')
                                ->rows(6)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Section::make('Arabic Content')
                        ->schema([
                            TextInput::make('first_name_ar')
                                ->label('الاسم الأول')
                                ->maxLength(100)
                                ->extraInputAttributes(['dir' => 'rtl']),

                            TextInput::make('last_name_ar')
                                ->label('اسم العائلة')
                                ->maxLength(100)
                                ->extraInputAttributes(['dir' => 'rtl']),

                            TextInput::make('job_title_ar')
                                ->label('المسمى الوظيفي')
                                ->maxLength(180)
                                ->extraInputAttributes(['dir' => 'rtl']),

                            Textarea::make('hero_description_ar')
                                ->label('النبذة الرئيسية')
                                ->rows(3)
                                ->extraInputAttributes(['dir' => 'rtl'])
                                ->columnSpanFull(),

                            Textarea::make('about_ar')
                                ->label('نبذة عني')
                                ->rows(6)
                                ->extraInputAttributes(['dir' => 'rtl'])
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Section::make('Profile Media')
                        ->schema([
                            FileUpload::make('photo')
                                ->label('Profile Photo')
                                ->image()
                                ->acceptedFileTypes([
                                    'image/jpeg',
                                    'image/png',
                                    'image/webp',
                                ])
                                ->disk('public')
                                ->directory('profile/photos')
                                ->visibility('public')
                                ->maxSize(3072*3)
                                ->preventFilePathTampering(),

                            FileUpload::make('cv_file')
                                ->label('CV (PDF)')
                                ->acceptedFileTypes([
                                    'application/pdf',
                                ])
                                ->disk('public')
                                ->directory('profile/cv')
                                ->visibility('public')
                                ->maxSize(5120)
                                ->preventFilePathTampering(),
                        ])
                        ->columns(2),

                    Section::make('Contact & Social Links')
                        ->schema([
                            TextInput::make('email')
                                ->email()
                                ->maxLength(255),

                            TextInput::make('phone')
                                ->tel()
                                ->maxLength(30),

                            TextInput::make('location_en')
                                ->label('Location (EN)'),

                            TextInput::make('location_ar')
                                ->label('الموقع (AR)')
                                ->extraInputAttributes(['dir' => 'rtl']),

                            TextInput::make('github_url')
                                ->label('GitHub URL')
                                ->url(),

                            TextInput::make('linkedin_url')
                                ->label('LinkedIn URL')
                                ->url(),

                            TextInput::make('x_url')
                                ->label('X URL')
                                ->url(),
                        ])
                        ->columns(2),

                    Section::make('Statistics')
                        ->schema([
                            TextInput::make('experience_years')
                                ->label('Years of Experience')
                                ->numeric()
                                ->minValue(0)
                                ->step(0.5),

                            TextInput::make('completed_projects')
                                ->label('Completed Projects')
                                ->integer()
                                ->minValue(0),

                            TextInput::make('happy_clients')
                                ->label('Happy Clients')
                                ->integer()
                                ->minValue(0),

                            Toggle::make('show_statistics')
                                ->label('Show Statistics')
                                ->default(false),
                        ])
                        ->columns(2),

                ])
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Save Profile')
                            ->submit('save'),
                    ]),
                ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $profile = $this->getRecord() ?? new Profile();

        // Always use one public profile
        $profile->id = 1;

        $profile->fill($data);
        $profile->save();

        Notification::make()
            ->title('Profile saved successfully')
            ->success()
            ->send();
    }

    public function getRecord(): ?Profile
    {
        return Profile::query()->find(1);
    }
}