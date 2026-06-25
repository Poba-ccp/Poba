{{-- FILE: resources/views/admin/cms/news.blade.php --}}
@extends('layouts.admin')
@section('title','CMS News - Admin')
@section('page-title','Content Management')
@section('content')

@include('admin.cms._tabs', ['active' => 'news'])



<div class="admin-table-wrap">
    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.cms.news') }}" style="display:flex;gap:10px;align-items:center">
            {{-- Single search icon — no duplicate --}}
            <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}" style="width:220px">

            <select name="type" class="filter-select">
                <option value="">Type</option>
                @foreach(['Para Inclusive Sailing','Conference','Public','News','Private'] as $t)
                    <option value="{{ $t }}" {{ request('type')==$t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-teal" style="font-size:13px;padding:8px 16px">Filter</button>
        </form>

        <div style="display:flex;gap:10px;align-items:center">
            <a href="{{ route('admin.cms.news.export') }}"
               style="display:flex;align-items:center;gap:6px;font-size:13px;padding:8px 16px;background:#fff;border:1px solid #ddd;border-radius:8px;color:#333;text-decoration:none">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#e07b3a" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export
            </a>
            <a href="{{ route('admin.cms.news.create') }}" class="btn-teal" style="font-size:13px;padding:9px 20px">+ Add News</a>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Title <span class="sort-icon">⇅</span></th>
                <th>Date Added <span class="sort-icon">⇅</span></th>
                <th>Type <span class="sort-icon">⇅</span></th>
                <th>Description <span class="sort-icon">⇅</span></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($news as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '—' }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ Str::limit(strip_tags($item->description), 120) }}</td>
                <td>
                    <div class="action-icons">
                        {{-- View: opens admin preview modal, never leaves admin panel --}}
                        <a href="{{ route('admin.cms.news.show', $item->id) }}" class="btn-view" title="View">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
        <circle cx="12" cy="12" r="3"/>
    </svg>
</a>

                        <a href="{{ route('admin.cms.news.edit', $item->id) }}" class="btn-edit" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('admin.cms.news.delete', $item->id) }}"
                              onsubmit="return confirm('Delete this news?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14H6L5 6"/>
                                    <path d="M10 11v6"/><path d="M14 11v6"/>
                                    <path d="M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted)">No news found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="admin-table-footer">
        <div>{{ $news->links('vendor.pagination.simple-default') }}</div>
        <div>{{ $news->firstItem() ?? 0 }}-{{ $news->lastItem() ?? 0 }} of {{ $news->total() }}</div>
    </div>
</div>


@endsection