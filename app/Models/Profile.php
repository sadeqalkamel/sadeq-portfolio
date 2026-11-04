<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'first_name_en',
        'last_name_en',
        'first_name_ar',
        'last_name_ar',
        'job_title_en',
        'job_title_ar',
        'hero_description_en',
        'hero_description_ar',
        'about_en',
        'about_ar',
        'photo',
        'cv_file',
        'email',
        'phone',
        'location_en',
        'location_ar',
        'github_url',
        'linkedin_url',
        'x_url',
        'experience_years',
        'completed_projects',
        'happy_clients',
        'show_statistics',
    ];

    protected function casts(): array
    {
        return [
            'experience_years' => 'decimal:1',
            'completed_projects' => 'integer',
            'happy_clients' => 'integer',
            'show_statistics' => 'boolean',
        ];
    }
}