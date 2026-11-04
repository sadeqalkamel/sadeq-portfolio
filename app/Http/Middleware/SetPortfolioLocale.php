<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPortfolioLocale
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        // Get language from URL if available
        $routeLocale = $request->route('locale');

        if (in_array($routeLocale, ['ar', 'en'], true)) {
            $locale = $routeLocale;

            // Remember the selected language
            $request->session()->put(
                'portfolio_locale',
                $locale
            );
        } else {
            // Preserve existing language preference
            $locale = $request->session()->get(
                'portfolio_locale',
                'en'
            );
        }

        // Accept supported languages only
        if (! in_array($locale, ['ar', 'en'], true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}