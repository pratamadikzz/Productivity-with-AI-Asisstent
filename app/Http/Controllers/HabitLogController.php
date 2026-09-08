<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\RedirectResponse;

class HabitLogController extends Controller
{
    public function toggle(Habit $habit): RedirectResponse
    {
        abort_unless(
            $habit->user_id === auth()->id(),
            403
        );

        $today = now()->toDateString();

        $log = $habit->logs()
            ->whereDate('completed_date', $today)
            ->first();

        if ($log) {
            $log->delete();

            return back()->with(
                'success',
                'Check-in hari ini dibatalkan'
            );
        }

        $habit->logs()->create([
            'completed_date' => $today,
        ]);

        return back()->with(
            'success',
            'Habit berhasil diselesaikan hari ini'
        );
    }
}