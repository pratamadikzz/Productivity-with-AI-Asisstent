<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Milestone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    public function store(
        Request $request,
        Goal $goal
    ): RedirectResponse {
        abort_unless(
            $goal->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
        ]);

        $goal->milestones()->create($validated);

        // Update progress setelah milestone dibuat
        $goal->updateProgress();

        return back()->with(
            'success',
            'Milestone berhasil ditambahkan'
        );
    }

    public function updateStatus(
        Milestone $milestone
    ): RedirectResponse {
        abort_unless(
            $milestone->goal->user_id === auth()->id(),
            403
        );

        $goal = $milestone->goal;

        $milestone->update([
            'is_completed' => ! $milestone->is_completed,
        ]);

        // Update progress setelah status berubah
        $goal->updateProgress();

        return back()->with(
            'success',
            'Status milestone berhasil diperbarui'
        );
    }

    public function destroy(
        Milestone $milestone
    ): RedirectResponse {
        abort_unless(
            $milestone->goal->user_id === auth()->id(),
            403
        );

        $goal = $milestone->goal;

        $milestone->delete();

        // Update progress setelah milestone dihapus
        $goal->updateProgress();

        return back()->with(
            'success',
            'Milestone berhasil dihapus'
        );
    }
}