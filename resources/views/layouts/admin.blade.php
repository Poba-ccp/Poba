{{-- FILE: resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POBA Admin')</title>
    <link rel="stylesheet" href="{{ asset('css/poba.css') }}">
    @include('partials.theme-css')
    <style>
        /* This is the only addition: smoothly hides the elements when collapsed */
        .cms-menu-items,
        .settings-menu-items {
            display: none;
        }

        .cms-menu-items.show,
        .settings-menu-items.show {
            display: block;
        }

        .cms-header,
        .settings-header {
            cursor: pointer;
            user-select: none;
        }
    </style>
    @stack('styles')
</head>

<body>
    {{-- Full Width Top Header --}}
    <header class="admin-top-header">
        <div class="admin-header-brand">
            <img src="{{ asset('images/logo.png') }}" alt="POBA Logo" onerror="this.style.display='none'">
            <div class="admin-header-brand-text">
                <span class="brand-title">POBA</span>
                <span class="brand-sub">Pakistan Ocean</span>
                <span class="brand-sub">&amp; Bay Alumni</span>
            </div>
        </div>
        <button class="hamburger-admin" onclick="document.getElementById('adminSidebar').classList.toggle('open')">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>
        <div class="admin-header-spacer"></div>
        <div class="admin-header-user">
            <span class="user-role">{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}</span>
            <img src="{{ asset('images/admin-avatar.png') }}" alt="Admin"
                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1a7a7a&color=fff&size=38'">
        </div>
    </header>

    <div class="admin-wrapper">

        {{-- ── Sidebar ─────────────────────────────────────────────────────────── --}}
        <aside class="admin-sidebar" id="adminSidebar">

            <nav class="sidebar-nav">

                {{-- Dashboard — always visible --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" />
                        <rect x="14" y="3" width="7" height="7" />
                        <rect x="14" y="14" width="7" height="7" />
                        <rect x="3" y="14" width="7" height="7" />
                    </svg>
                    Dashboard
                </a>

                {{-- Alumni Users --}}
                @if (auth()->user()->hasPermission('alumni_users'))
                    <a href="{{ route('admin.alumni.index') }}"
                        class="{{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        Alumni Users
                    </a>
                @endif

                {{-- Events --}}
                @if (auth()->user()->hasPermission('events'))
                    <a href="{{ route('admin.events.index') }}"
                        class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        Events
                    </a>
                @endif

                {{-- Gallery --}}
                @if (auth()->user()->hasPermission('gallery'))
                    <a href="{{ route('admin.gallery.index') }}"
                        class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        Gallery
                    </a>
                @endif

                {{-- CMS group --}}
                @if (auth()->user()->hasAnyPermission(['homepage', 'about', 'news', 'verticals', 'contact', 'promotions', 'faqs']))
                    <div class="cms-header"
                        onclick="document.querySelector('.cms-menu-items').classList.toggle('show')"
                        style="padding:10px 20px 4px;font-size:11px;font-weight:700;color:#525456;text-transform:uppercase;letter-spacing:1px; display: flex; justify-content: space-between; align-items: center;">
                        <span>CMS</span>
                        <span style="font-size: 9px; color: #363535;">▼</span>
                    </div>

                    <div
                        class="cms-menu-items {{ request()->routeIs('admin.cms.*') && !request()->routeIs('admin.cms.footer*') && !request()->routeIs('admin.cms.seo*') ? 'show' : '' }}">
                        @if (auth()->user()->hasPermission('homepage'))
                            <a href="{{ route('admin.cms.homepage') }}"
                                class="{{ request()->routeIs('admin.cms.homepage*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                    <polyline points="9 22 9 12 15 12 15 22" />
                                </svg>
                                Homepage
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('about'))
                            <a href="{{ route('admin.cms.about') }}"
                                class="{{ request()->routeIs('admin.cms.about*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                                About Us
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('news'))
                            <a href="{{ route('admin.cms.news') }}"
                                class="{{ request()->routeIs('admin.cms.news*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="16" y1="13" x2="8" y2="13" />
                                    <line x1="16" y1="17" x2="8" y2="17" />
                                </svg>
                                News
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('verticals'))
                            <a href="{{ route('admin.cms.verticals') }}"
                                class="{{ request()->routeIs('admin.cms.verticals*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <line x1="8" y1="6" x2="21" y2="6" />
                                    <line x1="8" y1="12" x2="21" y2="12" />
                                    <line x1="8" y1="18" x2="21" y2="18" />
                                    <line x1="3" y1="6" x2="3.01" y2="6" />
                                    <line x1="3" y1="12" x2="3.01" y2="12" />
                                    <line x1="3" y1="18" x2="3.01" y2="18" />
                                </svg>
                                Verticals
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('contact'))
                            <a href="{{ route('admin.cms.contact') }}"
                                class="{{ request()->routeIs('admin.cms.contact*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13 19.79 19.79 0 0 1 1.61 4.38 2 2 0 0 1 3.59 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                </svg>
                                Contact Us
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('promotions'))
                            <a href="{{ route('admin.cms.promotions') }}"
                                class="{{ request()->routeIs('admin.cms.promotions*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                    <polyline points="17 6 23 6 23 12" />
                                </svg>
                                Promotions
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('faqs'))
                            <a href="{{ route('admin.cms.faqs') }}"
                                class="{{ request()->routeIs('admin.cms.faqs*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
                                FAQs
                            </a>
                        @endif
                    </div>
                @endif



                {{-- Settings group — Footer & SEO --}}
                @if (auth()->user()->hasAnyPermission(['footer', 'seo']))
                    <div class="settings-header"
                        onclick="document.querySelector('.settings-menu-items').classList.toggle('show')"
                        style="padding:10px 20px 4px;font-size:11px;font-weight:700;color:#525456;text-transform:uppercase;letter-spacing:1px; display: flex; justify-content: space-between; align-items: center;">
                        <span>Settings</span>
                        <span style="font-size: 9px; color: #666;">▼</span>
                    </div>

                    <div class="settings-menu-items {{ request()->routeIs('admin.cms.footer*') || request()->routeIs('admin.cms.seo*') || request()->routeIs('admin.theme.*') ? 'show' : '' }}">
                        @if (auth()->user()->hasPermission('footer'))
                            <a href="{{ route('admin.cms.footer') }}"
                                class="{{ request()->routeIs('admin.cms.footer*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <path d="M3 15h18" />
                                </svg>
                                Footer
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('seo'))
                            <a href="{{ route('admin.cms.seo') }}"
                                class="{{ request()->routeIs('admin.cms.seo*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                </svg>
                                SEO
                            </a>
                        @endif
                        @if (auth()->user()->hasPermission('seo'))
                            <a href="{{ route('admin.theme.index') }}"
                                class="{{ request()->routeIs('admin.theme.*') ? 'active' : '' }}"
                                style="padding-left:30px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3" />
                                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14" />
                                </svg>
                                Theme
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Admin Users — SuperAdmin ONLY --}}
                @if (auth()->user()->isSuperAdmin())
                    <div
                        style="padding:10px 20px 4px;font-size:11px;font-weight:700;color:#525456;text-transform:uppercase;letter-spacing:1px">
                        Administration
                    </div>
                    <a href="{{ route('admin.admin-users.index') }}"
                        class="{{ request()->routeIs('admin.admin-users.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Admin Users
                    </a>
                @endif

            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:10px;color:#888;font-size:14px;width:100%;padding:0 20px">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── Main Content ─────────────────────────────────────────────────────── --}}
        <div class="admin-main">

            <div class="admin-page-title-bar">
                <h2>@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="admin-content">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin:0;padding-left:18px">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
