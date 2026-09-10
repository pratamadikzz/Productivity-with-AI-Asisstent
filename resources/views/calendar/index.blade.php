@extends('layouts.app')

@section('title', 'Calendar')

@section('content')

    <div class="module-page calendar-page">

        {{-- HEADER --}}
        <div class="mb-8 flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Calendar
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Lihat task dan event berdasarkan jadwal.
                </p>
            </div>

            <div class="flex items-center gap-2">

                <a href="{{ route('events.create') }}"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    + New Event
                </a>

            </div>

        </div>


        {{-- CALENDAR HEADER --}}
        <div class="mb-4 flex items-center justify-between">

            <div>
                <h2 class="text-xl font-semibold text-slate-900">
                    {{ $currentDate->format('F Y') }}
                </h2>
            </div>

            <div class="flex items-center gap-2">

                {{-- PREVIOUS --}}
                <a href="{{ route('calendar.index', [
                    'month' => $currentDate->copy()->subMonth()->month,
                    'year' => $currentDate->copy()->subMonth()->year,
                ]) }}"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm hover:bg-slate-50">
                    ←
                </a>

                {{-- TODAY --}}
                <a href="{{ route('calendar.index') }}"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium hover:bg-slate-50">
                    Today
                </a>

                {{-- NEXT --}}
                <a href="{{ route('calendar.index', [
                    'month' => $currentDate->copy()->addMonth()->month,
                    'year' => $currentDate->copy()->addMonth()->year,
                ]) }}"
                    class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm hover:bg-slate-50">
                    →
                </a>

            </div>

        </div>


        {{-- CALENDAR --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

            {{-- DAYS --}}
            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50">

                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                    <div class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                        {{ $day }}
                    </div>
                @endforeach

            </div>


            {{-- DATES --}}
            <div class="grid grid-cols-7">

                @php
                    $date = $startOfCalendar->copy();
                @endphp

                @while ($date <= $endOfCalendar)

                    @php
                        $dateString = $date->toDateString();

                        $dayTasks = $tasks->filter(fn($task) => $task->due_date->toDateString() === $dateString);

                        $dayEvents = $events->filter(fn($event) => $event->start_at->toDateString() === $dateString);

                        $isCurrentMonth = $date->month === $currentDate->month;
                        $isToday = $date->isToday();
                    @endphp


                    <div
                        class="group min-h-[150px] border-b border-r border-slate-100 p-3
    {{ $isCurrentMonth ? 'bg-white' : 'bg-slate-50' }}">


                        {{-- DATE HEADER --}}
                        <div class="mb-3 flex items-center justify-between">

                            <button type="button"
                                onclick="window.location.href='{{ route('events.create', [
                                    'date' => $date->format('Y-m-d'),
                                ]) }}'"
                                class="text-xs text-slate-400 opacity-0 transition group-hover:opacity-100 hover:text-indigo-600"
                                title="Add event">
                                +
                            </button>

                            <span
                                class="
            flex h-7 w-7 items-center justify-center rounded-full text-xs font-medium

            {{ $isToday ? 'bg-indigo-600 text-white' : ($isCurrentMonth ? 'text-slate-700' : 'text-slate-400') }}
        ">
                                {{ $date->day }}
                            </span>

                        </div>


                        {{-- TASKS --}}
                        <div class="space-y-1">

                            @foreach ($dayTasks as $task)
                                <a href="{{ route('tasks.edit', $task) }}"
                                    class="block truncate rounded-md bg-blue-50 px-2 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-100">
                                    🔵 {{ $task->title }}
                                </a>
                            @endforeach


                            {{-- EVENTS --}}
                            @foreach ($dayEvents as $event)
                                <a href="{{ route('events.show', $event) }}"
                                    class="block truncate rounded-md bg-purple-50 px-2 py-1.5 text-xs font-medium text-purple-700 hover:bg-purple-100">
                                    🟣 {{ $event->title }}
                                </a>
                            @endforeach

                        </div>

                    </div>


                    @php
                        $date->addDay();
                    @endphp

                @endwhile

            </div>

        </div>


        {{-- LEGEND --}}
        <div class="mt-4 flex items-center gap-5 text-sm text-slate-500">

            <div class="flex items-center gap-2">
                <span>🔵</span>
                <span>Task</span>
            </div>

            <div class="flex items-center gap-2">
                <span>🟣</span>
                <span>Event</span>
            </div>

        </div>

    </div>

@endsection
