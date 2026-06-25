<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    const PER_PAGE = 5;

    public function index(Request $request)
    {
        $now = Carbon::now();

        // ── Upcoming: event still ongoing or not yet ended ──
        //    end_date > today OR (end_date == today AND end_time >= now)
        $upcomingQuery = Event::where(function ($q) use ($now) {
            $q->where('end_date', '>', $now->toDateString())
              ->orWhere(function ($q2) use ($now) {
                  $q2->where('end_date', '=', $now->toDateString())
                     ->where('end_time', '>=', $now->toTimeString());
              });
        })->orderBy('start_date');

        // ── Previous: event has already ended ──
        //    end_date < today OR (end_date == today AND end_time < now)
        $previousQuery = Event::where(function ($q) use ($now) {
            $q->where('end_date', '<', $now->toDateString())
              ->orWhere(function ($q2) use ($now) {
                  $q2->where('end_date', '=', $now->toDateString())
                     ->where('end_time', '<', $now->toTimeString());
              });
        })->orderByDesc('start_date');

        // If end_time is null, we treat it as 23:59:59 so the event remains
        // "upcoming" until the end of that day. The above queries handle that
        // because null will be considered less than any time string, so
        // it will fall into previous only if end_date < today.

        $upcoming = $upcomingQuery->paginate(self::PER_PAGE, ['*'], 'upcoming_page');
        $previous = $previousQuery->paginate(self::PER_PAGE, ['*'], 'previous_page');

        $myEventIds       = [];
        $myParticipations = [];

        if (Auth::guard('alumni')->check()) {
            $rows = EventParticipant::where('alumni_user_id', Auth::guard('alumni')->id())
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->get(['event_id', 'status']);

            foreach ($rows as $r) {
                $myEventIds[] = $r->event_id;
                $myParticipations[$r->event_id] = $r->status;
            }
        }

        // AJAX load‑more
        if ($request->ajax()) {
            $tab = $request->get('tab', 'upcoming');
            $events = $tab === 'upcoming' ? $upcoming : $previous;
            $isPrevious = $tab === 'previous';

            return response()->json([
                'html'    => view('customer.events._event_cards', compact(
                    'events', 'myEventIds', 'myParticipations', 'isPrevious'
                ))->render(),
                'hasMore' => $events->hasMorePages(),
            ]);
        }

        return view('customer.events.index', compact(
            'upcoming', 'previous',
            'myEventIds', 'myParticipations'
        ));
    }

    // ── Register & Cancel methods (unchanged) ──

    public function register($id)
    {
        $alumni = Auth::guard('alumni')->user();
        $event  = Event::findOrFail($id);

        if (!$event->registration_required) {
            return back()->with('error', 'This event does not require registration.');
        }

        // Block registration if event has already ended
        $now = Carbon::now();
        $endDateTime = $event->end_date . ' ' . ($event->end_time ?? '23:59:59');
        if (Carbon::parse($endDateTime)->lt($now)) {
            return back()->with('error', 'Registration is closed for past events.');
        }

        if (!empty($event->entry_batches)) {
            $alumniEntry = (int) $alumni->entry;
            if (!in_array($alumniEntry, $event->entry_batches)) {
                return back()->with('error',
                    'You are not eligible for this event. It is open to batches: '
                    . implode(', ', $event->entry_batches) . '.'
                );
            }
        }

        $existing = EventParticipant::where('event_id', $id)
                    ->where('alumni_user_id', $alumni->id)
                    ->first();

        if ($existing) {
            if ($existing->status !== 'cancelled') {
                return back()->with('error', 'You are already registered for this event.');
            }
            $existing->update(['status' => 'pending']);
        } else {
            EventParticipant::create([
                'event_id'       => $id,
                'alumni_user_id' => $alumni->id,
                'status'         => 'pending',
            ]);
        }

        return back()->with('success', 'Registered successfully! Your status is pending confirmation.');
    }

    public function cancel($id)
    {
        $alumniId = Auth::guard('alumni')->id();

        $participant = EventParticipant::where('event_id', $id)
                        ->where('alumni_user_id', $alumniId)
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->first();

        if (!$participant) {
            return back()->with('error', 'No active registration found for this event.');
        }

        $participant->update(['status' => 'cancelled']);
        return back()->with('success', 'Your registration has been cancelled.');
    }
}