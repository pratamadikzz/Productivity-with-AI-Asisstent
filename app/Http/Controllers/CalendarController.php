<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->integer('month', now()->month);
        $year = $request->integer('year', now()->year);

        $currentDate = now()->setYear($year)->setMonth($month)->startOfMonth();

        $startOfCalendar = $currentDate->copy()->startOfWeek();
        $endOfCalendar = $currentDate->copy()->endOfMonth()->endOfWeek();

        $tasks = auth()->user()
            ->tasks()
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [
                $startOfCalendar->toDateString(),
                $endOfCalendar->toDateString(),
            ])
            ->get();

        $events = auth()->user()
            ->events()
            ->whereBetween('start_at', [
                $startOfCalendar,
                $endOfCalendar->copy()->endOfDay(),
            ])
            ->get();

        return view('calendar.index', compact(
            'currentDate',
            'startOfCalendar',
            'endOfCalendar',
            'tasks',
            'events'
        ));
    }
}