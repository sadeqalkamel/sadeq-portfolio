<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Skill;
use App\Models\Service;
use Illuminate\View\View;
use App\Models\Profile;

class PortfolioController extends Controller
{
    public function index(): View
    {
        // Featured projects
        $featuredProjects = Project::query()
            ->where('is_published', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->latest('created_at')
            ->limit(3)
            ->get();

        // Active skills
        $skillsByCategory = Skill::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('category');

        // Active services
        $services = Service::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        
        $profile = Profile::query()
            ->find(1);

        return view('portfolio', [
            'featuredProjects' => $featuredProjects,
            'skillsByCategory' => $skillsByCategory,
            'services' => $services,
            'profile' => $profile,
        ]);
    }
}