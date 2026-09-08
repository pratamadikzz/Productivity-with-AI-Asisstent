<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = auth()->user()
            ->events()
            ->latest('start_at')
            ->get();

        return view('events.index', compact('events'));
    }

    public function create(Request $request): View
    {
        $date = $request->query('date');

        return view('events.create', compact('date'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        auth()->user()
            ->events()
            ->create($validated);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dibuat');
    }

    public function show(Event $event): View
    {
        abort_unless(
            $event->user_id === auth()->id(),
            403
        );

        return view('events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        abort_unless(
            $event->user_id === auth()->id(),
            403
        );

        return view('events.edit', compact('event'));
    }

    public function update(
        Request $request,
        Event $event
    ): RedirectResponse {
        abort_unless(
            $event->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $event->update($validated);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil diperbarui');
    }

    public function destroy(Event $event): RedirectResponse
    {
        abort_unless(
            $event->user_id === auth()->id(),
            403
        );

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dihapus');
    }
}
