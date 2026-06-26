
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POBA - Palandarians Old Boys Association')</title>
    <meta name="description" content="@yield('meta_description', 'Official POBA Alumni Network')">
    <link rel="stylesheet" href="{{ asset('css/poba.css') }}">
    @include('partials.theme-css')
    @stack('styles')
</head>
<body>

{{-- Top Banner --}}
<div class="top-banner">PALANDARIANS' OLD BOYS' ASSOCIATION (POBA)</div>

{{-- Navbar --}}
<nav class="navbar">
    <div class="navbar-inner">
        {{-- Left Side Links --}}
        <ul class="navbar-nav nav-left">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
            <!-- <li><a href="#">Sponsor</a></li> -->
             <li><a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'active' : '' }}">Updates</a></li>
            <li><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">Events</a></li>
        </ul>

        {{-- Centered Logo Shield --}}
        <div class="navbar-logo-container">
            <a href="{{ route('home') }}" class="navbar-brand-centered">
                <img src="{{ asset('images/logo.png') }}" alt="POBA Logo" onerror="this.style.display='none'">
            </a>
        </div>

        {{-- Right Side Links --}}
        <ul class="navbar-nav nav-right">
            <li><a href="{{ route('star.alumni') }}" class="{{ request()->routeIs('star.*') ? 'active' : '' }}">Star Alumni</a></li>
            <li class="dropdown">
                <a href="#" class="{{ request()->routeIs('alumni.*') || request()->routeIs('gallery.*') ? 'active' : '' }}">Alumni ▾</a>
                <div class="dropdown-menu">
                    <a href="{{ route('member.index') }}">Become Member</a>
                    <a href="{{ route('alumni.index') }}">Alumni Directory</a>
                    <a href="#">Achievements</a>
                    <a href="#">Networking</a>
                    <a href="#">Career Services</a>
                    <a href="{{ route('gallery.index') }}">Gallery</a>
                </div>
            </li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
            <!-- <li>
                @auth('alumni')
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-teal-nav" style="border:none;cursor:pointer">Logout</button>
                    </form>
                @else
                    <a href="{{ route('member.index') }}" class="btn-teal-nav">Become a Member</a>
                @endauth
            </li> -->
            <li>
    @auth('alumni')

        <a href="{{ route('profile.edit') }}" class="btn-teal-nav">
            My Profile
        </a>

        <form method="POST" action="{{ route('logout') }}" style="display:inline;margin-left:8px;">
            @csrf
            <button type="submit" class="btn-teal-nav" style="border:none;cursor:pointer">
                Logout
            </button>
        </form>

    @else
        <a href="{{ route('member.index') }}" class="btn-teal-nav">
            Become a Member
        </a>
         <a href="{{ route('login') }}" class="btn-teal-nav">
            Login
        </a>
    @endauth
</li>
        </ul>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="container" style="margin-top:16px">
        <div class="alert alert-success">{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="container" style="margin-top:16px">
        <div class="alert alert-danger">{{ session('error') }}</div>
    </div>
@endif

{{-- Main Content --}}
@yield('content')

{{-- Footer --}}
<!-- <footer>
    <div class="container">
        <div class="grid-4" style="gap:30px">
            <div>
                <div class="footer-logo">
                    <div class="footer-logo-circle">
                        <img src="{{ asset('images/logo.png') }}" alt="POBA Logo" onerror="this.style.display='none'">
                    </div>
                    <div>
                        <div class="footer-logo-text">POBA</div>
                        <div class="footer-logo-sub">Palandarians Old Boys Association</div>
                    </div>
                </div>
            </div>
            <div>
                <h5>Quick Links</h5>
                <a href="{{ route('about') }}">About Us</a>
                <a href="{{ route('news.index') }}">News</a>
                <a href="{{ route('events.index') }}">Events</a>
                <a href="#">Donate Now</a>
            </div>
            <div>
                <h5>Alumni</h5>
                <a href="{{ route('alumni.index') }}">Alumni Directory</a>
                <a href="#">Achievements</a>
                <a href="#">Networking</a>
                <a href="{{ route('star.alumni') }}">Star Alumni</a>
                <a href="#">Career Services</a>
            </div>
            <div>
                <div class="contact-info" style="flex-direction:column;gap:10px">
                    <span>📞 +92 21 123 4567</span>
                    <span>✉️ info@poba.edu.pk</span>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 POBA. All rights reserved.</p>
            <div class="social-links">
                <a href="#" title="Twitter">𝕏</a>
                <a href="#" title="LinkedIn">in</a>
                <a href="#" title="Facebook">f</a>
                <a href="#" title="Instagram">📷</a>
                <a href="#" title="TikTok">♪</a>
            </div>
        </div>
    </div>
</footer> -->
<footer>
    <div class="footer-container">

        {{-- Main Row: Logo on left, links pushed right --}}
        <div class="footer-inner">
            <div class="footer-brand">
                <img src="{{ asset('images/footerLogo.png') }}"
                     alt="POBA Logo"
                     class="footer-logo-img"
                     onerror="this.style.display='none'">
            </div>

            <div class="footer-links-wrapper">
                <div class="footer-col">
                    <h5>Quick Links</h5>
                    <a href="{{ route('about') }}">About Us</a>
                    <a href="{{ route('news.index') }}">News</a>
                    <a href="{{ route('events.index') }}">Events</a>
                    <a href="{{ route('star.alumni') }}">Star Alumni</a>
                </div>

                <div class="footer-col">
                    <h5>Alumni</h5>
                    <a href="{{ route('alumni.index') }}">Alumni Directory</a>
                    <a href="#">Achievements</a>
                    <a href="#">Networking</a>
                    <a href="#">Career Services</a>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="footer-bottom">
            <div class="footer-socials">
                <a href="#" title="Twitter / X" aria-label="Twitter">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="#" title="LinkedIn" aria-label="LinkedIn">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                </a>
                <a href="#" title="Facebook" aria-label="Facebook">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <a href="#" title="Instagram" aria-label="Instagram">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
                <a href="#" title="TikTok" aria-label="TikTok">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 1 0 6.34 6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.02-.06z"/></svg>
                </a>
            </div>

            <div class="footer-contact">
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.82 19a19.5 19.5 0 0 1-5.9-5.9A19.79 19.79 0 0 1 3 5.18 2 2 0 0 1 5.18 3h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L9.17 10.9a16 16 0 0 0 5.9 5.9l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    +92 21 123 4567
                </span>
                <span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    info@poba.edu.pk
                </span>
            </div>
        </div>

    </div>
</footer>
@stack('scripts')
</body>
</html>
