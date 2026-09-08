<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HabitController extends Controller
{
    public function index(): View
    {
        $habits = auth()->user()
            ->habits()
            ->latest()
            ->get();

        return view('habits.index', compact('habits'));
    }

    public function create(): View
    {
        return view('habits.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'frequency' => ['required', 'in:daily,weekly'],
            'target_per_week' => [
                'required',
                'integer',
                'min:1',
                'max:7',
            ],
        ]);

        auth()->user()
            ->habits()
            ->create($validated);

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil dibuat');
    }

    public function show(
        Request $request,
        Habit $habit
    ): View {
        abort_unless(
            $habit->user_id === auth()->id(),
            403
        );

        $habit->load('logs');

        $currentStreak = $habit->getCurrentStreak();

        $longestStreak = $habit->getLongestStreak();

        $completionRate = $habit->getCompletionRate();

        $weeklyLogs = $habit->getWeeklyLogs();

        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $currentMonth = now()
            ->setYear($year)
            ->setMonth($month)
            ->startOfMonth();

        $startOfCalendar = $currentMonth
            ->copy()
            ->startOfWeek();

        $endOfCalendar = $currentMonth
            ->copy()
            ->endOfMonth()
            ->endOfWeek();

        $calendarDays = collect();

        $date = $startOfCalendar->copy();

        while ($date <= $endOfCalendar) {

            $calendarDays->push($date->copy());

            $date->addDay();
        }

        return view('habits.show', compact(
            'habit',
            'currentStreak',
            'longestStreak',
            'completionRate',
            'weeklyLogs',
            'currentMonth',
            'calendarDays'
        ));
    }

    public function edit(Habit $habit): View
    {
        abort_unless(
            $habit->user_id === auth()->id(),
            403
        );

        return view('habits.edit', compact('habit'));
    }

    public function update(
        Request $request,
        Habit $habit
    ): RedirectResponse {
        abort_unless(
            $habit->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'frequency' => ['required', 'in:daily,weekly'],
            'target_per_week' => [
                'required',
                'integer',
                'min:1',
                'max:7',
            ],
        ]);

        $habit->update($validated);

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil diperbarui');
    }

    public function destroy(Habit $habit): RedirectResponse
    {
        abort_unless(
            $habit->user_id === auth()->id(),
            403
        );

        $habit->delete();

        return redirect()
            ->route('habits.index')
            ->with('success', 'Habit berhasil dihapus');
    }
}
