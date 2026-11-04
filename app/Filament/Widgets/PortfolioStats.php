<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Skill;
use App\Models\Service;
use App\Models\ContactMessage;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PortfolioStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [

            Stat::make(
                'Published Projects',
                Project::where('is_published', true)->count()
            )
                ->description('Projects published on your portfolio')
                ->descriptionIcon('heroicon-m-folder')
                ->color('primary'),

            Stat::make(
                'Active Skills',
                Skill::where('is_active', true)->count()
            )
                ->description('Skills visible on your website')
                ->descriptionIcon('heroicon-m-code-bracket')
                ->color('success'),

            Stat::make(
                'Active Services',
                Service::where('is_active', true)->count()
            )
                ->description('Services currently enabled')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info'),

            Stat::make(
                'Unread Messages',
                ContactMessage::whereNull('read_at')->count()
            )
                ->description('Messages waiting to be read')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),

        ];
    }
}