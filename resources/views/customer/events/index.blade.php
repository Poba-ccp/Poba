@extends('layouts.app')
@section('title','Events - POBA')
@section('content')

<section class="section-pad" style="padding-top:40px">
    <div class="container">

        <div style="text-align:center;margin-bottom:40px">
            <h1 style="font-size:2.5rem;font-weight:700;color:#086666;display:inline-block;padding-bottom:8px;border-bottom:4px solid var(--orange);line-height:1.2">
                Events
            </h1>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div style="margin-bottom:20px;padding:12px 18px;background:#e1f5ee;border:1px solid #5dcaa5;border-radius:8px;color:#0f6e56;font-size:14px">
                ✓ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="margin-bottom:20px;padding:12px 18px;background:#fcebeb;border:1px solid #f09595;border-radius:8px;color:#a32d2d;font-size:14px">
                ✕ {{ session('error') }}
            </div>
        @endif

        {{-- Tabs --}}
        <div class="tab-btns">
            <button class="tab-btn active" id="btnUpcoming" data-tab="upcoming">Upcoming</button>
            <button class="tab-btn"         id="btnPrevious" data-tab="previous">Previous</button>
        </div>

        {{-- Upcoming Events Container --}}
        <div id="tabUpcoming">
            <div id="upcoming-events">
                @include('customer.events._event_cards', [
                    'events'          => $upcoming,
                    'myEventIds'      => $myEventIds,
                    'myParticipations'=> $myParticipations,
                    'isPrevious'      => false,
                ])
            </div>
            @if($upcoming->hasMorePages())
                <div class="load-more-wrapper" id="upcoming-load-more">
                    <button class="btn-teal load-more-btn" data-tab="upcoming" data-page="{{ $upcoming->currentPage() + 1 }}">
                        Load More
                    </button>
                </div>
            @endif
        </div>

        {{-- Previous Events Container --}}
        <div id="tabPrevious" style="display:none">
            <div id="previous-events">
                @include('customer.events._event_cards', [
                    'events'          => $previous,
                    'myEventIds'      => $myEventIds,
                    'myParticipations'=> $myParticipations,
                    'isPrevious'      => true,
                ])
            </div>
            @if($previous->hasMorePages())
                <div class="load-more-wrapper" id="previous-load-more">
                    <button class="btn-teal load-more-btn" data-tab="previous" data-page="{{ $previous->currentPage() + 1 }}">
                        Load More
                    </button>
                </div>
            @endif
        </div>

    </div>
</section>

@push('scripts')
<script>
    // ── Tab switching ──────────────────────────────────────────────
    function showTab(tab) {
        document.getElementById('tabUpcoming').style.display = tab === 'upcoming' ? 'block' : 'none';
        document.getElementById('tabPrevious').style.display = tab === 'previous' ? 'block' : 'none';
        document.getElementById('btnUpcoming').classList.toggle('active', tab === 'upcoming');
        document.getElementById('btnPrevious').classList.toggle('active', tab === 'previous');
    }

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            showTab(this.dataset.tab);
        });
    });

    // ── Toggle description (See More / See Less) ─────────────────
    function toggleDesc(id) {
        const desc = document.getElementById('desc-' + id);
        const link = document.getElementById('seeMore-' + id);
        if (desc.style.display === 'none') {
            desc.style.display = 'block';
            link.textContent = 'See Less';
        } else {
            desc.style.display = 'none';
            link.textContent = 'See More';
        }
    }

    // ── Load More (AJAX) ──────────────────────────────────────────
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('load-more-btn')) {
            const btn = e.target;
            const tab = btn.dataset.tab;
            const page = parseInt(btn.dataset.page);
            const containerId = tab === 'upcoming' ? 'upcoming-events' : 'previous-events';
            const wrapperId = tab === 'upcoming' ? 'upcoming-load-more' : 'previous-load-more';

            btn.disabled = true;
            btn.textContent = 'Loading...';

            fetch(`?tab=${tab}&${tab}_page=${page}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                // Append new cards to the container
                document.getElementById(containerId).insertAdjacentHTML('beforeend', data.html);

                // Update or remove the load-more button
                if (data.hasMore) {
                    btn.dataset.page = page + 1;
                    btn.disabled = false;
                    btn.textContent = 'Load More';
                } else {
                    document.getElementById(wrapperId).remove();
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.textContent = 'Load More';
                alert('Something went wrong. Please try again.');
            });
        }
    });
</script>
@endpush
@endsection