{{-- FILE: resources/views/customer/home.blade.php --}}
@extends('layouts.app')
@section('title', 'POBA - Welcome to Alumni Network')
@section('content')

    @php
        $mockNews = [
            [
                'id' => '#',
                'title' => 'Team SGY representing Match Racing World Champions',
                'type' => 'NEWS | EXCLUSIVE | ALUMNI',
                'description' =>
                    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type.",
                'image_url' =>
                    'https://images.unsplash.com/photo-1540962351504-03099e0a754b?auto=format&fit=crop&w=400&h=250&q=80',
            ],
            [
                'id' => '#',
                'title' => 'Team SGY representing Match Racing World Champions',
                'type' => 'NEWS | EXCLUSIVE | ALUMNI',
                'description' =>
                    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type.",
                'image_url' =>
                    'https://images.unsplash.com/photo-1540962351504-03099e0a754b?auto=format&fit=crop&w=400&h=250&q=80',
            ],
            [
                'id' => '#',
                'title' => 'Team SGY representing Match Racing World Champions',
                'type' => 'NEWS | EXCLUSIVE | ALUMNI',
                'description' =>
                    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type.",
                'image_url' =>
                    'https://images.unsplash.com/photo-1540962351504-03099e0a754b?auto=format&fit=crop&w=400&h=250&q=80',
            ],
            [
                'id' => '#',
                'title' => 'Team SGY representing Match Racing World Champions',
                'type' => 'NEWS | EXCLUSIVE | ALUMNI',
                'description' =>
                    "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type.",
                'image_url' =>
                    'https://images.unsplash.com/photo-1540962351504-03099e0a754b?auto=format&fit=crop&w=400&h=250&q=80',
            ],
        ];

        $mockAlumni = [
            [
                'id' => '#',
                'full_name' => 'Muhammad Zakaullah',
                'current_designation' => 'Admiral (Retd) Chief of Naval Staff',
                'star_description' =>
                    'Dignity is not in possessing honors, but in deserving them. Admiral Zakaullah...',
                'class_year' => '1972',
                'image_url' =>
                    'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&h=350&q=80',
            ],
            [
                'id' => '#',
                'full_name' => 'Muhammad Zakaullah',
                'current_designation' => 'Admiral (Retd) Chief of Naval Staff',
                'star_description' =>
                    'Dignity is not in possessing honors, but in deserving them. Admiral Zakaullah...',
                'class_year' => '1972',
                'image_url' =>
                    'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&h=350&q=80',
            ],
            [
                'id' => '#',
                'full_name' => 'Muhammad Zakaullah',
                'current_designation' => 'Admiral (Retd) Chief of Naval Staff',
                'star_description' =>
                    'Dignity is not in possessing honors, but in deserving them. Admiral Zakaullah...',
                'class_year' => '1972',
                'image_url' =>
                    'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&h=350&q=80',
            ],
            [
                'id' => '#',
                'full_name' => 'Muhammad Zakaullah',
                'current_designation' => 'Admiral (Retd) Chief of Naval Staff',
                'star_description' =>
                    'Dignity is not in possessing honors, but in deserving them. Admiral Zakaullah...',
                'class_year' => '1972',
                'image_url' =>
                    'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&h=350&q=80',
            ],
        ];

        $displayNews = count($news) > 0 ? $news : json_decode(json_encode($mockNews));
        $displayAlumni = count($starAlumni) > 0 ? $starAlumni : json_decode(json_encode($mockAlumni));
    @endphp

    {{-- Hero --}}
    @php
        $heroSlides = json_decode($settings['hero_slides'] ?? '[]', true) ?: [];
        if (empty($heroSlides) && !empty($settings['hero_image'])) {
            $heroSlides = [$settings['hero_image']];
        }
        $heroImages =
            count($heroSlides) > 0
                ? array_map(fn($s) => asset('storage/' . $s), $heroSlides)
                : [asset('images/hero.png')];
    @endphp

    <section class="hero-custom" id="heroSlider">
        @foreach ($heroImages as $i => $img)
            <div class="hero-slide {{ $i === 0 ? 'active' : '' }}"
                style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.4)), url('{{ $img }}')">
            </div>
        @endforeach

        <div class="hero-overlay" style="width:100%;padding-left:60px;padding-right:60px;box-sizing:border-box">
            <div class="hero-content-custom">
                <h1>{{ $settings['hero_title'] ?? 'Welcome to POBA Alumni Network' }}</h1>
                <p class="tagline">{{ $settings['hero_tagline'] ?? 'Serving with Valour' }}</p>
                <p class="desc">
                    {{ $settings['hero_description'] ?? 'Join our prestigious community of Pakistan Ocean & Bay Alumni. Stay connected, share experiences, and build lasting professional relationships.' }}
                </p>
                <a href="{{ $settings['hero_btn_url'] ?? route('member.index') }}" class="btn-teal-capsule" target="_blank">
                    {{ $settings['hero_btn_text'] ?? 'Become a Member' }}
                </a>
            </div>

            @if (count($heroImages) > 1)
                <div class="hero-dots">
                    @foreach ($heroImages as $i => $img)
                        <span class="dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}"></span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <style>
    .hero-custom {
        position: relative;
        overflow: hidden;
        min-height: 480px;
    }

    .hero-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1.2s ease-in-out;
    }

    .hero-slide.active {
        opacity: 1;
    }

    .hero-overlay {
        position: relative;
        z-index: 2;
        padding: 100px 60px 60px 60px;
        box-sizing: border-box;
        width: 100%;
    }

    .hero-content-custom {
        text-align: left !important;
        max-width: 640px;
        width: 100%;
    }

    .hero-content-custom h1 {
        color: var(--theme-on-hero, #ffffff) !important;
        white-space: normal;         /* was nowrap — this was breaking mobile */
        word-break: break-word;
        font-size: clamp(1.4rem, 4vw, 2.4rem);  /* fluid font size */
        line-height: 1.25;
    }

    .hero-content-custom .tagline {
        color: #FF7E40;
        font-size: clamp(1rem, 2.5vw, 1.25rem);
    }

    .hero-content-custom .desc {
        color: rgba(255,255,255,0.88);
        font-size: clamp(0.875rem, 2vw, 1rem);
    }

    .hero-dots {
        display: flex;
        gap: 8px;
        margin-top: 30px;
    }

    .hero-dots .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
    }

    .hero-dots .dot.active {
        background: #fff;
    }

    /* Tablet */
    @media (max-width: 1024px) {
        .hero-overlay {
            padding: 80px 40px 50px 40px;
        }

        .hero-content-custom {
            max-width: 100%;
        }
    }

    /* Mobile */
    @media (max-width: 768px) {
        .hero-custom {
            min-height: 300px;
        }

        .hero-overlay {
            padding: 60px 20px 40px 20px;
        }

        .hero-content-custom {
            max-width: 100%;
        }

        .btn-teal-capsule {
            display: inline-block;
            width: auto;
            max-width: 100%;
            white-space: normal;
            text-align: center;
        }
    }

    /* Small mobile */
    @media (max-width: 480px) {
        .hero-custom {
            min-height: 260px;
        }

        .hero-overlay {
            padding: 50px 16px 30px 16px;
        }

        .hero-dots {
            margin-top: 20px;
        }
    }

    .stat-icon-custom {
        background: transparent !important;
        box-shadow: none !important;
        width: auto !important;
        height: auto !important;
    }
</style>

    @if (count($heroImages) > 1)
        <script>
            (function() {
                const slides = document.querySelectorAll('#heroSlider .hero-slide');
                const dots = document.querySelectorAll('#heroSlider .dot');
                let current = 0,
                    interval;

                function goTo(index) {
                    slides.forEach((s, i) => s.classList.toggle('active', i === index));
                    dots.forEach((d, i) => d.classList.toggle('active', i === index));
                    current = index;
                }

                function next() {
                    goTo((current + 1) % slides.length);
                }

                function startAuto() {
                    interval = setInterval(next, 5000);
                }

                dots.forEach(dot => {
                    dot.addEventListener('click', () => {
                        clearInterval(interval);
                        goTo(parseInt(dot.dataset.slide));
                        startAuto();
                    });
                });

                startAuto();
            })();
        </script>
    @endif

    {{-- About Section --}}
    <section class="section-pad" style="background:{{ $settings['about_bg_color'] ?? '#fff' }}">
        <div class="container">
            <div class="grid-2" style="align-items:center;gap:50px">
                <div>
                    <img src="{{ !empty($settings['about_image']) ? asset('storage/' . $settings['about_image']) : asset('images/about.png') }}"
                        alt="About POBA"
                        style="border-radius:24px;width:100%;object-fit:cover;max-height:380px;box-shadow: 0 15px 35px rgba(0,0,0,0.1)">
                </div>
                <div>
                    <h2 class="section-title-left">{{ $settings['about_title'] ?? 'About POBA' }}</h2>
                    <p style="color:var(--text-muted);font-size:15px;line-height:1.7;margin-bottom:28px">
                        {{ $settings['about_description'] ?? 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' }}
                    </p>
                    @php
                        $aboutStats = json_decode($settings['about_stats'] ?? '[]', true) ?: [];
                        if (empty($aboutStats)) {
                            $aboutStats = [
                                ['icon' => null, 'heading' => 'Excellence', 'subheading' => 'In Service & Leadership'],
                                ['icon' => null, 'heading' => 'Community', 'subheading' => 'Strong Alumni Network'],
                                ['icon' => null, 'heading' => 'Global Reach', 'subheading' => 'Worldwide Presence'],
                                ['icon' => null, 'heading' => 'Integrity', 'subheading' => 'Honor & Commitment'],
                            ];
                        }
                    @endphp

                    <div class="stats-grid-custom">
                        @foreach ($aboutStats as $stat)
                            <div class="stat-item-custom">
                                <div class="stat-icon-custom">
                                    @if (!empty($stat['icon']))
                                        <img src="{{ asset('storage/' . $stat['icon']) }}" alt=""
                                            style="width:40px;height:40px;object-fit:contain">
                                    @else
                                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13 2L20 14L27 2H22L20 6L18 2H13Z" fill="#C0392B" />
                                            <path d="M14 8L20 18L26 8H22L20 12L18 8H14Z" fill="#E74C3C" />
                                            <circle cx="20" cy="24" r="11" fill="#F4B731" stroke="#D4920A"
                                                stroke-width="1.5" />
                                            <circle cx="20" cy="24" r="7.5" fill="#FBCB4A" stroke="#F4B731"
                                                stroke-width="1" />
                                            <path
                                                d="M20 19.5L21.3 22.2L24.3 22.6L22.1 24.7L22.7 27.6L20 26.1L17.3 27.6L17.9 24.7L15.7 22.6L18.7 22.2L20 19.5Z"
                                                fill="#fff" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="stat-heading-custom">{{ $stat['heading'] }}</div>
                                    <div class="stat-subheading-custom">{{ $stat['subheading'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="margin-top:35px">
                        <a href="{{ $settings['about_btn_url'] ?? route('member.index') }}"
                            class="btn-outline-orange-capsule" target="_blank">
                            {{ $settings['about_btn_text'] ?? 'Become a Member' }}
                        </a>
                    </div>
                </div>
            </div>
    </section>

    {{-- Latest News --}}
    <section class="section-pad" style="background:{{ $settings['news_bg_color'] ?? 'var(--bg-light)' }}">
        <div class="container">
            <h2 class="section-title-center">Latest News</h2>
            <div class="grid-4" style="margin-top:40px">
                @foreach ($displayNews as $item)
                    <div class="card-news">
                        <div class="card-news-img-container">
                            <img class="card-news-img"
                                src="{{ isset($item->image_url) ? $item->image_url : ($item->image ? asset('storage/' . $item->image) : 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?auto=format&fit=crop&w=400&h=250&q=80') }}"
                                alt="{{ $item->title }}">
                        </div>
                        <div class="card-news-body">
                            <div class="card-news-type">
                                {{ isset($item->type) ? $item->type : 'NEWS | EXCLUSIVE | ALUMNI' }}</div>
                            <h3 class="card-news-title">{{ $item->title }}</h3>
                            <p class="card-news-text">{{ Str::limit(strip_tags($item->description), 110) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="text-align:center;margin-top:45px">
                <a href="{{ route('news.index') }}" class="btn-teal-news-view">View all News</a>
            </div>
        </div>
    </section>

    {{-- Star Alumni --}}
    <section class="section-pad" style="background:{{ $settings['star_alumni_bg_color'] ?? '#eaf4f4' }}">
        <div class="container">
            <h2 class="section-title-center">Star Alumni</h2>
            <div class="grid-4" style="margin-top:40px">
                @foreach ($displayAlumni as $alumni)
                    <div class="alumni-card-custom">
                        <div class="alumni-img-container">
                            <img src="{{ isset($alumni->image_url) ? $alumni->image_url : ($alumni->profile_photo ? asset('storage/' . $alumni->profile_photo) : 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&h=350&q=80') }}"
                                alt="{{ $alumni->full_name }}">
                        </div>
                        <div class="alumni-info-custom">
                            <h4>{{ $alumni->full_name }}</h4>
                            <div class="position-custom">{{ $alumni->current_designation }}</div>
                            <div class="desc-custom">
                                {{ Str::limit($alumni->star_description ?? ($alumni->achievements ?? ''), 80) }}</div>
                            <div class="class-year-custom">Class of {{ $alumni->class_year }}</div>
                            <div style="text-align:center;margin-top:15px">
                                <a href="{{ $alumni->id === '#' ? '#' : route('alumni.show', $alumni->id) }}"
                                    class="btn-teal-alumni-details">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div style="text-align:center;margin-top:45px">
                <a href="{{ route('star.alumni') }}" class="btn-outline-teal-view">View All</a>
            </div>
        </div>
    </section>

@endsection
