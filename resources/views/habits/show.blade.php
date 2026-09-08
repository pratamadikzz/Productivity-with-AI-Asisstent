@extends('layouts.app')

@section('title', $habit->name)

@section('content')

    <div class="p-8">

        {{-- BACK --}}
        <a href="{{ route('habits.index') }}" class="text-sm text-slate-500 hover:text-slate-900">
            ← Back to Habits
        </a>


        {{-- HEADER --}}
        <div class="mt-6 flex items-start justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-2xl font-bold text-slate-900">
                        {{ $habit->name }}
                    </h1>

                    @if ($habit->is_active)
                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                            Active
                        </span>
                    @else
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                            Inactive
                        </span>
                    @endif

                </div>

                @if ($habit->description)
                    <p class="mt-2 max-w-2xl text-sm text-slate-500">
                        {{ $habit->description }}
                    </p>
                @endif

            </div>


            <a href="{{ route('habits.edit', $habit) }}"
                class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Edit
            </a>

        </div>

        {{-- TODAY CHECK-IN --}}
        <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6">

            @php
                $completedToday = $habit->logs->contains(fn($log) => $log->completed_date->isToday());
            @endphp

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm font-medium text-slate-500">
                        Today
                    </p>

                    <h2 class="mt-1 text-lg font-semibold text-slate-900">
                        {{ now()->format('l, d M Y') }}
                    </h2>

                    @if ($completedToday)
                        <p class="mt-1 text-sm text-green-600">
                            Kamu sudah menyelesaikan habit hari ini.
                        </p>
                    @else
                        <p class="mt-1 text-sm text-slate-500">
                            Jangan lupa lakukan habit kamu hari ini.
                        </p>
                    @endif

                </div>


                <form action="{{ route('habits.toggle', $habit) }}" method="POST">

                    @csrf

                    @if ($completedToday)
                        <button type="submit"
                            class="rounded-xl bg-green-100 px-5 py-3 text-sm font-semibold text-green-700 transition hover:bg-green-200">
                            ✓ Completed Today
                        </button>
                    @else
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                            Complete Today
                        </button>
                    @endif

                </form>

            </div>

        </div>


        {{-- STATS --}}
        <div class="mt-8 grid gap-4 md:grid-cols-4">

            {{-- CURRENT STREAK --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <p class="text-sm text-slate-500">
                    Current Streak
                </p>

                <div class="mt-2 flex items-end gap-2">

                    <p class="text-2xl font-bold text-slate-900">
                        {{ $currentStreak }}
                    </p>

                    <span class="mb-1 text-sm text-slate-500">
                        days
                    </span>

                </div>

                <p class="mt-1 text-xs text-orange-500">
                    🔥 Keep going!
                </p>

            </div>

            {{-- WEEKLY ACTIVITY --}}
            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6">

                <div class="mb-6">

                    <h2 class="font-semibold text-slate-900">
                        This Week
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Aktivitas habit minggu ini.
                    </p>

                </div>


                <div class="grid grid-cols-7 gap-3">

                    @foreach ($weeklyLogs as $day)
                        <div class="text-center">

                            {{-- DAY --}}
                            <p class="mb-2 text-xs font-medium text-slate-500">
                                {{ $day['date']->format('D') }}
                            </p>


                            {{-- STATUS --}}
                            @if ($day['completed'])
                                <div
                                    class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600">
                                    ✓
                                </div>
                            @else
                                <div
                                    class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-300">
                                    —
                                </div>
                            @endif


                            {{-- DATE --}}
                            <p class="mt-2 text-xs text-slate-400">
                                {{ $day['date']->format('d') }}
                            </p>

                        </div>
                    @endforeach

                </div>

            </div>


            {{-- HABIT CALENDAR --}}
            <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6">

                {{-- HEADER --}}
                <div class="mb-6 flex items-center justify-between">

                    <div>

                        <h2 class="font-semibold text-slate-900">
                            Habit Calendar
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Lihat konsistensi habit kamu.
                        </p>

                    </div>


                    <div class="flex items-center gap-2">

                        {{-- PREVIOUS MONTH --}}
                        <a href="{{ route('habits.show', [
                            'habit' => $habit,
                            'month' => $currentMonth->copy()->subMonth()->month,
                            'year' => $currentMonth->copy()->subMonth()->year,
                        ]) }}"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50"
                            title="Previous month">
                            ←
                        </a>


                        {{-- CURRENT MONTH --}}
                        <div class="min-w-[140px] text-center">

                            <p class="text-sm font-semibold text-slate-900">
                                {{ $currentMonth->format('F Y') }}
                            </p>

                        </div>


                        {{-- NEXT MONTH --}}
                        <a href="{{ route('habits.show', [
                            'habit' => $habit,
                            'month' => $currentMonth->copy()->addMonth()->month,
                            'year' => $currentMonth->copy()->addMonth()->year,
                        ]) }}"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-600 transition hover:bg-slate-50"
                            title="Next month">
                            →
                        </a>

                    </div>

                </div>


                {{-- DAY HEADER --}}
                <div class="mb-2 grid grid-cols-7 gap-2">

                    @foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                        <div class="py-2 text-center text-xs font-medium text-slate-400">
                            {{ $day }}
                        </div>
                    @endforeach

                </div>


                {{-- CALENDAR --}}
                <div class="grid grid-cols-7 gap-2">

                    @foreach ($calendarDays as $date)
                        @php

                            $isCurrentMonth = $date->month === $currentMonth->month;

                            $isToday = $date->isToday();

                            $completed = $habit->logs->contains(fn($log) => $log->completed_date->isSameDay($date));

                        @endphp


                        <div
                            class="
                    min-h-[72px]
                    rounded-xl
                    border
                    p-2
                    transition
                    {{ $isCurrentMonth ? 'border-slate-200 bg-white' : 'border-transparent bg-slate-50' }}
                    {{ $isToday ? 'ring-2 ring-indigo-500/30' : '' }}
                ">

                            {{-- DATE --}}
                            <div class="flex items-center justify-between">

                                <span
                                    class="
                            text-xs
                            font-medium
                            {{ $isCurrentMonth ? 'text-slate-600' : 'text-slate-300' }}
                        ">
                                    {{ $date->format('d') }}
                                </span>


                                @if ($isToday)
                                    <span class="text-[10px] font-semibold text-indigo-600">
                                        Today
                                    </span>
                                @endif

                            </div>


                            {{-- STATUS --}}
                            @if ($completed)
                                <div class="mt-3 flex justify-center">

                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-sm font-semibold text-green-600">
                                        ✓
                                    </div>

                                </div>
                            @else
                                <div class="mt-3 flex justify-center">

                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-sm text-slate-300">
                                        —
                                    </div>

                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>


                {{-- LEGEND --}}
                <div class="mt-6 flex items-center gap-5 text-xs text-slate-500">

                    <div class="flex items-center gap-2">

                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-green-600">
                            ✓
                        </span>

                        Completed

                    </div>


                    <div class="flex items-center gap-2">

                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-slate-300">
                            —
                        </span>

                        Not completed

                    </div>

                </div>

            </div>
            

            {{-- LONGEST STREAK --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <p class="text-sm text-slate-500">
                    Longest Streak
                </p>

                <div class="mt-2 flex items-end gap-2">

                    <p class="text-2xl font-bold text-slate-900">
                        {{ $longestStreak }}
                    </p>

                    <span class="mb-1 text-sm text-slate-500">
                        days
                    </span>

                </div>

                <p class="mt-1 text-xs text-slate-400">
                    Personal best
                </p>

            </div>


            {{-- COMPLETION --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <p class="text-sm text-slate-500">
                    This Week
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $completionRate }}%
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    Completion rate
                </p>

            </div>


            {{-- TOTAL --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5">

                <p class="text-sm text-slate-500">
                    Total Check-ins
                </p>

                <p class="mt-2 text-2xl font-bold text-slate-900">
                    {{ $habit->logs->count() }}
                </p>

                <p class="mt-1 text-xs text-slate-400">
                    All time
                </p>

            </div>

        </div>


        {{-- LOGS --}}
        <div class="mt-8 rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-100 px-6 py-5">

                <h2 class="font-semibold text-slate-900">
                    Check-in History
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Riwayat aktivitas habit kamu.
                </p>

            </div>


            <div>

                @forelse($habit->logs->sortByDesc('completed_date') as $log)
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 last:border-b-0">

                        <div class="flex items-center gap-3">

                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 text-green-600">
                                ✓
                            </div>

                            <span class="text-sm font-medium text-slate-700">
                                {{ $log->completed_date->format('d M Y') }}
                            </span>

                        </div>

                        <span class="text-xs text-slate-400">
                            Completed
                        </span>

                    </div>

                @empty

                    <div class="px-6 py-10 text-center">

                        <p class="text-sm text-slate-500">
                            Belum ada check-in.
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Fitur check-in akan tersedia di tahap berikutnya.
                        </p>

                    </div>
                @endforelse

            </div>

        </div>

    </div>

@endsection
