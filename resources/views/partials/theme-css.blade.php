@php
    $theme = \App\Models\SiteTheme::getAll();
    $headingFont    = $theme['heading_font']    ?? 'Poppins';
    $bodyFont       = $theme['body_font']       ?? 'Roboto';
    $navFont        = $theme['nav_font']        ?? 'Poppins';
    $headingSize    = $theme['heading_size']    ?? '2rem';
    $subheadingSize = $theme['subheading_size'] ?? '1.5rem';
    $bodySize       = $theme['body_size']       ?? '14px';
    $navSize        = $theme['nav_size']        ?? '14px';
    $headingWeight  = $theme['heading_weight']  ?? '700';
    $primaryColor   = $theme['primary_color']   ?? '#1a7a7a';
    $secondaryColor = $theme['secondary_color'] ?? '#e87722';
    $textColor      = $theme['text_color']      ?? '#2c3e50';
    $bgColor        = $theme['bg_color']        ?? '#ffffff';
    $navBgColor     = $theme['nav_bg_color']    ?? '#ffffff';
    $footerBgColor  = $theme['footer_bg_color'] ?? '#1a7a7a';
    $cardBgColor    = $theme['card_bg_color']   ?? '#ffffff';
    $borderRadius   = $theme['border_radius']   ?? '12px';
    $buttonRadius   = $theme['button_radius']   ?? '30px';

    // Build Google Fonts URL — load all 3 fonts in one request
    $fonts = array_unique([$headingFont, $bodyFont, $navFont]);
    $fontQuery = implode('&family=', array_map(fn($f) => str_replace(' ', '+', $f) . ':wght@400;500;600;700;800', $fonts));
@endphp

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family={{ $fontQuery }}&display=swap" rel="stylesheet">

{{-- Dynamic CSS Variables — these OVERRIDE the static values in poba.css --}}
<style>
    :root {
        /* Colors */
        --teal:            {{ $primaryColor }};
        --teal-dark:       {{ $primaryColor }}cc;
        --teal-light:      {{ $primaryColor }}18;
        --orange:          {{ $secondaryColor }};
        --orange-dark:     {{ $secondaryColor }}cc;
        --text-dark:       {{ $textColor }};
        --white:           {{ $bgColor }};
        --radius:          {{ $borderRadius }};

        /* Custom theme vars */
        --theme-primary:       {{ $primaryColor }};
        --theme-secondary:     {{ $secondaryColor }};
        --theme-text:          {{ $textColor }};
        --theme-bg:            {{ $bgColor }};
        --theme-nav-bg:        {{ $navBgColor }};
        --theme-footer-bg:     {{ $footerBgColor }};
        --theme-card-bg:       {{ $cardBgColor }};
        --theme-radius:        {{ $borderRadius }};
        --theme-btn-radius:    {{ $buttonRadius }};

        /* Typography */
        --theme-heading-font:  '{{ $headingFont }}', sans-serif;
        --theme-body-font:     '{{ $bodyFont }}', sans-serif;
        --theme-nav-font:      '{{ $navFont }}', sans-serif;
        --theme-heading-size:  {{ $headingSize }};
        --theme-sub-size:      {{ $subheadingSize }};
        --theme-body-size:     {{ $bodySize }};
        --theme-nav-size:      {{ $navSize }};
        --theme-heading-weight:{{ $headingWeight }};
    }

    /* Apply body font globally */
    body {
        font-family: var(--theme-body-font) !important;
        font-size:   var(--theme-body-size) !important;
        color:       var(--theme-text) !important;
        background:  var(--theme-bg) !important;
    }

    /* Headings */
    h1, h2, h3, h4, h5, h6,
    .section-title, .section-title-left, .section-title-center {
        font-family: var(--theme-heading-font) !important;
        font-weight: var(--theme-heading-weight) !important;
        color:       var(--theme-primary) !important;
    }
    h1 { font-size: var(--theme-heading-size) !important; }
    h2 { font-size: var(--theme-sub-size) !important; }

    /* Navbar */
    .navbar {
        background:  var(--theme-nav-bg) !important;
        font-family: var(--theme-nav-font) !important;
    }
    .navbar-nav a {
        font-family: var(--theme-nav-font) !important;
        font-size:   var(--theme-nav-size) !important;
    }

    /* Footer */
    footer {
        background: var(--theme-footer-bg) !important;
    }

    /* Cards */
    .card, .alumni-card, .event-card, .promo-card,
    .admin-form-page, .admin-table-wrap {
        background:    var(--theme-card-bg) !important;
        border-radius: var(--theme-radius) !important;
    }

    /* Buttons */
    .btn-teal, .btn-approve, .btn-save,
    .btn-teal-nav, .btn-teal-capsule, .btn-teal-news-view,
    .btn-teal-alumni-details {
        background:    var(--theme-primary) !important;
        border-radius: var(--theme-btn-radius) !important;
        font-family:   var(--theme-body-font) !important;
    }
    .btn-orange, .tab-btn.active, .sidebar-nav a.active {
        background: var(--theme-secondary) !important;
    }
    .btn-outline-teal {
        color:         var(--theme-primary) !important;
        border-color:  var(--theme-primary) !important;
        border-radius: var(--theme-btn-radius) !important;
    }

    /* Primary color usages */
    .top-banner,
    .admin-top-header,
    .navbar-brand-centered,
    .page-header {
        background: var(--theme-primary) !important;
    }
    a { color: var(--theme-primary) }
    a:hover { color: var(--theme-secondary) }

    /* Section title underline */
    .section-title::after,
    .section-title-left::after,
    .section-title-center::after {
        background: var(--theme-secondary) !important;
    }
</style>
