@php
    // Profile is optional until the administrator saves it for the first time.
    $profile = $profile ?? null;
    $isArabic = app()->isLocale('ar');

    $firstName = $isArabic
        ? ($profile?->first_name_ar ?: $profile?->first_name_en ?: __('portfolio.hero.first_name'))
        : ($profile?->first_name_en ?: __('portfolio.hero.first_name'));

    $lastName = $isArabic
        ? ($profile?->last_name_ar ?: $profile?->last_name_en ?: __('portfolio.hero.last_name'))
        : ($profile?->last_name_en ?: __('portfolio.hero.last_name'));

    $fullName = trim($firstName . ' ' . $lastName);

    $jobTitle = $isArabic
        ? ($profile?->job_title_ar ?: $profile?->job_title_en ?: __('portfolio.job_title'))
        : ($profile?->job_title_en ?: __('portfolio.job_title'));

    $heroDescription = $isArabic
        ? ($profile?->hero_description_ar ?: $profile?->hero_description_en ?: __('portfolio.hero.description'))
        : ($profile?->hero_description_en ?: __('portfolio.hero.description'));

    $aboutDescription = $isArabic
        ? ($profile?->about_ar ?: $profile?->about_en ?: __('portfolio.about_section.description'))
        : ($profile?->about_en ?: __('portfolio.about_section.description'));

    $portraitUrl = filled($profile?->photo)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($profile->photo)
        : asset('assets/portfolio/portrait-reference.jpg');

    $cvUrl = filled($profile?->cv_file)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($profile->cv_file)
        : null;

    $featuredProjects = $featuredProjects ?? collect();
    $skillsByCategory = $skillsByCategory ?? collect();
    $services = $services ?? collect();

    $hasStatistics = $profile?->show_statistics
        && ($profile->experience_years !== null
            || $profile->completed_projects !== null
            || $profile->happy_clients !== null);
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isArabic ? 'rtl' : 'ltr' }}" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#080916">
{{-- SEO Metadata --}}
@php
    $seoTitle = $fullName . ' | ' . $jobTitle;

    $seoDescription = __(
        'portfolio.meta.description'
    );
@endphp

{{-- Page Title --}}
<title>{{ $seoTitle }}</title>

{{-- Search Engine Description --}}
<meta
    name="description"
    content="{{ $seoDescription }}"
>

{{-- Open Graph --}}
<meta property="og:type" content="website">

<meta
    property="og:title"
    content="{{ $seoTitle }}"
>

<meta
    property="og:description"
    content="{{ $seoDescription }}"
>

<meta
    property="og:site_name"
    content="{{ $fullName }}"
>

<meta
    property="og:locale"
    content="{{ app()->isLocale('ar') ? 'ar_AR' : 'en_US' }}"
>

{{-- Social Sharing --}}
<meta name="twitter:card" content="summary">

<meta
    name="twitter:title"
    content="{{ $seoTitle }}"
>

<meta
    name="twitter:description"
    content="{{ $seoDescription }}"
>
{{-- ==========================================
     Multilingual SEO
     Canonical & Hreflang
     ========================================== --}}

@php
    $currentLocale = app()->getLocale();

    $canonicalUrl = route('portfolio.localized', [
        'locale' => $currentLocale,
    ]);

    $arabicUrl = route('portfolio.localized', [
        'locale' => 'ar',
    ]);

    $englishUrl = route('portfolio.localized', [
        'locale' => 'en',
    ]);
@endphp

{{-- Canonical URL --}}

<link
    rel="canonical"
    href="{{ $canonicalUrl }}"
>

{{-- Language Alternatives --}}

<link
    rel="alternate"
    hreflang="ar"
    href="{{ $arabicUrl }}"
>

<link
    rel="alternate"
    hreflang="en"
    href="{{ $englishUrl }}"
>

{{-- Default Language --}}

<link
    rel="alternate"
    hreflang="x-default"
    href="{{ $englishUrl }}"
>

{{-- Open Graph URL --}}

<meta
    property="og:url"
    content="{{ $canonicalUrl }}"
>

{{-- Prevent indexing local development --}}

@if(app()->environment('local'))
    <meta
        name="robots"
        content="noindex, nofollow"
    >
@endif
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
{{-- Google Fonts - Non-blocking loading --}}

<link
    rel="preload"
    as="style"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;family=JetBrains+Mono:wght@400;500;600&amp;family=Tajawal:wght@400;500;600;700;800&amp;display=swap"
    onload="this.onload=null;this.rel='stylesheet'"
>

<noscript>
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;family=JetBrains+Mono:wght@400;500;600&amp;family=Tajawal:wght@400;500;600;700;800&amp;display=swap"
    >
</noscript>
@vite(['resources/css/app.css', 'resources/js/app.js', 
  //'resources/css/portfolio-rtl.css'
  ])
</head>
<body>
  <div class="site-shell" id="top">
    <header class="header" id="header">
      <div class="container nav-wrap">
        <a class="brand" href="#home" aria-label="{{ __('portfolio.home') }}">{{ $firstName }}<span>.</span></a>
        <nav class="desktop-nav" aria-label="{{ __('portfolio.ui.main_navigation') }}">
          <a href="#home" class="nav-link active">{{ __('portfolio.home') }}</a>
          <a href="#about" class="nav-link">{{ __('portfolio.about') }}</a>
          <a href="#skills" class="nav-link">{{ __('portfolio.skills') }}</a>
          <a href="#projects" class="nav-link">{{ __('portfolio.projects') }}</a>
          <a href="#services" class="nav-link">{{ __('portfolio.services') }}</a>
          <a href="#contact" class="nav-link">{{ __('portfolio.contact') }}</a>
        </nav>
        <div class="nav-actions">
          <a
            href="{{ route('portfolio.localized', ['locale' => $isArabic ? 'en': 'ar']) }}"
            class="icon-button"
            style="font-size: 12px; font-weight: 700; text-decoration: none;"
            aria-label="{{ $isArabic ? __('portfolio.ui.switch_to_english') : __('portfolio.ui.switch_to_arabic') }}"
            title="{{ $isArabic ? __('portfolio.ui.switch_to_english') : __('portfolio.ui.switch_to_arabic') }}"
          >{{ app()->getLocale() === 'ar' ? 'EN' : 'AR' }}</a>
          <button type="button" class="icon-button theme-button" id="theme-toggle" aria-label="{{ __('portfolio.ui.switch_color_theme') }}" title="{{ __('portfolio.ui.toggle_theme') }}">
            <svg class="moon-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.2 15.4A8.7 8.7 0 0 1 8.6 3.8a8.8 8.8 0 1 0 11.6 11.6Z"/></svg>
            <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.5 1.5m11.2 11.2 1.5 1.5m0-14.2-1.5 1.5M6.4 17.6l-1.5 1.5"/></svg>
          </button>
          <a class="btn btn-outline header-cta" href="#contact">{{ __('portfolio.lets_talk') }}</a>
          <button type="button" class="icon-button menu-toggle" id="menu-toggle" aria-label="{{ __('portfolio.ui.open_navigation') }}" data-open-label="{{ __('portfolio.ui.open_navigation') }}" data-close-label="{{ __('portfolio.ui.close_navigation') }}" aria-expanded="false" aria-controls="mobile-nav">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
          </button>
        </div>
      </div>
      <nav class="mobile-nav" id="mobile-nav" aria-label="{{ __('portfolio.ui.mobile_navigation') }}" hidden>
        <a href="#home">{{ __('portfolio.home') }}</a>
        <a href="#about">{{ __('portfolio.about') }}</a>
        <a href="#skills">{{ __('portfolio.skills') }}</a>
        <a href="#projects">{{ __('portfolio.projects') }}</a>
        <a href="#services">{{ __('portfolio.services') }}</a>
        <a href="#contact">{{ __('portfolio.contact') }}</a>
      </nav>
    </header>

    <main>
      <section class="hero" id="home" aria-labelledby="hero-title">
        <div class="hero-stars" aria-hidden="true"></div>
        <div class="container hero-layout">
          <div class="hero-copy reveal">
            <span class="hello-label"><span class="tag-code">&lt;/&gt;</span> {{ __('portfolio.hero.greeting') }}</span>
            <h1 id="hero-title">{{ $firstName }} <span class="gradient-text">{{ $lastName }}</span></h1>
            <h2 dir="auto">{{ $jobTitle }}</h2>
            <p>{{ $heroDescription }}</p>
            <div class="hero-buttons">
              <a class="btn btn-primary" href="#projects">{{ __('portfolio.view_work') }} <span class="direction-arrow" aria-hidden="true">→</span></a>
              <a class="btn btn-ghost" href="#contact">{{ __('portfolio.contact_me') }} <span aria-hidden="true">↗</span></a>
            </div>
            <div class="socials" aria-label="{{ __('portfolio.ui.social_networks') }}">
              @if(filled($profile?->github_url))
                <a href="{{ $profile->github_url }}" aria-label="GitHub" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .9a11.1 11.1 0 0 0-3.51 21.64c.56.1.76-.24.76-.53v-2.06c-3.1.67-3.76-1.32-3.76-1.32-.51-1.28-1.24-1.62-1.24-1.62-1.01-.69.08-.67.08-.67 1.12.08 1.71 1.15 1.71 1.15 1 .1.83 2.24 3.24 1.56.1-.72.4-1.22.73-1.5-2.47-.28-5.06-1.24-5.06-5.5 0-1.22.44-2.22 1.15-3-.12-.28-.5-1.42.1-2.95 0 0 .94-.3 3.05 1.14a10.57 10.57 0 0 1 5.56 0c2.11-1.44 3.05-1.14 3.05-1.14.6 1.53.22 2.67.1 2.95.72.78 1.14 1.78 1.14 3 0 4.27-2.6 5.22-5.08 5.5.42.37.79 1.09.79 2.2v3.27c0 .29.2.64.77.53A11.1 11.1 0 0 0 12 .9Z"/></svg></a>
              @endif
              @if(filled($profile?->linkedin_url))
                <a href="{{ $profile->linkedin_url }}" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.45 2H3.55C2.69 2 2 2.69 2 3.55v16.9c0 .86.69 1.55 1.55 1.55h16.9c.86 0 1.55-.69 1.55-1.55V3.55c0-.86-.69-1.55-1.55-1.55ZM7.93 18.71H4.98V9.2h2.95v9.51ZM6.45 7.9a1.71 1.71 0 1 1 .02-3.42 1.71 1.71 0 0 1-.02 3.42Zm12.26 10.81h-2.94v-4.62c0-1.1-.02-2.51-1.53-2.51-1.53 0-1.77 1.19-1.77 2.43v4.7H9.53V9.2h2.82v1.3h.04c.39-.74 1.35-1.53 2.78-1.53 2.98 0 3.54 1.96 3.54 4.51v5.23Z"/></svg></a>
              @endif
              @if(filled($profile?->x_url))
                <a href="{{ $profile->x_url }}" aria-label="X" target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.2 4H23l-6.1 6.97L24 20h-5.56l-4.36-5.7L9.1 20H6.3l6.53-7.47L6 4h5.7l3.94 5.22L20.2 4Z" transform="translate(-3 0) scale(1.05)"/></svg></a>
              @endif
              @if(filled($profile?->email))
                <a href="mailto:{{ $profile->email }}" aria-label="{{ __('portfolio.ui.contact_by_email') }}" ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></a>
              @endif
            </div>
          </div>

          <div class="hero-portrait" aria-label="{{ __('portfolio.ui.portrait_area') }}">
            <div class="portrait-halo"></div>
            <img src="{{ $portraitUrl }}" alt="{{ $fullName }}" class="portrait-img" loading="eager" fetchpriority="high">
          </div>

          <div class="code-window reveal" aria-label="{{ __('portfolio.ui.developer_code') }}" dir="ltr">
            <div class="window-bar"><div class="window-dots"><i></i><i></i><i></i></div><span>{{ __('portfolio.hero.code_file') }}</span></div>
            <div class="code-content">
<pre><code><span class="syntax-pink">const</span> <span class="syntax-yellow">developer</span> = {
  <span class="syntax-lavender">name</span>: <span class="syntax-green">"{{ $fullName }}"</span>,
  <span class="syntax-lavender">role</span>: <span class="syntax-green">"{{ $jobTitle }}"</span>,
  <span class="syntax-lavender">focus</span>: <span class="syntax-green">"{{ __('portfolio.hero.code_focus') }}"</span>,
  <span class="syntax-lavender">passion</span>: <span class="syntax-green">"{{ __('portfolio.hero.code_passion') }}"</span>,
  <span class="syntax-pink">skills</span>: [<span class="syntax-green">"Laravel"</span>, <span class="syntax-green">"PHP"</span>,
           <span class="syntax-green">"MySQL"</span>, <span class="syntax-green">"JavaScript"</span>]
};</code></pre>
              <div class="code-foot"><span>// {{ __('portfolio.hero.code_comment') }}</span><strong>&gt;_</strong></div>
            </div>
          </div>
        </div>
        <a class="scroll-cue" href="#about" aria-label="{{ __('portfolio.ui.scroll_down') }}"><span class="mouse" aria-hidden="true"></span>{{ __('portfolio.hero.scroll_down') }}</a>
      </section>

      <section class="section about-section" id="about" aria-labelledby="about-heading">
        <div class="container about-grid" @unless($hasStatistics) style="grid-template-columns: minmax(0, 1fr)" @endunless>
          <div class="section-intro reveal">
            <p class="eyebrow">{{ __('portfolio.about_section.eyebrow') }}</p>
            <h2 id="about-heading">{{ __('portfolio.about_section.heading_first') }}<br><span class="gradient-text">{{ __('portfolio.about_section.heading_highlight') }}</span></h2>
            <p>{{ $aboutDescription }}</p>
            @if($cvUrl)
              <a class="btn btn-outline btn-sm" href="{{ $cvUrl }}" target="_blank" rel="noopener noreferrer" download>
                {{ __('portfolio.about_section.request_cv') }} <span aria-hidden="true">↗</span>
              </a>
            @endif
          </div>
          @if($hasStatistics)
            <div class="stats-grid">
              @if($profile->experience_years !== null)
                <div class="stat-card reveal">
                  <span class="stat-icon">▦</span>
                  <strong>{{ $profile->experience_years }}</strong>
                  <span>{{ __('portfolio.about_section.experience') }}</span>
                </div>
              @endif
              @if($profile->completed_projects !== null)
                <div class="stat-card reveal">
                  <span class="stat-icon">&lt;/&gt;</span>
                  <strong>{{ $profile->completed_projects }}</strong>
                  <span>{{ __('portfolio.about_section.completed_projects') }}</span>
                </div>
              @endif
              @if($profile->happy_clients !== null)
                <div class="stat-card reveal">
                  <span class="stat-icon">☺</span>
                  <strong>{{ $profile->happy_clients }}</strong>
                  <span>{{ __('portfolio.about_section.happy_clients') }}</span>
                </div>
              @endif
            </div>
          @endif
        </div>
      </section>

      <section class="section skills-section" id="skills" aria-labelledby="skills-heading">
        <div class="container">
          <div class="section-heading reveal"><div><p class="eyebrow">{{ __('portfolio.skills_section.eyebrow') }}</p><h2 id="skills-heading">{{ __('portfolio.skills_section.heading_first') }} <span class="gradient-text">{{ __('portfolio.skills_section.heading_highlight') }}</span></h2></div><a href="#services" class="btn btn-outline btn-sm">{{ __('portfolio.skills_section.explore_services') }} <span class="direction-arrow" aria-hidden="true">→</span></a></div>
          {{-- Skills are loaded from MySQL and grouped by category. --}}
          <div class="skill-groups">
            @foreach ([
                'backend' => ['label' => __('portfolio.skills_section.backend'), 'symbol' => '▤'],
                'frontend' => ['label' => __('portfolio.skills_section.frontend'), 'symbol' => '▣'],
                'tools' => ['label' => __('portfolio.skills_section.tools'), 'symbol' => '⚒'],
            ] as $category => $group)
              @php $categorySkills = $skillsByCategory->get($category, collect()); @endphp
              @if($categorySkills->isNotEmpty())
                <div class="skill-group reveal">
                  <div class="group-name">{{ $group['symbol'] }} &nbsp; {{ $group['label'] }}</div>
                  <div class="skill-grid">
                    @foreach($categorySkills as $skill)
                      @php
                          $skillName = $isArabic && filled($skill->name_ar)
                              ? $skill->name_ar
                              : $skill->name_en;
                      @endphp
                      <div class="skill-pill">
                        @if(filled($skill->icon))
                          <img
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($skill->icon) }}"
                            alt=""
                            width="30" height="30" loading="lazy"
                            style="width:30px;height:30px;object-fit:contain;flex-shrink:0"
                          >
                        @else
                          <span class="skill-symbol" aria-hidden="true">{{ mb_strtoupper(mb_substr($skill->name_en, 0, 2)) }}</span>
                        @endif
                        <span dir="auto" style="min-width:0;overflow-wrap:anywhere">{{ $skillName }}</span>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            @endforeach
          </div>
          @if($skillsByCategory->flatten(1)->isEmpty())
            <p style="color:var(--muted);padding:16px 0">
              {{ $isArabic ? 'ستظهر المهارات هنا قريبًا.' : 'Skills will appear here soon.' }}
            </p>
          @endif
        </div>
      </section>

      <section class="section projects-section" id="projects" aria-labelledby="projects-heading">
        <div class="container">
          <div class="section-heading reveal"><div><p class="eyebrow">{{ __('portfolio.projects_section.eyebrow') }}</p><h2 id="projects-heading">{{ __('portfolio.projects_section.heading') }}</h2></div><a class="btn btn-outline btn-sm" href="#contact">{{ __('portfolio.projects_section.discuss_project') }} <span class="direction-arrow" aria-hidden="true">→</span></a></div>
          <div class="projects-grid projects-grid--{{ max(1, min($featuredProjects->count(), 3)) }}">
            @forelse($featuredProjects as $project)
              @php
                  $projectTitle = $isArabic && filled($project->title_ar)
                      ? $project->title_ar
                      : $project->title;
                  $projectDescription = $isArabic
                      ? ($project->short_description_ar ?: $project->description_ar ?: $project->short_description ?: $project->description)
                      : ($project->short_description ?: $project->description);
              @endphp
              <article class="project-card {{ $loop->first ? 'project-featured' : '' }} reveal">
                <div class="project-shot">
                  @if($project->cover_image)
                    <img
                      src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($project->cover_image) }}"
                      alt="{{ $projectTitle }}"
                      loading="lazy"
                    >
                  @else
                    <div class="flex h-full items-center justify-center text-slate-400">
                      {{ __('portfolio.projects_section.no_cover') }}
                    </div>
                  @endif
                </div>

                <div class="project-body">
                  <h3 dir="auto">{{ $projectTitle }}</h3>
                  <p dir="auto">{{ \Illuminate\Support\Str::limit($projectDescription ?? '', 140) }}</p>

                  <div class="tags">
                    @foreach($project->technologies ?? [] as $technology)
                      <span>{{ $technology }}</span>
                    @endforeach
                  </div>

                  @if($project->github_url || $project->live_url)
                    <div class="project-links">
                      @if($project->live_url)
                        <a href="{{ $project->live_url }}" class="btn btn-outline btn-sm" target="_blank" rel="noopener noreferrer">
                          {{ __('portfolio.projects_section.live_demo') }} ↗
                        </a>
                      @endif
                      @if($project->github_url)
                        <a href="{{ $project->github_url }}" class="btn btn-outline btn-sm" target="_blank" rel="noopener noreferrer">
                          {{ __('portfolio.projects_section.github') }} ↗
                        </a>
                      @endif
                    </div>
                  @endif
                </div>
              </article>
            @empty
              <article class="project-card project-featured reveal">
                <div class="project-body">
                  <h3>{{ __('portfolio.projects_section.coming_soon') }}</h3>
                  <p>{{ __('portfolio.projects_section.coming_soon_description') }}</p>
                </div>
              </article>
            @endforelse
          </div>
        </div>
      </section>

      <section class="section process-section" id="process" aria-labelledby="process-heading">
        <div class="container"><div class="section-heading reveal"><div><p class="eyebrow">{{ __('portfolio.process_section.eyebrow') }}</p><h2 id="process-heading">{{ __('portfolio.process_section.heading') }}</h2></div></div>
          <div class="process-grid"><div class="process-card reveal"><b class="step purple">01</b><div><h3>{{ __('portfolio.process_section.idea') }}</h3><p>{{ __('portfolio.process_section.idea_description') }}</p></div><span class="process-art">♧</span></div><div class="process-card reveal"><b class="step blue">02</b><div><h3>{{ __('portfolio.process_section.design') }}</h3><p>{{ __('portfolio.process_section.design_description') }}</p></div><span class="process-art">✎</span></div><div class="process-card reveal"><b class="step cyan">03</b><div><h3>{{ __('portfolio.process_section.develop') }}</h3><p>{{ __('portfolio.process_section.develop_description') }}</p></div><span class="process-art">&lt;/&gt;</span></div><div class="process-card reveal"><b class="step green">04</b><div><h3>{{ __('portfolio.process_section.deploy') }}</h3><p>{{ __('portfolio.process_section.deploy_description') }}</p></div><span class="process-art">♧</span></div></div>
        </div>
      </section>

      <section class="section services-section" id="services" aria-labelledby="services-heading">
        <div class="container"><div class="section-heading reveal"><div><p class="eyebrow">{{ __('portfolio.services_section.eyebrow') }}</p><h2 id="services-heading">{{ __('portfolio.services_section.heading_first') }} <span class="gradient-text">{{ __('portfolio.services_section.heading_highlight') }}</span></h2></div></div>
          {{-- Only active services are passed from PortfolioController. --}}
          <div class="services-grid">
            @forelse($services as $service)
              @php
                  $serviceTitle = $isArabic && filled($service->title_ar)
                      ? $service->title_ar
                      : $service->title_en;
                  $serviceDescription = $isArabic && filled($service->description_ar)
                      ? $service->description_ar
                      : $service->description_en;
              @endphp
              <article class="service-card reveal">
                <span class="service-icon" aria-hidden="true">
                  @if(filled($service->icon))
                    <img
                      src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($service->icon) }}"
                      alt="" width="34" height="34" loading="lazy"
                      style="width:34px;height:34px;object-fit:contain;display:block"
                    >
                  @else
                    ◇
                  @endif
                </span>
                <h3 dir="auto">{{ $serviceTitle }}</h3>
                <p dir="auto">{{ $serviceDescription }}</p>
              </article>
            @empty
              <p style="grid-column:1/-1;color:var(--muted);padding:20px 0">
                {{ $isArabic ? 'ستظهر خدماتي هنا قريبًا.' : 'My services will appear here soon.' }}
              </p>
            @endforelse
          </div>
        </div>
      </section>

      <section class="section contact-section" id="contact" aria-labelledby="contact-heading">
        <div class="container contact-panel reveal">
          <div>
            <p class="eyebrow">{{ __('portfolio.contact_section.eyebrow') }}</p>
            <h2 id="contact-heading">{{ __('portfolio.contact_section.heading_first') }} <span class="gradient-text">{{ __('portfolio.contact_section.heading_highlight') }}</span></h2>
            <p>{{ __('portfolio.contact_section.description') }}</p>
          </div>
          @if(filled($profile?->email))
            <a class="btn btn-primary" href="mailto:{{ $profile->email }}?subject=Project%20Inquiry">
              {{ __('portfolio.contact_section.start_conversation') }} ↗
            </a>
          @else
            <a class="btn btn-primary" href="#content-form" data-placeholder-email="true" data-placeholder-message="{{ __('portfolio.ui.missing_email') }}">
              {{ __('portfolio.contact_section.start_conversation') }} ↗
            </a>
          @endif
        </div>
      </section>
      {{-- =========================================
     CONTACT MESSAGE FORM
     Sadeq Personal Portfolio
     ========================================= --}}

<section class="sadeq-contact section" id="contact-form">

    <div class="container">

        <div class="sadeq-contact__header">

            <span class="sadeq-contact__eyebrow">
                {{ app()->isLocale('ar') ? 'تواصل معي' : 'GET IN TOUCH' }}
            </span>

            <h2 class="sadeq-contact__title">
                {{ app()->isLocale('ar') ? 'أرسل لي' : 'Send Me a' }}

                <span class="sadeq-contact__gradient">
                    {{ app()->isLocale('ar') ? 'رسالة' : 'Message' }}
                </span>
            </h2>

            <p class="sadeq-contact__description">
                {{ __('contact.form_description') }}
            </p>

        </div>

        <div class="sadeq-contact__card">

            {{-- Success Message --}}

            @if(session('contact_success'))

                <div class="sadeq-contact__alert sadeq-contact__alert--success"
                     role="status">

                    <span aria-hidden="true">✓</span>

                    {{ __('contact.success') }}

                </div>

            @endif

            {{-- Validation Errors --}}

            @if($errors->any())

                <div class="sadeq-contact__alert sadeq-contact__alert--error"
                     role="alert">

                    <span aria-hidden="true">!</span>

                    {{ __('contact.error') }}

                </div>

            @endif

            {{-- Contact Form --}}

            <form method="POST"
                  action="{{ route('contact.store') }}"
                  class="sadeq-contact__form">

                @csrf

                {{-- Hidden Spam Protection --}}

                <div class="sadeq-contact__honeypot"
                     aria-hidden="true">

                    <label for="sadeq-website">
                        Website
                    </label>

                    <input type="text"
                           id="sadeq-website"
                           name="website"
                           tabindex="-1"
                           autocomplete="off">

                </div>

                <div class="sadeq-contact__grid">

                    {{-- Visitor Name --}}

                    <div class="sadeq-contact__field">

                        <label for="sadeq-name">
                            {{ __('contact.name') }}
                            <span>*</span>
                        </label>

                        <input type="text"
                               id="sadeq-name"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               minlength="2"
                               maxlength="120"
                               autocomplete="name"
                               dir="auto"
                               aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">

                        @error('name')
                            <small class="sadeq-contact__error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Email Address --}}

                    <div class="sadeq-contact__field">

                        <label for="sadeq-email">
                            {{ __('contact.email') }}
                            <span>*</span>
                        </label>

                        <input type="email"
                               id="sadeq-email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               maxlength="255"
                               autocomplete="email"
                               dir="ltr"
                               aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">

                        @error('email')
                            <small class="sadeq-contact__error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Message Subject --}}

                    <div class="sadeq-contact__field sadeq-contact__field--full">

                        <label for="sadeq-subject">
                            {{ __('contact.subject') }}
                            <span>*</span>
                        </label>

                        <input type="text"
                               id="sadeq-subject"
                               name="subject"
                               value="{{ old('subject') }}"
                               required
                               minlength="3"
                               maxlength="180"
                               dir="auto"
                               aria-invalid="{{ $errors->has('subject') ? 'true' : 'false' }}">

                        @error('subject')
                            <small class="sadeq-contact__error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                    {{-- Message Body --}}

                    <div class="sadeq-contact__field sadeq-contact__field--full">

                        <label for="sadeq-message">
                            {{ __('contact.message') }}
                            <span>*</span>
                        </label>

                        <textarea id="sadeq-message"
                                  name="message"
                                  rows="6"
                                  minlength="10"
                                  maxlength="5000"
                                  required
                                  dir="auto"
                                  aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}">{{ old('message') }}</textarea>

                        @error('message')
                            <small class="sadeq-contact__error">
                                {{ $message }}
                            </small>
                        @enderror

                    </div>

                </div>

                {{-- Bottom Actions --}}

                <div class="sadeq-contact__footer">

                    <p class="sadeq-contact__privacy">
                        <span aria-hidden="true">♢</span>

                        {{ __('contact.privacy') }}
                    </p>

                    <button type="submit"
                            class="sadeq-contact__submit">

                        <span>
                            {{ __('contact.send') }}
                        </span>

                        <svg viewBox="0 0 24 24"
                             width="19"
                             height="19"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">

                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>

                        </svg>

                    </button>

                </div>

            </form>

        </div>

    </div>

</section>
    </main>
    <footer class="footer"><div class="container footer-wrap"><a class="brand" href="#home">{{ $firstName }}<span>.</span></a><p>© <span id="year">2026</span> {{ __('portfolio.footer.rights', ['name' => $fullName]) }}</p><a href="#top">{{ __('portfolio.footer.back_to_top') }} ↑</a></div></footer>
  </div>
  <dialog id="project-modal" class="project-modal" aria-labelledby="modal-title"><button class="modal-close" type="button" aria-label="{{ __('portfolio.ui.close_dialog') }}">×</button><p class="eyebrow">{{ __('portfolio.ui.project_preview') }}</p><h2 id="modal-title">{{ __('portfolio.ui.project') }}</h2><p id="modal-description"></p><p class="modal-note">{{ __('portfolio.ui.project_preview_note') }}</p><button type="button" class="btn btn-primary modal-dismiss">{{ __('portfolio.ui.got_it') }}</button></dialog>
  <div class="toast" role="status" aria-live="polite" id="toast"></div>
</body>
</html>
