{{-- FILE: resources/views/admin/events/participants.blade.php --}}
@extends('layouts.admin')
@section('title','Participants - Admin')
@section('page-title','Event Participants')
@section('content')

<div style="margin-bottom:16px">
    <a href="{{ route('admin.events.index') }}" style="color:var(--text-muted);font-size:14px;text-decoration:none">← Back</a>
</div>

<div class="admin-table-wrap">
    <div style="padding:20px 24px 16px">
        <h2 style="font-size:22px;font-weight:700">{{ $event->title }} Participants</h2>
        <p style="font-size:14px;color:var(--text-muted)">{{ $event->start_date }} – {{ $event->end_date }}</p>
    </div>

    @if(session('success'))
        <div style="margin:0 24px 16px;padding:12px 16px;background:#e1f5ee;border:1px solid #5dcaa5;border-radius:8px;color:#0f6e56;font-size:14px">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.events.participants', $event->id) }}" style="display:flex;gap:10px;align-items:center">
            <input type="text" name="search" class="search-input" placeholder="Search" style="width:260px" value="{{ request('search') }}">
            <select name="status" onchange="this.form.submit()" style="font-size:13px;padding:8px 12px;border:1px solid var(--border);border-radius:8px;background:#fff">
                <option value="">All Statuses</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>
        <a href="{{ route('admin.events.participants.export', $event->id) }}" class="btn-outline-teal" style="font-size:13px;padding:8px 18px">⬆ Export</a>
    </div>

    <table class="admin-table" id="partTable">
        <thead>
            <tr>
                <th>Name <span class="sort-icon">⇅</span></th>
                <th>Email <span class="sort-icon">⇅</span></th>
                <th>Entry Batch <span class="sort-icon">⇅</span></th>
                <th>Phone Number <span class="sort-icon">⇅</span></th>
                <th>CCP No. <span class="sort-icon">⇅</span></th>
                <th>City <span class="sort-icon">⇅</span></th>
                <th>Status <span class="sort-icon">⇅</span></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $p)
            <tr>
                <td>{{ $p->alumniUser->full_name ?? 'N/A' }}</td>
                <td>{{ $p->alumniUser->email ?? 'N/A' }}</td>
                <td>{{ $p->alumniUser->entry ?? '-' }}</td>
                <td>{{ $p->alumniUser->phone_number ?? '-' }}</td>
                <td>{{ $p->alumniUser->ccp_no ?? '-' }}</td>
                <td>{{ $p->alumniUser->current_city ?? '-' }}</td>
                <td>
                    {{-- Inline status change form --}}
                    <form method="POST" action="{{ route('admin.events.participants.status', [$event->id, $p->id]) }}">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                            style="font-size:12px;padding:4px 8px;border-radius:20px;border:1px solid;cursor:pointer;font-weight:500;
                            {{ $p->status === 'confirmed' ? 'border-color:#5dcaa5;background:#e1f5ee;color:#0f6e56' :
                               ($p->status === 'pending'  ? 'border-color:#fac775;background:#faeeda;color:#854f0b' :
                                                            'border-color:#f09595;background:#fcebeb;color:#a32d2d') }}">
                            <option value="confirmed" {{ $p->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="pending"   {{ $p->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ $p->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </td>
                <td>
                    <div class="action-icons">
                        {{-- Cancel / restore registration --}}
                        @if($p->status !== 'cancelled')
                            <form method="POST"
                                  action="{{ route('admin.events.participants.cancel', [$event->id, $p->id]) }}"
                                  onsubmit="return confirm('Cancel this participant\'s registration?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-delete" title="Cancel registration"
                                        style="color:#e24b4a">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <form method="POST"
                                  action="{{ route('admin.events.participants.restore', [$event->id, $p->id]) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-view" title="Restore registration" style="color:#1d9e75">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted)">No participants yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="admin-table-footer">
        <div style="display:flex;align-items:center;gap:10px">
            <span style="font-size:13px;color:var(--text-muted)">Result per page</span>
            <select onchange="window.location='?per_page='+this.value+'&search={{ request('search') }}&status={{ request('status') }}'"
                    style="font-size:13px;padding:4px 8px;border:1px solid var(--border);border-radius:6px">
                @foreach([10,25,50,100] as $pp)
                    <option value="{{ $pp }}" {{ request('per_page', 10) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                @endforeach
            </select>
        </div>
        <div>{{ $participants->links('vendor.pagination.simple-default') }}</div>
        <div style="font-size:13px;color:var(--text-muted)">{{ $participants->firstItem() ?? 0 }}-{{ $participants->lastItem() ?? 0 }} of {{ $participants->total() }}</div>
    </div>
</div>
@endsection