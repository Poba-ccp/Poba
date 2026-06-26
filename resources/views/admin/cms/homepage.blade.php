{{-- FILE: resources/views/admin/cms/homepage.blade.php --}}
@extends('layouts.admin')
@section('title', 'CMS Homepage - Admin')
@section('page-title', 'Content Management')
@section('content')
   <style>
.cms-color-field {
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 6px 10px;
    background: var(--white);
    height: 42px;
    box-sizing: border-box;
}
.cms-color-field input[type="color"] {
    width: 28px;
    height: 28px;
    padding: 0;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    flex-shrink: 0;
}
.cms-color-field input[type="text"] {
    border: none;
    outline: none;
    font-size: 13px;
    color: var(--text-dark);
    width: 100%;
    background: transparent;
}
</style>

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
            <input type="text" name="about_title" class="admin-input" value="{{ $settings['about_title'] ?? 'About POBA' }}" required>
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">About Section Background Color:</label>
            <div class="cms-color-field">
                <input type="color" id="about_bg_color_picker" value="{{ $settings['about_bg_color'] ?? '#ffffff' }}"
                       onchange="document.getElementById('about_bg_color_text').value=this.value">
                <input type="text" id="about_bg_color_text" name="about_bg_color"
                       value="{{ $settings['about_bg_color'] ?? '#ffffff' }}"
                       oninput="document.getElementById('about_bg_color_picker').value=this.value">
            </div>
        </div>
    </div>

    <div class="admin-form-group">
        <label class="admin-form-label">Upload Image: *</label>
        @if(!empty($settings['about_image']))
            <img src="{{ asset('storage/'.$settings['about_image']) }}" alt="About" style="height:60px;border-radius:6px;display:block;margin-bottom:8px">
        @endif
        <div class="admin-upload" onclick="document.getElementById('aboutImg').click()">
            <span style="font-size:18px">➕</span>
            <p style="font-size:12px">Click to upload</p>
        </div>
        <input type="file" id="aboutImg" name="about_image" accept="image/*" style="display:none" onchange="document.getElementById('aboutName').textContent='✓ '+this.files[0].name">
        <p id="aboutName" style="font-size:12px;color:var(--teal);margin-top:4px"></p>
    </div>

    <div class="admin-form-group">
        <label class="admin-form-label">Description: *</label>
        <textarea name="about_description" class="admin-input" rows="4">{{ $settings['about_description'] ?? '' }}</textarea>
    </div>

    <div class="admin-form-row">
        <div class="admin-form-group">
            <label class="admin-form-label">Button Text:</label>
            <input type="text" name="about_btn_text" class="admin-input" value="{{ $settings['about_btn_text'] ?? 'Become a Member' }}">
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Button URL:</label>
            <input type="url" name="about_btn_url" class="admin-input" value="{{ $settings['about_btn_url'] ?? '' }}" placeholder="https://...">
        </div>
    </div>
</div>

{{-- Section Background Colors --}}
<div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
    <div class="cms-section-title">Section Background Colors</div>
    <div class="admin-form-row">
        <div class="admin-form-group">
            <label class="admin-form-label">Latest News Background:</label>
            <div class="cms-color-field">
                <input type="color" id="news_bg_color_picker" value="{{ $settings['news_bg_color'] ?? '#f8f9fa' }}"
                       onchange="document.getElementById('news_bg_color_text').value=this.value">
                <input type="text" id="news_bg_color_text" name="news_bg_color"
                       value="{{ $settings['news_bg_color'] ?? '#f8f9fa' }}"
                       oninput="document.getElementById('news_bg_color_picker').value=this.value">
            </div>
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Star Alumni Background:</label>
            <div class="cms-color-field">
                <input type="color" id="star_alumni_bg_color_picker" value="{{ $settings['star_alumni_bg_color'] ?? '#eaf4f4' }}"
                       onchange="document.getElementById('star_alumni_bg_color_text').value=this.value">
                <input type="text" id="star_alumni_bg_color_text" name="star_alumni_bg_color"
                       value="{{ $settings['star_alumni_bg_color'] ?? '#eaf4f4' }}"
                       oninput="document.getElementById('star_alumni_bg_color_picker').value=this.value">
            </div>
        </div>
    </div>
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

@endsection
