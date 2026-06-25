<?php
// FILE: app/Http/Controllers/Admin/EventController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // ── Events CRUD ───────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Event::query();
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%");
            });
        }
        $perPage = (int) $request->get('per_page', 10);
        $events  = $query->orderByDesc('start_date')->paginate($perPage)->withQueryString();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'start_date'          => 'required|date|after_or_equal:today',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'start_time'          => 'required',
            'location'            => 'required|string|max:255',
            'entry_batches'       => 'nullable|array',
            'entry_batches.*'     => 'integer|min:1|max:100',
            'gallery_link'        => 'nullable|url|max:500',
            'logo'                => 'nullable|image|max:2048',
            'registration_required' => 'required|in:0,1',
        ]);

        $data = $request->except(['_token', 'logo']);
        $data['registration_required'] = $request->boolean('registration_required');
        $data['is_upcoming']           = strtotime($request->start_date) >= strtotime(today());
        // entry_batches comes in as array of integers from entry_batches[]
        $data['entry_batches'] = $request->filled('entry_batches') ? array_map('intval', $request->entry_batches) : null;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('events', 'public');
        }

        Event::create($data);
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',
            'start_time'          => 'required',
            'location'            => 'required|string|max:255',
            'entry_batches'       => 'nullable|array',
            'entry_batches.*'     => 'integer|min:1|max:100',
            'gallery_link'        => 'nullable|url|max:500',
            'logo'                => 'nullable|image|max:2048',
            'registration_required' => 'required|in:0,1',
        ]);

        $data = $request->except(['_token', '_method', 'logo']);
        $data['registration_required'] = $request->boolean('registration_required');
        $data['is_upcoming']           = strtotime($request->start_date) >= strtotime(today());
        $data['entry_batches'] = $request->filled('entry_batches') ? array_map('intval', $request->entry_batches) : null;

        if ($request->hasFile('logo')) {
            if ($event->logo) Storage::disk('public')->delete($event->logo);
            $data['logo'] = $request->file('logo')->store('events', 'public');
        }

        $event->update($data);
        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        if ($event->logo) Storage::disk('public')->delete($event->logo);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    public function export(Request $request)
    {
        $events  = Event::orderByDesc('start_date')->get();
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="events.csv"'];
        return response()->stream(function () use ($events) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Title', 'Location', 'Start Date', 'End Date', 'Start Time', 'End Time', 'Entry Batches', 'Registration Required']);
            foreach ($events as $ev) {
                fputcsv($h, [
                    $ev->title, $ev->location, $ev->start_date, $ev->end_date,
                    $ev->start_time, $ev->end_time ?? '',
                    $ev->entry_batches ? implode(', ', $ev->entry_batches) : 'Open to all',
                    $ev->registration_required ? 'Yes' : 'No',
                ]);
            }
            fclose($h);
        }, 200, $headers);
    }

    // ── Participants ──────────────────────────────────────────────────────────

    public function participants(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $query = EventParticipant::with('alumniUser')->where('event_id', $id);

        if ($request->search) {
            $query->whereHas('alumniUser', function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('ccp_no', 'like', "%{$request->search}%");
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $perPage      = (int) $request->get('per_page', 10);
        $participants = $query->paginate($perPage)->withQueryString();
        return view('admin.events.participants', compact('event', 'participants'));
    }

    /** Change a participant's status (confirmed / pending / cancelled). */
    public function updateParticipantStatus(Request $request, $eventId, $pId)
    {
        $request->validate(['status' => 'required|in:confirmed,pending,cancelled']);
        EventParticipant::where('event_id', $eventId)->findOrFail($pId)
            ->update(['status' => $request->status]);
        return back()->with('success', 'Status updated to ' . ucfirst($request->status) . '.');
    }

    /** Cancel a participant's registration. */
    public function cancelParticipant($eventId, $pId)
    {
        EventParticipant::where('event_id', $eventId)->findOrFail($pId)->update(['status' => 'cancelled']);
        return back()->with('success', 'Registration cancelled.');
    }

    /** Restore a cancelled registration (back to pending). */
    public function restoreParticipant($eventId, $pId)
    {
        EventParticipant::where('event_id', $eventId)->findOrFail($pId)->update(['status' => 'pending']);
        return back()->with('success', 'Registration restored and set to pending.');
    }

    /** Export participants as CSV. */
    public function exportParticipants($id)
    {
        $event        = Event::findOrFail($id);
        $participants = EventParticipant::with('alumniUser')->where('event_id', $id)->get();
        $filename     = str_slug($event->title) . '-participants.csv';
        $headers      = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];
        return response()->stream(function () use ($participants) {
            $h = fopen('php://output', 'w');
            fputcsv($h, ['Name', 'Email', 'Entry Batch', 'Phone Number', 'CCP No.', 'City', 'Status']);
            foreach ($participants as $p) {
                fputcsv($h, [
                    $p->alumniUser->full_name    ?? '',
                    $p->alumniUser->email        ?? '',
                    $p->alumniUser->entry        ?? '',
                    $p->alumniUser->phone_number ?? '',
                    $p->alumniUser->ccp_no       ?? '',
                    $p->alumniUser->current_city ?? '',
                    ucfirst($p->status),
                ]);
            }
            fclose($h);
        }, 200, $headers);
    }
}