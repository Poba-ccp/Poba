@extends('layouts.admin')
@section('title', 'View News - Admin')
@section('page-title', 'Content Management')

@section('content')

@include('admin.cms._tabs', ['active' => 'news'])

<style>
    .news-show-wrap {
        max-width: 820px;
        margin: 0 auto;
    }
    .news-show-wrap .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--text-muted, #666);
        font-size: 14px;
        text-decoration: none;
        margin-bottom: 20px;
    }
    .news-show-wrap h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 6px;
        color: #1a1a2e;
    }
    .news-show-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        font-size: 14px;
        color: #777;
        margin-bottom: 24px;
    }
    .news-show-meta span {
        background: #f0f6f6;
        padding: 2px 14px;
        border-radius: 20px;
    }
    .news-show-img {
        width: 100%;
        max-height: 380px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 28px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    .news-show-body {
        font-size: 16px;
        line-height: 1.8;
        color: #333;
    }
    .news-show-body img {
        max-width: 100%;
        border-radius: 8px;
    }
    .news-show-actions {
        margin-top: 36px;
        display: flex;
        gap: 16px;
        justify-content: center;
        padding-bottom: 20px;
    }
    .btn-edit {
        background: #1a7a7a;
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 12px 44px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }
    .btn-edit:hover { background: #2a9a9a; }
    .btn-back {
        background: transparent;
        color: #555;
        border: 1.5px solid #ccc;
        border-radius: 50px;
        padding: 12px 44px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }
    .btn-back:hover { background: #f5f5f5; }
</style>

<div class="news-show-wrap">
    <a href="{{ route('admin.cms.news') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
        Back to News
    </a>

    <h1>{{ $item->title }}</h1>
    <div class="news-show-meta">
        <span>{{ $item->type ?? 'No type' }}</span>
        <span>Added: {{ $item->created_at ? $item->created_at->format('d M Y') : '—' }}</span>
        @if($item->updated_at && $item->updated_at->diffInDays($item->created_at) > 0)
            <span>Updated: {{ $item->updated_at->format('d M Y') }}</span>
        @endif
    </div>

    @if($item->image)
        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}" class="news-show-img">
    @endif

    <div class="news-show-body">
        {!! $item->description !!}
    </div>

    <div class="news-show-actions">
        <a href="{{ route('admin.cms.news.edit', $item->id) }}" class="btn-edit">Edit</a>
        <a href="{{ route('admin.cms.news') }}" class="btn-back">Back</a>
    </div>
</div>

@endsection