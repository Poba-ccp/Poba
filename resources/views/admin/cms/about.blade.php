{{-- FILE: resources/views/admin/cms/about.blade.php --}}
@extends('layouts.admin')
@section('title', 'CMS About Us - Admin')
@section('page-title', 'Content Management')
@section('content')

    @include('admin.cms._tabs', ['active' => 'about'])

    <form method="POST" action="{{ route('admin.cms.about.save') }}" enctype="multipart/form-data">
        @csrf

        {{-- First Section (Our Mission) --}}
        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">First Section</div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Title: *</label>
                    <input type="text" name="mission_title" class="admin-input"
                        value="{{ $settings['mission_title'] ?? 'Our Mission' }}" required>
                </div>
                <div class="admin-form-group">
    <label class="admin-form-label">Upload Image: *</label>

    <div style="display:flex;align-items:center;gap:15px;">

        <!-- Upload Box -->
        <div onclick="document.getElementById('missImg').click()"
            style="flex:1;display:flex;align-items:center;gap:10px;background:#E6F3F4;border:1px solid #E6F3F4;border-radius:30px;padding:12px 18px;cursor:pointer;">

            <span style="width:22px;height:22px;background:#0B8CA3;color:#fff;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:bold;">
                +
            </span>

            <span style="font-size:13px;color:#666;">
                Drag & Drop files here or click to select file(s)
            </span>

            <input type="file"
                id="missImg"
                name="mission_image"
                accept="image/*"
                style="display:none;">
        </div>

        <!-- Image Preview -->
        @if (!empty($settings['mission_image']))
            <img src="{{ asset('storage/' . $settings['mission_image']) }}"
                alt="Mission"
                style="width:55px;height:55px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
        @endif

    </div>
</div>
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">Description:</label>
                <textarea name="mission_description" class="admin-input" rows="4">{{ $settings['mission_description'] ?? '' }}</textarea>
            </div>

            <!-- <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Button Text:</label>
                    <input type="text" name="mission_btn_text" class="admin-input"
                        value="{{ $settings['mission_btn_text'] ?? '' }}">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Button URL:</label>
                    <input type="url" name="mission_btn_url" class="admin-input"
                        value="{{ $settings['mission_btn_url'] ?? '' }}" placeholder="https://...">
                </div>
            </div> -->

            {{-- Stats Repeater --}}
            <div class="admin-form-group">
                @php
                    $missionStats = json_decode($settings['mission_stats'] ?? '[]', true);
                    if (empty($missionStats)) {
                        $missionStats = [
                            ['icon' => null, 'heading' => 'Excellence', 'subheading' => 'In Service & Leadership'],
                        ];
                    }
                @endphp

                <div id="statsContainer">
                    @foreach ($missionStats as $i => $stat)
                        <div class="admin-form-row-3 stat-row" data-index="{{ $i }}" style="margin-bottom:16px">
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
                                    <input type="file" name="mission_stats[{{ $i }}][icon_new]"
                                        accept="image/*" style="display:none" onchange="previewStatIcon(this)">
                                </div>
                                <input type="hidden" name="mission_stats[{{ $i }}][icon_existing]"
                                    value="{{ $stat['icon'] ?? '' }}">
                            </div>
                            <div>
                                <label class="admin-form-label">Heading:</label>
                                <input type="text" name="mission_stats[{{ $i }}][heading]"
                                    class="admin-input" placeholder="Excellence" value="{{ $stat['heading'] }}">
                            </div>
                            <div>
                                <label class="admin-form-label">Sub-heading:</label>
                                <input type="text" name="mission_stats[{{ $i }}][subheading]"
                                    class="admin-input" placeholder="In Service & Leadership"
                                    value="{{ $stat['subheading'] }}">
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
        </div>

        {{-- Second Section (Our History) --}}
        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">Second Section (Our History)</div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Title: *</label>
                    <input type="text" name="history_title" class="admin-input"
                        value="{{ $settings['history_title'] ?? 'Our History' }}" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Description:</label>
                    <input type="text" name="history_description" class="admin-input"
                        value="{{ $settings['history_description'] ?? 'Milestones in POBA\'s journey of excellence' }}">
                </div>
            </div>

            <div id="timelineRows">
                @php
                    $tl = json_decode($settings['history_timeline'] ?? '[]', true);
                    if (empty($tl)) {
                        $tl = [
                            [
                                'year' => '1947',
                                'heading' => 'Foundation Era',
                                'description' =>
                                    'Establishment of Pakistan Navy and the beginning of naval education traditions.',
                            ],
                            [
                                'year' => '1965',
                                'heading' => 'First Alumni Network',
                                'description' => 'Formation of the first organized alumni association.',
                            ],
                        ];
                    }
                @endphp
                @foreach ($tl as $i => $row)
                    <div class="admin-form-row-3 tl-row" id="tlRow-{{ $i }}"
                        style="align-items:start;margin-bottom:14px">
                        <div>
                            <div class="stat-row-header">
                                <label class="admin-form-label" style="margin-bottom:0">Year:</label>
                                <button type="button" onclick="this.closest('.tl-row').remove()"
                                    class="remove-stat-btn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                    </svg>
                                </button>
                            </div>
                            <input type="text" name="years[]" class="admin-input" value="{{ $row['year'] }}"
                                placeholder="1947">
                        </div>
                        <div>
                            <label class="admin-form-label">Heading:</label>
                            <input type="text" name="headings[]" class="admin-input" value="{{ $row['heading'] }}"
                                placeholder="Foundation Era">
                        </div>
                        <div>
                            <label class="admin-form-label">Description:</label>
                            <input type="text" name="descriptions[]" class="admin-input"
                                value="{{ $row['description'] }}" placeholder="Description...">
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="#" onclick="addTimelineRow(); return false;"
                style="font-size:13px;color:var(--teal);font-weight:600">+ Add row</a>
        </div>

        <div style="display:flex;gap:14px">
            <button type="submit" class="btn-teal" style="padding:12px 40px">Save</button>
            <button type="reset" class="btn-outline-red" style="padding:12px 40px">Cancel</button>
        </div>
    </form>

    <script>
        let statIndex = {{ count($missionStats) }};

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
            <div class="stat-row-header">
                <label class="admin-form-label" style="margin-bottom:0">Stats:</label>
                <button type="button" class="remove-stat-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                </button>
            </div>
            <div class="stat-icon-upload" onclick="this.querySelector('input').click()">
                <span class="stat-icon-plus">+</span><span>Icon</span>
                <input type="file" name="mission_stats[${statIndex}][icon_new]" accept="image/*" style="display:none" onchange="previewStatIcon(this)">
            </div>
            <input type="hidden" name="mission_stats[${statIndex}][icon_existing]" value="">
        </div>
        <div>
            <label class="admin-form-label">Heading:</label>
            <input type="text" name="mission_stats[${statIndex}][heading]" class="admin-input" placeholder="Excellence">
        </div>
        <div>
            <label class="admin-form-label">Sub-heading:</label>
            <input type="text" name="mission_stats[${statIndex}][subheading]" class="admin-input" placeholder="In Service & Leadership">
        </div>`;
            container.appendChild(row);
            statIndex++;
            attachRemoveHandlers();
        });

        function attachRemoveHandlers() {
            document.querySelectorAll('.remove-stat-btn:not([onclick])').forEach(btn => {
                btn.onclick = function() {
                    this.closest('.stat-row').remove();
                };
            });
        }
        attachRemoveHandlers();

        let tlCount = {{ count($tl) }};

        function addTimelineRow() {
            const i = tlCount++;
            const html = `<div class="admin-form-row-3 tl-row" id="tlRow-${i}" style="align-items:start;margin-bottom:14px">
        <div>
            <div class="stat-row-header">
                <label class="admin-form-label" style="margin-bottom:0">Year:</label>
                <button type="button" onclick="this.closest('.tl-row').remove()" class="remove-stat-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                </button>
            </div>
            <input type="text" name="years[]" class="admin-input" placeholder="1947">
        </div>
        <div>
            <label class="admin-form-label">Heading:</label>
            <input type="text" name="headings[]" class="admin-input" placeholder="Era Name">
        </div>
        <div>
            <label class="admin-form-label">Description:</label>
            <input type="text" name="descriptions[]" class="admin-input" placeholder="Description...">
        </div>
    </div>`;
            document.getElementById('timelineRows').insertAdjacentHTML('beforeend', html);
        }
    </script>
@endsection
