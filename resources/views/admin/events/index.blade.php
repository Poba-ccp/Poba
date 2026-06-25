{{-- FILE: resources/views/admin/events/index.blade.php --}}
@extends('layouts.admin')
@section('title','Events - Admin')
@section('page-title','Events')
@section('content')
<div class="admin-table-wrap">
    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.events.index') }}">
            <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}" style="width:260px">
        </form>
        <div style="display:flex;gap:10px">
            <a href="{{ route('admin.events.export') }}" class="btn-outline-teal" style="font-size:13px;padding:9px 20px">⬆ Export</a>
            <a href="{{ route('admin.events.create') }}" class="btn-teal" style="font-size:13px;padding:9px 20px">+ Add Event</a>
        </div>
    </div>

    @if(session('success'))
        <div style="margin:0 24px 16px;padding:12px 16px;background:#e1f5ee;border:1px solid #5dcaa5;border-radius:8px;color:#0f6e56;font-size:14px">
            ✓ {{ session('success') }}
        </div>
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                <th>Title <span class="sort-icon">⇅</span></th>
                <th>Location <span class="sort-icon">⇅</span></th>
                <th>Start Date <span class="sort-icon">⇅</span></th>
                <th>End Date <span class="sort-icon">⇅</span></th>
                <th>Start Time <span class="sort-icon">⇅</span></th>
                <th>End Time <span class="sort-icon">⇅</span></th>
                <th>Entry Batches <span class="sort-icon">⇅</span></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $ev)
            <tr>
                <td>{{ $ev->title }}</td>
                <td>{{ $ev->location }}</td>
                <td>{{ $ev->start_date }}</td>
                <td>{{ $ev->end_date }}</td>
                <td>{{ $ev->start_time }}</td>
                <td>{{ $ev->end_time ?? '-' }}</td>
                <td>{{ $ev->entry_batches ? implode(', ', $ev->entry_batches) : '-' }}</td>
                <td>
                    <div class="action-icons">
                        {{-- View Participants --}}
                        <a href="{{ route('admin.events.participants', $ev->id) }}" class="btn-view" title="Participants">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </a>
                        {{-- Edit --}}
                        <a href="{{ route('admin.events.edit', $ev->id) }}" class="btn-edit" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.events.destroy', $ev->id) }}" onsubmit="return confirm('Delete this event and all its participant registrations?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">No events found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="admin-table-footer">
        <div style="display:flex;align-items:center;gap:10px">
            <span style="font-size:13px;color:var(--text-muted)">Result per page</span>
            <select onchange="window.location='?per_page='+this.value+'&search={{ request('search') }}'" style="font-size:13px;padding:4px 8px;border:1px solid var(--border);border-radius:6px">
                @foreach([10,25,50,100] as $pp)
                    <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                @endforeach
            </select>
        </div>
        <div>{{ $events->links('vendor.pagination.simple-default') }}</div>
        <div style="font-size:13px;color:var(--text-muted)">{{ $events->firstItem() ?? 0 }}-{{ $events->lastItem() ?? 0 }} of {{ $events->total() }}</div>
    </div>
</div>
@endsection