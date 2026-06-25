{{-- FILE: resources/views/customer/news/index.blade.php --}}
@extends('layouts.app')
@section('title','Latest News - POBA')
@section('content')

<section class="section-pad">
    <div class="container">

        {{-- Heading with orange underline matching text width --}}
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="display: inline-block; font-size: 2.2rem; font-weight: 700; color: #086666; border-bottom: 3px solid #e87722; padding-bottom: 8px; margin: 0;">
                Latest News
            </h2>
        </div>

        <div class="grid-4">
            @forelse($news as $item)
            <div class="card">
                <img class="card-img" src="{{ $item->image ? asset('storage/'.$item->image) : 'https://placehold.co/400x200/1a7a7a/fff?text=News' }}" alt="{{ $item->title }}">
                <div class="card-body">
                    <div class="card-type">{{ strtoupper($item->type ?? 'NEWS') }}</div>
                    <div class="card-title">{{ $item->title }}</div>
                    @if($loop->first)<div class="card-date">Posted On {{ $item->published_at ? $item->published_at->format('d-m-Y') : '' }}</div>@endif
                    <p class="card-text">{{ Str::limit(strip_tags($item->description), 100) }}</p>
                    <a href="{{ route('news.show', $item->id) }}" style="font-size:13px;color:var(--teal);font-weight:600;margin-top:8px;display:inline-block">Read More →</a>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-muted)">No news available yet.</div>
            @endforelse
        </div>

        <div style="text-align:center;margin-top:40px">
            {{ $news->links('vendor.pagination.simple-default') }}
        </div>
    </div>
</section>
@endsection