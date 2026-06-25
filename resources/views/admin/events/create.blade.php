{{-- FILE: resources/views/admin/events/create.blade.php --}}
@extends('layouts.admin')
@section('title','Create Event - Admin')
@section('page-title','Create an Event')
@section('content')

<div style="margin-bottom:16px">
    <a href="{{ route('admin.events.index') }}" style="color:var(--text-muted);font-size:14px;text-decoration:none">← Back</a>
</div>

<div class="admin-form-page">
    <h2>Create an Event</h2>

    @if($errors->any())
        <div style="margin-bottom:20px;padding:12px 16px;background:#fcebeb;border:1px solid #f09595;border-radius:8px;color:#a32d2d;font-size:14px">
            <strong>Please fix the following errors:</strong>
            <ul style="margin:6px 0 0;padding-left:18px">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" id="eventForm">
        @csrf

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label class="admin-form-label">Event Title: *</label>
                <input type="text" name="title" class="admin-input" placeholder="Sailing Event" value="{{ old('title') }}" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Registration Required: *</label>
                <div style="display:flex;gap:24px;margin-top:10px">
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer">
                        <input type="radio" name="registration_required" value="1" {{ old('registration_required')=='1' ? 'checked' : '' }}> Yes
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer">
                        <input type="radio" name="registration_required" value="0" {{ old('registration_required','0')=='0' ? 'checked' : '' }}> No
                    </label>
                </div>
                <small style="color:var(--text-muted);font-size:12px;margin-top:4px;display:block">If No, the event is informational only — no registration button shown.</small>
            </div>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">Description: *</label>
            <textarea name="description" class="admin-input" rows="4" placeholder="Event description..." required>{{ old('description') }}</textarea>
        </div>

        <div class="admin-form-row" style="grid-template-columns:1fr 1fr 1fr 1fr">
            <div class="admin-form-group">
                <label class="admin-form-label">Start Date: *</label>
                <input type="date" name="start_date" id="start_date" class="admin-input" value="{{ old('start_date') }}" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">End Date: *</label>
                <input type="date" name="end_date" id="end_date" class="admin-input" value="{{ old('end_date') }}" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Start Time: *</label>
                <input type="time" name="start_time" id="start_time" class="admin-input" value="{{ old('start_time') }}" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">End Time:</label>
                <input type="time" name="end_time" id="end_time" class="admin-input" value="{{ old('end_time') }}">
            </div>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">Location: *</label>
            <input type="text" name="location" class="admin-input" placeholder="Sailing Public Spot" value="{{ old('location') }}" required>
        </div>

        <div class="admin-form-row">
            <div class="admin-form-group">
                <label class="admin-form-label">Focal Person Name: *</label>
                <input type="text" name="focal_person_name" class="admin-input" placeholder="Waleed Ahmed" value="{{ old('focal_person_name') }}">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Focal Person Number: *</label>
                <input type="text" name="focal_person_number" class="admin-input" placeholder="03454501450" value="{{ old('focal_person_number') }}">
            </div>
        </div>

        <div class="admin-form-row">
            {{-- ── Entry Batches multi-select tag input ────────────────────── --}}
            <div class="admin-form-group">
                <label class="admin-form-label">
                    Eligible Entry Batches:
                    <small style="color:var(--text-muted);font-weight:400"> (leave empty = open to all)</small>
                </label>

                {{-- Tag display area + input --}}
                <div id="batchTagBox" onclick="document.getElementById('batchInput').focus()"
                     style="min-height:44px;border:1px solid #d0d5dd;border-radius:8px;padding:6px 10px;display:flex;flex-wrap:wrap;gap:6px;align-items:center;cursor:text;background:#fff">
                    {{-- Tags rendered here by JS --}}
                    <input type="text" id="batchInput" placeholder="Type a batch number & press Enter"
                           style="border:none;outline:none;font-size:14px;min-width:200px;flex:1;padding:2px 0;background:transparent"
                           inputmode="numeric" pattern="[0-9]*">
                </div>

                {{-- Quick-add dropdown --}}
                <div style="display:flex;gap:8px;margin-top:8px;align-items:center">
                    <select id="batchDropdown" style="font-size:13px;padding:6px 10px;border:1px solid #d0d5dd;border-radius:8px;background:#fff;flex:1">
                        <option value="">— Quick add batch number —</option>
                        @for($i=1;$i<=100;$i++)
                            <option value="{{ $i }}">Batch {{ $i }}</option>
                        @endfor
                    </select>
                    <button type="button" onclick="addFromDropdown()" class="btn-teal" style="padding:6px 14px;font-size:13px">Add</button>
                    <button type="button" onclick="clearAllBatches()" style="padding:6px 14px;font-size:13px;border:1px solid #e24b4a;color:#e24b4a;border-radius:8px;background:#fff;cursor:pointer">Clear All</button>
                </div>

                {{-- Hidden inputs submitted with form --}}
                <div id="batchHiddenInputs"></div>

                <small style="color:var(--text-muted);font-size:12px;margin-top:6px;display:block">
                    Example: add 2, 4, 8, 9 individually. Only alumni whose batch matches can register.
                </small>

                {{-- Restore old() values after validation fail --}}
                @if(old('entry_batches'))
                    <script>window._oldBatches = @json(old('entry_batches'));</script>
                @endif
            </div>

            <div class="admin-form-group">
                <label class="admin-form-label">Gallery Link:</label>
                <input type="url" name="gallery_link" class="admin-input" placeholder="https://palandrians.org/gallery" value="{{ old('gallery_link') }}">
            </div>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">Upload Event Logo: *</label>
            <div class="admin-upload" onclick="document.getElementById('logoFile').click()">
                <span style="font-size:20px">➕</span>
                <p>Drag &amp; Drop files here or click to select file(s)</p>
            </div>
            <input type="file" id="logoFile" name="logo" accept="image/*" style="display:none"
                   onchange="document.getElementById('logoName').textContent='✓ '+this.files[0].name">
            <p id="logoName" style="font-size:12px;color:var(--teal);margin-top:6px"></p>
        </div>

        <div style="display:flex;gap:14px;margin-top:10px">
            <button type="submit" class="btn-teal" style="padding:12px 40px">Publish</button>
            <a href="{{ route('admin.events.index') }}" class="btn-outline-red" style="padding:12px 40px">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
/* ── Batch tag widget ──────────────────────────────────────────────────── */
var batches = new Set();

function renderTags() {
    var box = document.getElementById('batchTagBox');
    var input = document.getElementById('batchInput');
    // Remove old tags (keep only the input)
    Array.from(box.children).forEach(function(c){ if(c !== input) c.remove(); });

    batches.forEach(function(b) {
        var tag = document.createElement('span');
        tag.style.cssText = 'display:inline-flex;align-items:center;gap:4px;background:#e1f5ee;color:#0f6e56;border:1px solid #5dcaa5;border-radius:20px;padding:2px 10px;font-size:13px;font-weight:500';
        tag.innerHTML = 'Batch ' + b + ' <button type="button" onclick="removeBatch(' + b + ')" style="background:none;border:none;cursor:pointer;color:#0f6e56;font-size:15px;line-height:1;padding:0">×</button>';
        box.insertBefore(tag, input);
    });

    // Rebuild hidden inputs
    var hid = document.getElementById('batchHiddenInputs');
    hid.innerHTML = '';
    batches.forEach(function(b){
        var inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'entry_batches[]';
        inp.value = b;
        hid.appendChild(inp);
    });
}

function addBatch(val) {
    var n = parseInt(val);
    if (!isNaN(n) && n >= 1 && n <= 100) {
        batches.add(n);
        renderTags();
    }
}

function removeBatch(n) { batches.delete(n); renderTags(); }

function clearAllBatches() { batches.clear(); renderTags(); }

function addFromDropdown() {
    var dd = document.getElementById('batchDropdown');
    if (dd.value) { addBatch(dd.value); dd.value = ''; }
}

// Keyboard input
document.getElementById('batchInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        addBatch(this.value.trim());
        this.value = '';
    }
    if (e.key === 'Backspace' && this.value === '' && batches.size > 0) {
        var last = Array.from(batches).pop();
        removeBatch(last);
    }
});

// Restore old() values after validation failure
if (window._oldBatches && Array.isArray(window._oldBatches)) {
    window._oldBatches.forEach(function(b){ batches.add(parseInt(b)); });
    renderTags();
}

/* ── Date/time restrictions ───────────────────────────────────────────── */
(function(){
    var today = new Date().toISOString().split('T')[0];
    var startDate = document.getElementById('start_date');
    var endDate   = document.getElementById('end_date');
    var startTime = document.getElementById('start_time');
    var endTime   = document.getElementById('end_time');

    function nowHHMM(){ var n=new Date(); return n.getHours().toString().padStart(2,'0')+':'+n.getMinutes().toString().padStart(2,'0'); }

    startDate.addEventListener('change', function(){
        endDate.min = this.value || today;
        if (endDate.value && endDate.value < this.value) endDate.value = this.value;
        startTime.min = (this.value === today) ? nowHHMM() : '';
    });
    startTime.addEventListener('change', function(){
        if (startDate.value === endDate.value) endTime.min = this.value;
    });
    endDate.addEventListener('change', function(){
        endTime.min = (this.value === startDate.value) ? startTime.value : '';
    });
    if (startDate.value === today) startTime.min = nowHHMM();
})();
</script>
@endpush
@endsection