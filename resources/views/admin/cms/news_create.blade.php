{{-- FILE: resources/views/admin/cms/news_create.blade.php --}}
@extends('layouts.admin')
@section('title','Add News - Admin')
@section('page-title','Content Management')

@push('styles')
{{-- Tiptap / Quill CDN --}}
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .news-create-wrap {
        max-width: 960px;
    }
    .news-create-wrap .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--text-muted, #666);
        font-size: 14px;
        text-decoration: none;
        margin-bottom: 18px;
    }
    .news-create-wrap h2 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 28px;
        color: #1a1a2e;
    }

    /* 3-column top row */
    .news-top-row {
        display: grid;
        grid-template-columns: 180px 1fr 1fr;
        gap: 20px;
        align-items: start;
        margin-bottom: 24px;
    }

    /* Image upload box */
    .news-image-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #e8f4f4;
        border: 1.5px solid #c0dede;
        border-radius: 10px;
        padding: 14px 18px;
        cursor: pointer;
        font-size: 14px;
        color: #3a8a8a;
        font-weight: 500;
        white-space: nowrap;
        transition: background .15s;
    }
    .news-image-btn:hover { background: #d4ecec; }
    .news-image-btn svg { flex-shrink: 0; }

    /* Inputs */
    .news-field-label {
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 6px;
        display: block;
    }
    .news-input {
        width: 100%;
        background: #e8f4f4;
        border: 1.5px solid #c0dede;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 14px;
        color: #333;
        outline: none;
        box-sizing: border-box;
        transition: border-color .15s;
    }
    .news-input:focus { border-color: #1a8a8a; }

    /* Description label */
    .news-desc-label {
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
        display: block;
    }

    /* Quill editor styling */
    .ql-toolbar.ql-snow {
        background: #1a6e6e;
        border-radius: 10px 10px 0 0;
        border: none;
        padding: 10px 12px;
    }
    .ql-toolbar.ql-snow .ql-stroke { stroke: #fff; }
    .ql-toolbar.ql-snow .ql-fill  { fill:  #fff; }
    .ql-toolbar.ql-snow .ql-picker-label { color: #fff; }
    .ql-toolbar.ql-snow .ql-picker-label::before { color: #fff; }
    .ql-toolbar.ql-snow button:hover .ql-stroke,
    .ql-toolbar.ql-snow button.ql-active .ql-stroke { stroke: #a8e6e6; }
    .ql-toolbar.ql-snow .ql-picker-options { background: #1a6e6e; color: #fff; }

    .ql-container.ql-snow {
        background: #e8f4f4;
        border: 1.5px solid #c0dede;
        border-top: none;
        border-radius: 0 0 10px 10px;
        min-height: 180px;
        font-size: 14px;
        color: #333;
    }
    .ql-editor { min-height: 160px; }
    .ql-editor.ql-blank::before { color: #999; font-style: normal; }

    /* Footer buttons */
    .news-form-actions {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 32px;
        padding-bottom: 20px;
    }
    .btn-save {
        background: #1a7a7a;
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 14px 60px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-save:hover { background: #155f5f; }
    .btn-cancel-outline {
        background: transparent;
        color: #e05a5a;
        border: 2px solid #e05a5a;
        border-radius: 50px;
        padding: 14px 60px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background .15s;
        display: inline-block;
    }
    .btn-cancel-outline:hover { background: #fdf0f0; }

    /* Preview image thumbnail */
    #imgPreview {
        display: none;
        height: 48px;
        border-radius: 6px;
        margin-top: 8px;
    }
    #imgFileName {
        font-size: 12px;
        color: #1a8a8a;
        margin-top: 4px;
    }
</style>
@endpush

@section('content')

@include('admin.cms._tabs', ['active' => 'news'])

<div class="news-create-wrap">
    <a href="{{ route('admin.cms.news') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    </a>

    <h2>Add News</h2>

    <form method="POST" action="{{ route('admin.cms.news.store') }}" enctype="multipart/form-data" id="newsForm">
        @csrf

        {{-- Hidden textarea that Quill writes into --}}
        <textarea name="description" id="descriptionInput" style="display:none"></textarea>

        {{-- TOP ROW: Image | Type | Title 1 --}}
        <div class="news-top-row">
            {{-- Image --}}
            <div>
                <span class="news-field-label">Image:</span>
                <button type="button" class="news-image-btn" onclick="document.getElementById('newsImgInput').click()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Image
                </button>
                <input type="file" id="newsImgInput" name="image" accept="image/*" style="display:none"
                       onchange="previewImg(this)">
                <img id="imgPreview" alt="Preview">
                <p id="imgFileName"></p>
            </div>

            {{-- Type --}}
            <div>
                <span class="news-field-label">Type:</span>
                <input list="typeOptions" name="type" class="news-input" placeholder="Select or type type" value="{{ old('type') }}">
<datalist id="typeOptions">
    <option value="Para Inclusive Sailing">
    <option value="Conference">
    <option value="Public">
    <option value="News">
    <option value="Private">
</datalist>
            </div>

            {{-- Title --}}
            <div>
                <span class="news-field-label">Title 1:</span>
                <input type="text" name="title" class="news-input" placeholder="Executive Committee" value="{{ old('title') }}" required>
            </div>
        </div>

        {{-- DESCRIPTION --}}
        <div>
            <span class="news-desc-label">Description:</span>
            <div id="quillEditor">{{ old('description') }}</div>
        </div>

        {{-- ACTIONS --}}
        <div class="news-form-actions">
            <button type="submit" class="btn-save">Save</button>
            <a href="{{ route('admin.cms.news') }}" class="btn-cancel-outline">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    // Quill rich text editor
    const quill = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Write news content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                [{ 'align': [] }],
                [{ 'color': [] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['code', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image', 'blockquote', 'hr'],
            ]
        }
    });

    // On submit, copy Quill HTML into the hidden textarea
    document.getElementById('newsForm').addEventListener('submit', function () {
        document.getElementById('descriptionInput').value = quill.root.innerHTML;
    });

    // Image preview
    function previewImg(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('imgPreview');
                img.src = e.target.result;
                img.style.display = 'block';
                document.getElementById('imgFileName').textContent = '✓ ' + file.name;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush

@endsection