<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoalController extends Controller
{
    public function index(): View
    {
        $goals = auth()->user()
            ->goals()
            ->latest()
            ->get();

        return view('goals.index', compact('goals'));
    }

    public function create(): View
    {
        return view('goals.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_date' => ['nullable', 'date'],
        ]);

        auth()->user()
            ->goals()
            ->create($validated);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Goal berhasil dibuat');
    }

    public function show(Goal $goal): View
    {
        abort_unless(
            $goal->user_id === auth()->id(),
            403
        );

        $goal->load('milestones');

        return view('goals.show', compact('goal'));
    }

    public function edit(Goal $goal): View
    {
        abort_unless(
            $goal->user_id === auth()->id(),
            403
        );

        return view('goals.edit', compact('goal'));
    }

    public function update(
        Request $request,
        Goal $goal
    ): RedirectResponse {
        abort_unless(
            $goal->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_date' => ['nullable', 'date'],
            'status' => [
                'required',
                'in:active,completed,archived',
            ],
        ]);

        $goal->update($validated);

        return redirect()
            ->route('goals.index')
            ->with('success', 'Goal berhasil diperbarui');
    }

    public function destroy(Goal $goal): RedirectResponse
    {
        abort_unless(
            $goal->user_id === auth()->id(),
            403
        );

        $goal->delete();

        return redirect()
            ->route('goals.index')
            ->with('success', 'Goal berhasil dihapus');
    }
}