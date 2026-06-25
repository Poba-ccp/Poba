{{-- FILE: resources/views/customer/events/_event_cards.blade.php --}}
{{-- Reusable partial: renders a list of event cards.
     Variables expected:
       $events           – paginator
       $myEventIds       – array of event IDs the alumni is registered for
       $myParticipations – array of event_id => status
       $isPrevious       – bool: true when rendering previous events tab
--}}

@foreach($events as $event)

@php
    $isRegistered  = in_array($event->id, $myEventIds ?? []);
    $canRegister   = !$isPrevious && $event->registration_required;
    $isEligible    = true;
    $ineligibleMsg = '';

    if ($canRegister && !empty($event->entry_batches) && Auth::guard('alumni')->check()) {
        $alumniEntry = (int) Auth::guard('alumni')->user()->entry;
        if (!in_array($alumniEntry, $event->entry_batches)) {
            $isEligible    = false;
            $ineligibleMsg = 'Open to batches: ' . implode(', ', $event->entry_batches);
        }
    }

    $myStatus = ($myParticipations ?? [])[$event->id] ?? '';

    $startTime = $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : '';
    $endTime   = $event->end_time   ? \Carbon\Carbon::parse($event->end_time)->format('g:i A')   : '';
@endphp

<div class="event-card" id="event-{{ $event->id }}">

    {{-- Date block --}}
    <div class="event-date">
        <div class="day">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</div>
        <div class="month-year">{{ \Carbon\Carbon::parse($event->start_date)->format('M Y') }}</div>
    </div>

    {{-- Thumbnail --}}
    <img class="event-thumb"
         src="{{ $event->logo ? asset('storage/'.$event->logo) : 'https://placehold.co/100x80/1a7a7a/fff?text=Event' }}"
         alt="{{ $event->title }}">

    {{-- Info --}}
    <div class="event-info">
        <h4>{{ $event->title }}</h4>
        <div class="event-meta">
            <span>📍 {{ $event->location }}</span>
            <span>📅 {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}</span>
            <span>🕐 {{ $startTime }}{{ $endTime ? ' – ' . $endTime : '' }}</span>
        </div>
        <div class="event-focal">
            <strong>Focal Person</strong><br>
            {{ $event->focal_person_name }} – {{ $event->focal_person_number }}
        </div>

        {{-- Expandable details --}}
        <div class="event-desc" id="desc-{{ $event->id }}" style="display:none;margin-top:10px">
            @if(!empty($event->entry_batches))
                <p style="margin:0 0 6px">
                    <strong>Eligible Batches:</strong> {{ implode(', ', $event->entry_batches) }}
                </p>
            @else
                <p style="margin:0 0 6px"><strong>Eligible Batches:</strong> Open to all</p>
            @endif
            <p style="margin:0">{{ $event->description }}</p>
            @if($event->gallery_link)
                <a href="{{ $event->gallery_link }}" target="_blank"
                   style="font-size:13px;color:var(--teal);margin-top:6px;display:inline-block">
                   🖼 View Gallery →
                </a>
            @endif
        </div>

        <a href="#" onclick="toggleDesc('{{ $event->id }}'); return false;"
           style="font-size:13px;color:var(--orange);font-weight:600;margin-top:8px;display:inline-block"
           id="seeMore-{{ $event->id }}">See More</a>
    </div>

    {{-- Actions --}}
    <div class="event-actions">
        @if($isPrevious)
            {{-- Previous event: gallery link only --}}
            @if($event->gallery_link)
                <a href="{{ $event->gallery_link }}" target="_blank"
                   class="btn-outline-teal" style="font-size:13px;padding:8px 16px">View Gallery</a>
            @else
                <a href="{{ route('gallery.index') }}"
                   class="btn-outline-teal" style="font-size:13px;padding:8px 16px">View Gallery</a>
            @endif

        @elseif(!$canRegister)
            <span style="font-size:12px;color:var(--text-muted);font-style:italic;text-align:center;display:block">
                No registration needed
            </span>

        @elseif($isRegistered)
            <div style="text-align:center;margin-bottom:8px">
                @if($myStatus === 'confirmed')
                    <span style="display:inline-block;padding:3px 12px;border-radius:20px;background:#e1f5ee;color:#0f6e56;font-size:12px;font-weight:600;border:1px solid #5dcaa5">
                        ✓ Confirmed
                    </span>
                @else
                    <span style="display:inline-block;padding:3px 12px;border-radius:20px;background:#faeeda;color:#854f0b;font-size:12px;font-weight:600;border:1px solid #fac775">
                        ⏳ Pending
                    </span>
                @endif
            </div>
            <form method="POST" action="{{ route('events.cancel', $event->id) }}">@csrf
                <button type="submit" class="btn-outline-orange"
                        style="font-size:13px;padding:8px 16px"
                        onclick="return confirm('Cancel your registration for this event?')">
                    Cancel Registration
                </button>
            </form>

        @elseif(!Auth::guard('alumni')->check())
            <a href="{{ route('login') }}" class="btn-teal" style="font-size:13px;padding:8px 16px">
                Register Now
            </a>

        @elseif(!$isEligible)
            <div style="text-align:center">
                <span style="display:inline-block;padding:3px 12px;border-radius:20px;background:#fcebeb;color:#a32d2d;font-size:12px;font-weight:500;border:1px solid #f09595;margin-bottom:4px">
                    Not Eligible
                </span>
                <p style="font-size:11px;color:var(--text-muted);margin:0">{{ $ineligibleMsg }}</p>
            </div>

        @else
            <form method="POST" action="{{ route('events.register', $event->id) }}">@csrf
                <button type="submit" class="btn-teal" style="font-size:13px;padding:8px 16px">
                    Register Now
                </button>
            </form>
        @endif
    </div>

</div>
@endforeach