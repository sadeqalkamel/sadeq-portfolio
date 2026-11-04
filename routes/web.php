<?php

use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetPortfolioLocale;
use App\Http\Controllers\ContactMessageController;

Route::get('/', function (
    \Illuminate\Http\Request $request
) {
    $locale = $request->session()->get(
        'portfolio_locale',
        'en'
    );

    if (! in_array($locale, ['ar', 'en'], true)) {
        $locale = 'en';
    }

    return redirect()->route(
        'portfolio.localized',
        ['locale' => $locale]
    );
});

Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.index')->middleware(SetPortfolioLocale::class);

Route::get('/language/{locale}', function (string $locale) {

    // Accept English and Arabic only
    abort_unless(in_array($locale, ['en', 'ar'], true), 404);

    // Save selected language in session
    session()->put('portfolio_locale', $locale);

    // Return to homepage
    return redirect('/');

})->name('portfolio.language');

Route::post('/contact', [
    ContactMessageController::class,
    'store'
])
    ->middleware([
        SetPortfolioLocale::class,
        'throttle:5,1',
    ])
    ->name('contact.store');


Route::get('/{locale}',[PortfolioController::class, 'index'])
->whereIn('locale', ['en', 'ar'])
->middleware(SetPortfolioLocale::class)
->name('portfolio.localized');


/*
|--------------------------------------------------------------------------
| SEO Sitemap
|--------------------------------------------------------------------------
*/

Route::get('/sitemap.xml', function () {

    $urls = [
        route('portfolio.localized', ['locale' => 'ar']),
        route('portfolio.localized', ['locale' => 'en']),
    ];

    return response()
        ->view('seo.sitemap', [
            'urls' => $urls,
        ])
        ->header(
            'Content-Type',
            'application/xml; charset=UTF-8'
        );

})->name('sitemap');



/*
|--------------------------------------------------------------------------
| SEO Robots.txt
|--------------------------------------------------------------------------
*/

Route::get('/robots.txt', function () {

    $lines = [
        'User-agent: *',
        'Allow: /',
    ];

    if (app()->environment('production')) {

        $lines[] = 'Disallow: /admin';

        $lines[] = 'Sitemap: ' . route('sitemap');
    }

    return response(
        implode("\n", $lines) . "\n",
        200,
        [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]
    );

})->name('robots');
