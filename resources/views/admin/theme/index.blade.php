{{-- FILE: resources/views/admin/theme/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Theme Settings - Admin')
@section('page-title', 'Theme Settings')

@section('content')
<style>
/* ── Page Layout ── */
.theme-wrap        { display:flex; gap:28px; align-items:flex-start; flex-wrap:wrap; }
.theme-form-col    { flex:1; min-width:320px; }
.theme-preview-col { width:380px; position:sticky; top:90px; flex-shrink:0; }

/* ── Section Cards ── */
.ts-card  { background:#fff; border-radius:14px; padding:24px 26px; margin-bottom:18px;
             box-shadow:0 2px 10px rgba(0,0,0,.05); border:1px solid #e8ecf0; }
.ts-card h3 { font-size:14px; font-weight:700; color:#1a202c; margin:0 0 16px 0;
              padding-bottom:10px; border-bottom:2px solid #f0f4f8;
              display:flex; align-items:center; gap:8px; }

/* ── Grid ── */
.ts-row   { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px; }
.ts-row-1 { grid-template-columns:1fr; margin-bottom:14px; }
.ts-grp   { display:flex; flex-direction:column; gap:5px; }
.ts-lbl   { font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.5px; }
.ts-sel, .ts-inp {
    border:1.5px solid #e2e8f0; border-radius:8px; padding:8px 12px;
    font-size:13px; color:#1a202c; background:#f8fafc;
    outline:none; width:100%; box-sizing:border-box; transition:border .2s;
}
.ts-sel:focus, .ts-inp:focus { border-color:#0d9488; background:#fff; }
.ts-sel { cursor:pointer; }

/* ── Font preview ── */
.font-sample { font-size:11px; color:#94a3b8; margin-top:3px; font-style:italic; }

/* ── Color Row ── */
.clr-row  { display:flex; align-items:center; gap:8px; margin-bottom:12px; }
.clr-dot  { width:36px; height:34px; border-radius:7px; border:1.5px solid #e2e8f0;
             cursor:pointer; padding:2px; flex-shrink:0; }
.clr-hex  { flex:1; border:1.5px solid #e2e8f0; border-radius:8px; padding:7px 10px;
             font-size:12px; font-family:monospace; background:#f8fafc; outline:none;
             color:#1a202c; }
.clr-hex:focus { border-color:#0d9488; }
.clr-lbl  { font-size:11px; font-weight:600; color:#64748b; margin-bottom:5px;
             text-transform:uppercase; letter-spacing:.5px; }
.clr-name { font-size:12px; color:#475569; margin-bottom:5px; font-weight:500; }

/* ── Preview Panel ── */
.prev-panel { background:#fff; border-radius:14px; overflow:hidden;
              box-shadow:0 4px 20px rgba(0,0,0,.1); border:1px solid #e2e8f0; }
.prev-header { background:#0d9488; color:#fff; padding:11px 16px;
               display:flex; align-items:center; justify-content:space-between; }
.prev-header span:first-child { font-size:13px; font-weight:700; }
.prev-header span:last-child  { font-size:11px; opacity:.8; }
.prev-body   { padding:0; }

/* ── Isolated preview iframe wrapper ── */
#previewFrame { width:100%; height:580px; border:none; display:block; }

/* ── Action Buttons ── */
.ts-actions { display:flex; gap:12px; margin-top:4px; flex-wrap:wrap; }
.btn-ts-save  { padding:11px 30px; background:#0d9488; color:#fff; border:none;
                border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;
                transition:background .2s; }
.btn-ts-save:hover  { background:#0b7a70; }
.btn-ts-reset { padding:11px 22px; background:transparent; color:#dc2626;
                border:1.5px solid #dc2626; border-radius:8px; font-size:13px;
                font-weight:600; cursor:pointer; transition:all .2s; }
.btn-ts-reset:hover { background:#fef2f2; }

@media(max-width:960px){
    .theme-preview-col { width:100%; position:static; }
    .ts-row { grid-template-columns:1fr; }
}
</style>

<form method="POST" action="{{ route('admin.theme.update') }}" id="themeForm">
@csrf @method('PUT')
<div class="theme-wrap">

    {{-- ══════════════ LEFT: FORM ══════════════ --}}
    <div class="theme-form-col">

        {{-- ── Typography ── --}}
        <div class="ts-card">
            <h3>🔤 Typography</h3>

            <div class="ts-row">
                <div class="ts-grp">
                    <label class="ts-lbl">Heading Font</label>
                    <select name="heading_font" class="ts-sel" id="f_heading_font" onchange="liveUpdate()">
                        @foreach($googleFonts as $font)
                        <option value="{{ $font }}" {{ ($settings['heading_font'] ?? 'Poppins') === $font ? 'selected' : '' }}>{{ $font }}</option>
                        @endforeach
                    </select>
                    <span class="font-sample" id="sample_heading">Sample text</span>
                </div>
                <div class="ts-grp">
                    <label class="ts-lbl">Body Font</label>
                    <select name="body_font" class="ts-sel" id="f_body_font" onchange="liveUpdate()">
                        @foreach($googleFonts as $font)
                        <option value="{{ $font }}" {{ ($settings['body_font'] ?? 'Roboto') === $font ? 'selected' : '' }}>{{ $font }}</option>
                        @endforeach
                    </select>
                    <span class="font-sample" id="sample_body">Sample text</span>
                </div>
            </div>

            <div class="ts-row">
                <div class="ts-grp">
                    <label class="ts-lbl">Navigation Font</label>
                    <select name="nav_font" class="ts-sel" id="f_nav_font" onchange="liveUpdate()">
                        @foreach($googleFonts as $font)
                        <option value="{{ $font }}" {{ ($settings['nav_font'] ?? 'Poppins') === $font ? 'selected' : '' }}>{{ $font }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ts-grp">
                    <label class="ts-lbl">Heading Weight</label>
                    <select name="heading_weight" class="ts-sel" id="f_heading_weight" onchange="liveUpdate()">
                        @foreach(['400'=>'Regular','500'=>'Medium','600'=>'Semi Bold','700'=>'Bold','800'=>'Extra Bold','900'=>'Black'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['heading_weight'] ?? '700') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="ts-row">
                <div class="ts-grp">
                    <label class="ts-lbl">Heading Size</label>
                    <select name="heading_size" class="ts-sel" id="f_heading_size" onchange="liveUpdate()">
                        @foreach(['1.5rem'=>'Small','1.8rem'=>'Medium','2rem'=>'Default','2.5rem'=>'Large','3rem'=>'XL','3.5rem'=>'XXL'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['heading_size'] ?? '2rem') === $v ? 'selected' : '' }}>{{ $l }} ({{ $v }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="ts-grp">
                    <label class="ts-lbl">Sub-Heading Size</label>
                    <select name="subheading_size" class="ts-sel" id="f_subheading_size" onchange="liveUpdate()">
                        @foreach(['1rem'=>'Small','1.2rem'=>'Medium','1.5rem'=>'Default','1.8rem'=>'Large','2rem'=>'XL'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['subheading_size'] ?? '1.5rem') === $v ? 'selected' : '' }}>{{ $l }} ({{ $v }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="ts-row">
                <div class="ts-grp">
                    <label class="ts-lbl">Body Text Size</label>
                    <select name="body_size" class="ts-sel" id="f_body_size" onchange="liveUpdate()">
                        @foreach(['12px'=>'Small','13px'=>'Default','14px'=>'Medium','15px'=>'Large','16px'=>'XL'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['body_size'] ?? '14px') === $v ? 'selected' : '' }}>{{ $l }} ({{ $v }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="ts-grp">
                    <label class="ts-lbl">Nav Font Size</label>
                    <select name="nav_size" class="ts-sel" id="f_nav_size" onchange="liveUpdate()">
                        @foreach(['12px'=>'Small','13px'=>'Default','14px'=>'Medium','15px'=>'Large','16px'=>'XL'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['nav_size'] ?? '14px') === $v ? 'selected' : '' }}>{{ $l }} ({{ $v }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Colors ── --}}
        <div class="ts-card">
            <h3>🎨 Colors</h3>
            @php
            $colorFields = [
                'primary_color'   => ['Primary Color (Teal — buttons, headings, navbar)',  '#1a7a7a'],
                'secondary_color' => ['Secondary Color (Orange — accents, hover)',          '#e87722'],
                'text_color'      => ['Body Text Color',                                    '#2c3e50'],
                'bg_color'        => ['Page Background Color',                              '#ffffff'],
                'nav_bg_color'    => ['Navbar Background Color',                            '#ffffff'],
                'footer_bg_color' => ['Footer Background Color',                            '#1a7a7a'],
                'card_bg_color'   => ['Card Background Color',                              '#ffffff'],
            ];
            @endphp

            @foreach($colorFields as $key => [$label, $default])
            <div>
                <div class="clr-name">{{ $label }}</div>
                <div class="clr-row">
                    <input type="color" class="clr-dot" id="picker_{{ $key }}"
                           value="{{ $settings[$key] ?? $default }}"
                           oninput="syncHex('{{ $key }}', this.value); liveUpdate()">
                    <input type="text" name="{{ $key }}" id="hex_{{ $key }}" class="clr-hex"
                           value="{{ $settings[$key] ?? $default }}" maxlength="7"
                           placeholder="#000000"
                           oninput="syncPicker('{{ $key }}', this.value); liveUpdate()">
                </div>
            </div>
            @endforeach
        </div>

        {{-- ── Layout ── --}}
        <div class="ts-card">
            <h3>📐 Shapes & Layout</h3>
            <div class="ts-row">
                <div class="ts-grp">
                    <label class="ts-lbl">Card Corner Radius</label>
                    <select name="border_radius" class="ts-sel" id="f_border_radius" onchange="liveUpdate()">
                        @foreach(['0px'=>'Sharp','4px'=>'Slight','8px'=>'Small','12px'=>'Default','16px'=>'Large','20px'=>'XL','24px'=>'Rounded'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['border_radius'] ?? '12px') === $v ? 'selected' : '' }}>{{ $l }} ({{ $v }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="ts-grp">
                    <label class="ts-lbl">Button Corner Radius</label>
                    <select name="button_radius" class="ts-sel" id="f_button_radius" onchange="liveUpdate()">
                        @foreach(['0px'=>'Sharp','4px'=>'Slight','8px'=>'Small','16px'=>'Medium','24px'=>'Large','30px'=>'Pill','50px'=>'Full Pill'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['button_radius'] ?? '30px') === $v ? 'selected' : '' }}>{{ $l }} ({{ $v }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="ts-actions">
            <button type="submit" class="btn-ts-save">💾 Save & Apply Theme</button>
            <button type="button" class="btn-ts-reset" onclick="confirmReset()">↺ Reset to Default</button>
        </div>
    </div>

    {{-- ══════════════ RIGHT: ISOLATED PREVIEW ══════════════ --}}
    <div class="theme-preview-col">
        <div class="prev-panel">
            <div class="prev-header">
                <span>👁 Live Preview</span>
                <span>Updates instantly</span>
            </div>
            <div class="prev-body">
                <iframe id="previewFrame" scrolling="yes"></iframe>
            </div>
        </div>
    </div>

</div>
</form>

{{-- Reset form --}}
<form method="POST" action="{{ route('admin.theme.reset') }}" id="resetForm">
    @csrf @method('DELETE')
</form>

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════
// POBA THEME SETTINGS — LIVE PREVIEW ENGINE
// Uses an isolated iframe so NO CSS leaks between
// the admin panel and the preview.
// ═══════════════════════════════════════════════════════

const loadedFonts = new Set();

// ── Load a Google Font into the PARENT page (for font samples) ──
function loadFont(name) {
    if (!name || loadedFonts.has(name)) return;
    loadedFonts.add(name);
    const l = document.createElement('link');
    l.rel  = 'stylesheet';
    l.href = `https://fonts.googleapis.com/css2?family=${name.replace(/ /g,'+')
              }:wght@400;500;600;700;800&display=swap`;
    document.head.appendChild(l);
}

// ── Read a form field value by name ──
function gv(name) {
    const el = document.querySelector(`[name="${name}"]`);
    return el ? el.value.trim() : '';
}

// ── Sync color picker → hex text ──
function syncHex(key, val) {
    document.getElementById('hex_' + key).value = val;
}

// ── Sync hex text → color picker ──
function syncPicker(key, val) {
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        document.getElementById('picker_' + key).value = val;
    }
}

// ── Build complete HTML for the isolated preview iframe ──
function buildPreviewHTML() {
    const headingFont    = gv('heading_font')    || 'Poppins';
    const bodyFont       = gv('body_font')       || 'Roboto';
    const navFont        = gv('nav_font')        || 'Poppins';
    const headingSize    = gv('heading_size')    || '2rem';
    const subheadingSize = gv('subheading_size') || '1.5rem';
    const bodySize       = gv('body_size')       || '14px';
    const navSize        = gv('nav_size')        || '14px';
    const headingWeight  = gv('heading_weight')  || '700';
    const primaryColor   = gv('primary_color')   || '#1a7a7a';
    const secondaryColor = gv('secondary_color') || '#e87722';
    const textColor      = gv('text_color')      || '#2c3e50';
    const bgColor        = gv('bg_color')        || '#ffffff';
    const navBgColor     = gv('nav_bg_color')    || '#ffffff';
    const footerBgColor  = gv('footer_bg_color') || '#1a7a7a';
    const cardBgColor    = gv('card_bg_color')   || '#ffffff';
    const borderRadius   = gv('border_radius')   || '12px';
    const buttonRadius   = gv('button_radius')   || '30px';

    // Update parent font sample labels
    loadFont(headingFont);
    loadFont(bodyFont);
    const sH = document.getElementById('sample_heading');
    const sB = document.getElementById('sample_body');
    if (sH) { sH.style.fontFamily = `'${headingFont}',sans-serif`; sH.textContent = headingFont; }
    if (sB) { sB.style.fontFamily = `'${bodyFont}',sans-serif`;    sB.textContent = bodyFont; }

    // Build all 3 Google Font families in one URL
    const fonts = [...new Set([headingFont, bodyFont, navFont])];
    const fontUrl = 'https://fonts.googleapis.com/css2?'
        + fonts.map(f => `family=${f.replace(/ /g,'+')}:wght@400;500;600;700;800`).join('&')
        + '&display=swap';

    return `<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="${fontUrl}" rel="stylesheet">
<style>
*  { box-sizing:border-box; margin:0; padding:0; }

/* ── Root variables ── */
:root {
    --primary:     ${primaryColor};
    --secondary:   ${secondaryColor};
    --text:        ${textColor};
    --bg:          ${bgColor};
    --nav-bg:      ${navBgColor};
    --footer-bg:   ${footerBgColor};
    --card-bg:     ${cardBgColor};
    --radius:      ${borderRadius};
    --btn-radius:  ${buttonRadius};
    --h-font:      '${headingFont}', sans-serif;
    --b-font:      '${bodyFont}', sans-serif;
    --n-font:      '${navFont}', sans-serif;
    --h-size:      ${headingSize};
    --s-size:      ${subheadingSize};
    --b-size:      ${bodySize};
    --n-size:      ${navSize};
    --h-weight:    ${headingWeight};
}

body {
    font-family: var(--b-font);
    font-size:   var(--b-size);
    color:       var(--text);
    background:  var(--bg);
}

/* ── Navbar ── */
.p-nav {
    background:  var(--nav-bg);
    padding:     10px 16px;
    display:     flex;
    align-items: center;
    justify-content: space-between;
    box-shadow:  0 1px 8px rgba(0,0,0,.08);
    font-family: var(--n-font);
    font-size:   var(--n-size);
}
.p-nav-links { display:flex; gap:14px; }
.p-nav-links a {
    color:           var(--text);
    text-decoration: none;
    font-weight:     600;
    font-family:     var(--n-font);
    font-size:       var(--n-size);
}
.p-nav-links a:hover { color: var(--secondary); }
.p-nav-btn {
    background:    var(--primary);
    color:         #fff;
    border:        none;
    padding:       6px 16px;
    border-radius: var(--btn-radius);
    font-family:   var(--n-font);
    font-size:     var(--n-size);
    font-weight:   600;
    cursor:        pointer;
}

/* ── Content ── */
.p-content { padding: 20px 16px; }

/* ── Headings ── */
.p-h1 {
    font-family: var(--h-font);
    font-size:   var(--h-size);
    font-weight: var(--h-weight);
    color:       var(--primary);
    margin-bottom: 8px;
    line-height: 1.2;
}
.p-h2 {
    font-family: var(--h-font);
    font-size:   var(--s-size);
    font-weight: var(--h-weight);
    color:       var(--secondary);
    margin-bottom: 12px;
    line-height: 1.3;
}

/* ── Body text ── */
.p-text {
    font-family:   var(--b-font);
    font-size:     var(--b-size);
    color:         var(--text);
    line-height:   1.7;
    margin-bottom: 16px;
}

/* ── Buttons ── */
.p-btn-wrap { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:18px; }
.p-btn-primary {
    background:    var(--primary);
    color:         #fff;
    border:        none;
    padding:       9px 22px;
    border-radius: var(--btn-radius);
    font-family:   var(--b-font);
    font-size:     var(--b-size);
    font-weight:   600;
    cursor:        pointer;
}
.p-btn-secondary {
    background:    transparent;
    color:         var(--secondary);
    border:        2px solid var(--secondary);
    padding:       7px 22px;
    border-radius: var(--btn-radius);
    font-family:   var(--b-font);
    font-size:     var(--b-size);
    font-weight:   600;
    cursor:        pointer;
}

/* ── Card ── */
.p-card {
    background:    var(--card-bg);
    border-radius: var(--radius);
    border:        1px solid rgba(0,0,0,.08);
    padding:       16px;
    margin-bottom: 18px;
    box-shadow:    0 2px 8px rgba(0,0,0,.06);
}
.p-card-title {
    font-family:   var(--h-font);
    font-size:     15px;
    font-weight:   var(--h-weight);
    color:         var(--primary);
    margin-bottom: 6px;
}
.p-card-text {
    font-family: var(--b-font);
    font-size:   var(--b-size);
    color:       var(--text);
    line-height: 1.6;
}

/* ── Tag/Badge ── */
.p-badge {
    display:       inline-block;
    background:    var(--primary);
    color:         #fff;
    font-size:     11px;
    font-weight:   700;
    padding:       3px 10px;
    border-radius: 20px;
    margin-bottom: 6px;
    font-family:   var(--b-font);
}

/* ── Divider ── */
.p-divider {
    height: 1px;
    background: rgba(0,0,0,.1);
    margin: 14px 0;
}

/* ── Footer ── */
.p-footer {
    background:  var(--footer-bg);
    color:       #fff;
    padding:     14px 16px;
    font-family: var(--b-font);
    font-size:   12px;
    display:     flex;
    justify-content: space-between;
    align-items: center;
}
.p-footer-links { display:flex; gap:14px; }
.p-footer-links a { color:rgba(255,255,255,.8); text-decoration:none; font-size:12px; }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="p-nav">
    <div class="p-nav-links">
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Events</a>
        <a href="#">Alumni</a>
    </div>
    <button class="p-nav-btn">Login</button>
</nav>

<!-- Content -->
<div class="p-content">

    <span class="p-badge">Welcome</span>
    <h1 class="p-h1">Main Page Heading</h1>
    <h2 class="p-h2">Sub Heading Text Here</h2>

    <p class="p-text">
        This is a sample body paragraph. It shows how your
        content will look with the selected font family and
        size settings applied across the website.
    </p>

    <div class="p-btn-wrap">
        <button class="p-btn-primary">Primary Button</button>
        <button class="p-btn-secondary">Secondary</button>
    </div>

    <div class="p-divider"></div>

    <div class="p-card">
        <div class="p-card-title">Card Title Example</div>
        <div class="p-card-text">
            This card shows how your card background color,
            border radius, heading font, and body font look
            together in a real content block.
        </div>
    </div>

</div>

<!-- Footer -->
<footer class="p-footer">
    <span>© 2025 POBA Alumni Portal</span>
    <div class="p-footer-links">
        <a href="#">About</a>
        <a href="#">Contact</a>
        <a href="#">Privacy</a>
    </div>
</footer>

</body>
</html>`;
}

// ── Write preview into the iframe ──
function liveUpdate() {
    const frame = document.getElementById('previewFrame');
    const html  = buildPreviewHTML();
    // Use srcdoc for a fully isolated document
    frame.srcdoc = html;
}

// ── Confirm reset ──
function confirmReset() {
    if (confirm('Reset all theme settings to POBA defaults?\n\nThis cannot be undone.')) {
        document.getElementById('resetForm').submit();
    }
}

// ── Initialize on page load ──
document.addEventListener('DOMContentLoaded', function () {
    loadFont(gv('heading_font'));
    loadFont(gv('body_font'));
    liveUpdate();
});
</script>
@endpush
@endsection
