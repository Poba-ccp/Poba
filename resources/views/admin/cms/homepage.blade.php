{{-- FILE: resources/views/admin/cms/homepage.blade.php --}}
@extends('layouts.admin')
@section('title', 'CMS Homepage - Admin')
@section('page-title', 'Content Management')
@section('content')

    @include('admin.cms._tabs', ['active' => 'homepage'])

    <form method="POST" action="{{ route('admin.cms.homepage.save') }}" enctype="multipart/form-data">
        @csrf

        {{-- Hero Image --}}
        @php
            $heroSlides = json_decode($settings['hero_slides'] ?? '[]', true) ?: [];
        @endphp

        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">Hero Section</div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Title: *</label>
                    <input type="text" name="hero_title" class="admin-input"
                        value="{{ $settings['hero_title'] ?? 'Welcome to POBA Alumni Network' }}" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Tagline: *</label>
                    <input type="text" name="hero_tagline" class="admin-input"
                        value="{{ $settings['hero_tagline'] ?? 'Serving With Valour' }}" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">Description: *</label>
                <textarea name="hero_description" class="admin-input" rows="3">{{ $settings['hero_description'] ?? '' }}</textarea>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Button Text:</label>
                    <input type="text" name="hero_btn_text" class="admin-input"
                        value="{{ $settings['hero_btn_text'] ?? 'Become a Member' }}">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Button URL:</label>
                    <input type="url" name="hero_btn_url" class="admin-input"
                        value="{{ $settings['hero_btn_url'] ?? '' }}" placeholder="https://...">
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">Slider Images: *</label>

                <div id="slidesContainer" style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:16px">
                    @foreach ($heroSlides as $slide)
                        <div class="slide-thumb" style="position:relative;width:140px">
                            <img src="{{ asset('storage/' . $slide) }}"
                                style="width:100%;height:90px;object-fit:cover;border-radius:8px">
                            <button type="button" class="remove-slide-btn" data-path="{{ $slide }}"
                                style="position:absolute;top:-8px;right:-8px;background:#e74c3c;color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer">×</button>
                        </div>
                    @endforeach
                </div>

                <div id="removeSlideInputs"></div>

                <div class="admin-upload" onclick="document.getElementById('newSlides').click()">
                    <span style="font-size:20px">➕</span>
                    <p>Click to add one or more slide images (select multiple at once)</p>
                </div>
                <input type="file" id="newSlides" name="new_slides[]" accept="image/*" multiple style="display:none"
                    onchange="previewNewSlides(this)">
                <div id="newSlidesPreview" style="display:flex;flex-wrap:wrap;gap:10px;margin-top:10px"></div>
            </div>
        </div>
        {{-- About Section --}}
        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">About Section</div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Title: *</label>
                    <input type="text" name="about_title" class="admin-input"
                        value="{{ $settings['about_title'] ?? 'About POBA' }}" required>
                </div>
                <!-- <div class="admin-form-group">
                    <label class="admin-form-label">About Section Background Color:</label>
                    <div class="cms-color-field">
                        <input type="color" id="about_bg_color_picker"
                            value="{{ $settings['about_bg_color'] ?? '#ffffff' }}"
                            onchange="document.getElementById('about_bg_color_text').value=this.value">
                        <input type="text" id="about_bg_color_text" name="about_bg_color"
                            value="{{ $settings['about_bg_color'] ?? '#ffffff' }}"
                            oninput="document.getElementById('about_bg_color_picker').value=this.value">
                    </div>
                </div> -->
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">Upload Image: *</label>
                @if (!empty($settings['about_image']))
                    <img src="{{ asset('storage/' . $settings['about_image']) }}" alt="About"
                        style="height:60px;border-radius:6px;display:block;margin-bottom:8px">
                @endif
                <div class="admin-upload" onclick="document.getElementById('aboutImg').click()">
                    <span style="font-size:18px">➕</span>
                    <p style="font-size:12px">Click to upload</p>
                </div>
                <input type="file" id="aboutImg" name="about_image" accept="image/*" style="display:none"
                    onchange="document.getElementById('aboutName').textContent='✓ '+this.files[0].name">
                <p id="aboutName" style="font-size:12px;color:var(--teal);margin-top:4px"></p>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">Description: *</label>
                <textarea name="about_description" class="admin-input" rows="4">{{ $settings['about_description'] ?? '' }}</textarea>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Button Text:</label>
                    <input type="text" name="about_btn_text" class="admin-input"
                        value="{{ $settings['about_btn_text'] ?? 'Become a Member' }}">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Button URL:</label>
                    <input type="url" name="about_btn_url" class="admin-input"
                        value="{{ $settings['about_btn_url'] ?? '' }}" placeholder="https://...">
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Stat Badges (Excellence, Community, etc.):</label>

                @php
                    $aboutStats = json_decode($settings['about_stats'] ?? '[]', true) ?: [];
                @endphp

                <div id="statsContainer">
                    @foreach ($aboutStats as $i => $stat)
                        <div class="admin-form-row-3 stat-row" data-index="{{ $i }}"
                            style="margin-bottom:16px">
                            <div>
                                <div class="stat-row-header">
                                    <label class="admin-form-label" style="margin-bottom:0">Stats:</label>
                                    <button type="button" class="remove-stat-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6" />
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                            <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="stat-icon-upload" onclick="this.querySelector('input').click()">
                                    @if (!empty($stat['icon']))
                                        <img src="{{ asset('storage/' . $stat['icon']) }}" class="stat-icon-preview">
                                    @else
                                        <span class="stat-icon-plus">+</span>
                                    @endif
                                    <span>Icon</span>
                                    <input type="file" name="stats[{{ $i }}][icon_new]" accept="image/*"
                                        style="display:none" onchange="previewStatIcon(this)">
                                </div>
                                <input type="hidden" name="stats[{{ $i }}][icon_existing]"
                                    value="{{ $stat['icon'] ?? '' }}">
                            </div>
                            <div>
                                <label class="admin-form-label">Heading:</label>
                                <input type="text" name="stats[{{ $i }}][heading]" class="admin-input"
                                    placeholder="Excellence" value="{{ $stat['heading'] }}">
                            </div>
                            <div>
                                <label class="admin-form-label">Sub-heading:</label>
                                <input type="text" name="stats[{{ $i }}][subheading]" class="admin-input"
                                    placeholder="In Service & Leadership" value="{{ $stat['subheading'] }}">
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="addStatRow"
                    style="background:none;border:none;color:var(--orange);font-weight:600;font-size:13px;cursor:pointer;margin-top:6px;display:flex;align-items:center;gap:6px">
                    <span
                        style="background:var(--orange);color:#fff;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px">+</span>
                    Add row
                </button>
            </div>


            <div style="display:flex;gap:14px">
                <button type="submit" class="btn-teal" style="padding:12px 40px">Save</button>
                <button type="reset" class="btn-outline-red" style="padding:12px 40px">Cancel</button>
            </div>
    </form>

    <script>
        function previewNewSlides(input) {
            const preview = document.getElementById('newSlidesPreview');
            preview.innerHTML = '';
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style = 'width:100px;height:65px;object-fit:cover;border-radius:6px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }

        document.querySelectorAll('.remove-slide-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'remove_slides[]';
                hidden.value = this.dataset.path;
                document.getElementById('removeSlideInputs').appendChild(hidden);
                this.closest('.slide-thumb').remove();
            });
        });
    </script>
    <script>
        let statIndex = {{ count($aboutStats) }};

        function previewStatIcon(input) {
            const wrapper = input.closest('.stat-icon-upload');
            const reader = new FileReader();
            reader.onload = e => {
                wrapper.querySelector('.stat-icon-plus, .stat-icon-preview')?.remove();
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'stat-icon-preview';
                wrapper.insertBefore(img, wrapper.firstChild);
            };
            if (input.files[0]) reader.readAsDataURL(input.files[0]);
        }

        document.getElementById('addStatRow').addEventListener('click', function() {
            const container = document.getElementById('statsContainer');
            const row = document.createElement('div');
            row.className = 'admin-form-row-3 stat-row';
            row.style = 'margin-bottom:16px';
            row.innerHTML = `
        <div>
            <label class="admin-form-label">Stats:
                <button type="button" class="remove-stat-btn" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:15px;vertical-align:middle">🗑</button>
            </label>
            <div class="stat-icon-upload" onclick="this.querySelector('input').click()">
                <span class="stat-icon-plus">+</span>
                <span>Icon</span>
                <input type="file" name="stats[${statIndex}][icon_new]" accept="image/*" style="display:none" onchange="previewStatIcon(this)">
            </div>
            <input type="hidden" name="stats[${statIndex}][icon_existing]" value="">
        </div>
        <div>
            <label class="admin-form-label">Heading:</label>
            <input type="text" name="stats[${statIndex}][heading]" class="admin-input" placeholder="Excellence">
        </div>
        <div>
            <label class="admin-form-label">Sub-heading:</label>
            <input type="text" name="stats[${statIndex}][subheading]" class="admin-input" placeholder="In Service & Leadership">
        </div>`;
            container.appendChild(row);
            statIndex++;
            attachRemoveHandlers();
        });

        function attachRemoveHandlers() {
            document.querySelectorAll('.remove-stat-btn').forEach(btn => {
                btn.onclick = function() {
                    this.closest('.stat-row').remove();
                };
            });
        }
        attachRemoveHandlers();
    </script>

@endsection
