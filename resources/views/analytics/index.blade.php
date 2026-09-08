@extends('layouts.app')

@section('title', 'Analytics')

@section('content')

<div class="px-8 py-8">

```
{{-- =========================================================
    HEADER
========================================================== --}}
<div class="mb-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Analytics
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Lihat perkembangan produktivitas kamu.
            </p>
        </div>

        <form method="GET" action="{{ route('analytics.index') }}">

            <label class="mb-1 block text-xs font-medium text-slate-500">
                Period
            </label>

            <select
                name="days"
                onchange="this.form.submit()"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-0"
            >
                <option value="7" {{ $days === 7 ? 'selected' : '' }}>
                    Last 7 Days
                </option>

                <option value="30" {{ $days === 30 ? 'selected' : '' }}>
                    Last 30 Days
                </option>

                <option value="90" {{ $days === 90 ? 'selected' : '' }}>
                    Last 90 Days
                </option>
            </select>

        </form>

    </div>
</div>


{{-- =========================================================
    PRODUCTIVITY OVERVIEW
========================================================== --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

    {{-- Productivity Score --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Productivity Score
        </p>

        <div class="mt-3 flex items-end gap-2">

            <span class="text-4xl font-bold text-slate-900">
                {{ $productivityScore }}
            </span>

            <span class="mb-1 text-sm text-slate-400">
                / 100
            </span>

        </div>

        <p class="mt-2 text-sm font-medium text-slate-600">
            {{ $productivityLabel }}
        </p>

        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100">
            <div
                class="h-full rounded-full bg-slate-900 transition-all"
                style="width: {{ $productivityScore }}%"
            ></div>
        </div>

    </div>


    {{-- Task Completion --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Task Completion
        </p>

        <p class="mt-3 text-4xl font-bold text-slate-900">
            {{ $periodCompletionRate }}%
        </p>

        <p class="mt-2 text-sm text-slate-500">
            {{ $periodCompleted }} completed
            from {{ $periodCreated }} created
        </p>

    </div>


    {{-- Habit Consistency --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Habit Consistency
        </p>

        <p class="mt-3 text-4xl font-bold text-slate-900">
            {{ $overallHabitConsistency }}%
        </p>

        <p class="mt-2 text-sm text-slate-500">
            Best streak: {{ $bestHabitStreak }} days
        </p>

    </div>


    {{-- Overdue --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <p class="text-sm font-medium text-slate-500">
            Overdue Tasks
        </p>

        <p class="mt-3 text-4xl font-bold text-slate-900">
            {{ $overdueTasks }}
        </p>

        <p class="mt-2 text-sm text-slate-500">
            Tasks past their due date
        </p>

    </div>

</div>


{{-- =========================================================
    PRODUCTIVITY HEALTH
========================================================== --}}
@php
    $healthClasses = match ($productivityHealth) {
        'excellent' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'healthy' => 'border-blue-200 bg-blue-50 text-blue-700',
        'attention' => 'border-amber-200 bg-amber-50 text-amber-700',
        'critical' => 'border-red-200 bg-red-50 text-red-700',
        default => 'border-slate-200 bg-slate-50 text-slate-700',
    };

    $healthIcon = match ($productivityHealth) {
        'excellent' => '🟢',
        'healthy' => '🔵',
        'attention' => '🟡',
        'critical' => '🔴',
        default => '⚪',
    };
@endphp

<div class="mt-6 rounded-2xl border p-6 {{ $healthClasses }}">

    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <p class="text-sm font-medium opacity-80">
                Productivity Health
            </p>

            <div class="mt-2 flex items-center gap-2">

                <span class="text-xl">
                    {{ $healthIcon }}
                </span>

                <h2 class="text-2xl font-bold">
                    {{ $productivityHealthLabel }}
                </h2>

            </div>

            <p class="mt-2 text-sm opacity-80">
                Kondisi produktivitas berdasarkan aktivitas kamu
                dalam {{ $days }} hari terakhir.
            </p>

        </div>

        <div class="text-left sm:text-right">

            <div class="text-4xl font-bold">
                {{ $productivityScore }}
            </div>

            <div class="text-xs font-medium opacity-70">
                / 100
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    PERIOD COMPARISON
========================================================== --}}
@php
    $comparisonClasses = match ($comparisonDirection) {
        'up' => 'text-emerald-600 bg-emerald-50 border-emerald-200',
        'down' => 'text-red-600 bg-red-50 border-red-200',
        default => 'text-slate-600 bg-slate-50 border-slate-200',
    };

    $comparisonIcon = match ($comparisonDirection) {
        'up' => '↑',
        'down' => '↓',
        default => '→',
    };
@endphp

<div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">

        <div>

            <p class="text-sm font-medium text-slate-500">
                Period Comparison
            </p>

            <h2 class="mt-1 text-xl font-bold text-slate-900">
                {{ $comparisonLabel }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Dibandingkan dengan {{ $days }} hari sebelumnya.
            </p>

        </div>


        <div class="flex flex-wrap items-center gap-4">

            <div class="text-right">
                <p class="text-xs text-slate-400">
                    Current
                </p>

                <p class="text-2xl font-bold text-slate-900">
                    {{ $periodCompletionRate }}%
                </p>
            </div>


            <div class="text-2xl text-slate-300">
                →
            </div>


            <div class="text-right">
                <p class="text-xs text-slate-400">
                    Previous
                </p>

                <p class="text-2xl font-bold text-slate-900">
                    {{ $previousPeriodCompletionRate }}%
                </p>
            </div>


            <div class="rounded-xl border px-4 py-3 {{ $comparisonClasses }}">

                <div class="flex items-center gap-2">

                    <span class="text-xl font-bold">
                        {{ $comparisonIcon }}
                    </span>

                    <span class="font-bold">
                        {{ abs($completionRateChange) }}%
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    PRIORITY ALERTS
========================================================== --}}
@if (count($priorityAlerts) > 0)

    <div class="mt-8">

        <div class="mb-4">

            <h2 class="text-lg font-semibold text-slate-900">
                Priority Alerts
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Hal yang paling membutuhkan perhatian kamu saat ini.
            </p>

        </div>


        <div class="space-y-3">

            @foreach ($priorityAlerts as $alert)

                @php
                    $alertClasses = match ($alert['type']) {
                        'danger' => 'border-red-200 bg-red-50 text-red-700',
                        'warning' => 'border-amber-200 bg-amber-50 text-amber-700',
                        'info' => 'border-blue-200 bg-blue-50 text-blue-700',
                        default => 'border-slate-200 bg-slate-50 text-slate-700',
                    };

                    $alertIcon = match ($alert['type']) {
                        'danger' => '!',
                        'warning' => '!',
                        'info' => 'i',
                        default => 'i',
                    };
                @endphp


                <div class="rounded-2xl border p-5 {{ $alertClasses }}">

                    <div class="flex items-start gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/70 font-bold">
                            {{ $alertIcon }}
                        </div>

                        <div>

                            <h3 class="font-semibold">
                                {{ $alert['title'] }}
                            </h3>

                            <p class="mt-1 text-sm leading-6 opacity-80">
                                {{ $alert['message'] }}
                            </p>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endif


{{-- =========================================================
    PRODUCTIVITY INSIGHTS
========================================================== --}}
@if (count($insights) > 0)

    <div class="mt-8">

        <div class="mb-4">

            <h2 class="text-lg font-semibold text-slate-900">
                Productivity Insights
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Ringkasan otomatis berdasarkan aktivitas kamu.
            </p>

        </div>


        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">

            @foreach ($insights as $insight)

                @php
                    $iconClass = match ($insight['type']) {
                        'success' => 'bg-emerald-100 text-emerald-600',
                        'warning' => 'bg-amber-100 text-amber-600',
                        'info' => 'bg-blue-100 text-blue-600',
                        default => 'bg-slate-100 text-slate-600',
                    };
                @endphp


                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $iconClass }}">

                            @if ($insight['type'] === 'success')
                                ✓
                            @elseif ($insight['type'] === 'warning')
                                !
                            @else
                                i
                            @endif

                        </div>


                        <div class="min-w-0">

                            <h3 class="font-semibold text-slate-900">
                                {{ $insight['title'] }}
                            </h3>

                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                {{ $insight['message'] }}
                            </p>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endif


{{-- =========================================================
    PRODUCTIVITY TREND
========================================================== --}}
<div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="mb-6">

        <h2 class="text-lg font-semibold text-slate-900">
            Productivity Trend
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Task yang diselesaikan selama {{ $days }} hari terakhir.
        </p>

    </div>


    <div class="h-80">
        <canvas id="weeklyTaskChart"></canvas>
    </div>

</div>


{{-- =========================================================
    PROJECTS + GOALS
========================================================== --}}
<div class="mt-8 grid gap-6 lg:grid-cols-2">


    {{-- Projects --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-6">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h2 class="text-lg font-semibold text-slate-900">
                        Project Progress
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Progress penyelesaian task pada setiap project.
                    </p>
                </div>

                <span class="text-2xl font-bold text-slate-900">
                    {{ $projectProgress }}%
                </span>

            </div>

        </div>


        @if ($projects->count())

            <div class="space-y-6">

                @foreach ($projects as $project)

                    <div>

                        <div class="mb-2 flex items-center justify-between gap-4">

                            <div class="min-w-0">

                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ $project->name }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ $project->completed_tasks_count }}
                                    /
                                    {{ $project->tasks_count }}
                                    tasks completed
                                </p>

                            </div>

                            <span class="shrink-0 text-sm font-semibold text-slate-900">
                                {{ $project->completion_rate }}%
                            </span>

                        </div>


                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-slate-900 transition-all"
                                style="width: {{ $project->completion_rate }}%"
                            ></div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="rounded-xl bg-slate-50 px-4 py-8 text-center">

                <p class="text-sm text-slate-500">
                    Belum ada project.
                </p>

            </div>

        @endif

    </div>


    {{-- Goals --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-6">

            <div class="flex items-center justify-between gap-4">

                <div>

                    <h2 class="text-lg font-semibold text-slate-900">
                        Goal Progress
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Progress goal berdasarkan milestone.
                    </p>

                </div>

                <span class="text-2xl font-bold text-slate-900">
                    {{ $goalProgress }}%
                </span>

            </div>

        </div>


        @if ($goals->count())

            <div class="space-y-6">

                @foreach ($goals as $goal)

                    <div>

                        <div class="mb-2 flex items-center justify-between gap-4">

                            <div class="min-w-0">

                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ $goal->title }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ $goal->milestones->where('is_completed', true)->count() }}
                                    /
                                    {{ $goal->milestones->count() }}
                                    milestones completed
                                </p>

                            </div>

                            <span class="shrink-0 text-sm font-semibold text-slate-900">
                                {{ $goal->completion_rate }}%
                            </span>

                        </div>


                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">

                            <div
                                class="h-full rounded-full bg-slate-900 transition-all"
                                style="width: {{ $goal->completion_rate }}%"
                            ></div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="rounded-xl bg-slate-50 px-4 py-8 text-center">

                <p class="text-sm text-slate-500">
                    Belum ada goal.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
    HABITS
========================================================== --}}
<div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

    <div class="mb-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h2 class="text-lg font-semibold text-slate-900">
                    Habit Performance
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Konsistensi setiap habit selama 30 hari terakhir.
                </p>

            </div>

            <div class="text-left sm:text-right">

                <p class="text-xs text-slate-400">
                    Overall Consistency
                </p>

                <p class="text-2xl font-bold text-slate-900">
                    {{ $overallHabitConsistency }}%
                </p>

            </div>

        </div>

    </div>


    @if ($habits->count())

        <div class="space-y-6">

            @foreach ($habits as $habit)

                <div>

                    <div class="mb-2 flex items-center justify-between gap-4">

                        <div class="min-w-0">

                            <p class="truncate text-sm font-semibold text-slate-800">
                                {{ $habit->name }}
                            </p>

                            <p class="text-xs text-slate-500">
                                {{ $habit->analytics_completed_days }}
                                /
                                {{ $habit->analytics_expected_days }}
                                days completed
                            </p>

                        </div>


                        <div class="shrink-0 text-right">

                            <p class="text-sm font-semibold text-slate-900">
                                {{ $habit->analytics_consistency }}%
                            </p>

                            @if ($habit->analytics_current_streak > 0)

                                <p class="text-xs text-slate-500">
                                    🔥 {{ $habit->analytics_current_streak }} day streak
                                </p>

                            @endif

                        </div>

                    </div>


                    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">

                        <div
                            class="h-full rounded-full bg-slate-900 transition-all"
                            style="width: {{ $habit->analytics_consistency }}%"
                        ></div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="rounded-xl bg-slate-50 px-4 py-8 text-center">

            <p class="text-sm text-slate-500">
                Belum ada habit.
            </p>

        </div>

    @endif

</div>


{{-- =========================================================
    OVERALL SUMMARY
========================================================== --}}
<div class="mt-8">

    <div class="mb-4">

        <h2 class="text-lg font-semibold text-slate-900">
            Overall Summary
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Ringkasan seluruh data productivity system kamu.
        </p>

    </div>


    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">


        {{-- Tasks --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Tasks
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $totalTasks }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                {{ $completedTasks }} completed
            </p>

        </div>


        {{-- Projects --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Projects
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $totalProjects }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                {{ $activeProjects }} active
            </p>

        </div>


        {{-- Goals --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Goals
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $totalGoals }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                {{ $activeGoals }} active
            </p>

        </div>


        {{-- Habits --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Habits
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $totalHabits }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Active habits
            </p>

        </div>


        {{-- Knowledge Base --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <p class="text-sm font-medium text-slate-500">
                Knowledge Base
            </p>

            <p class="mt-2 text-3xl font-bold text-slate-900">
                {{ $totalNotes }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Active notes
            </p>

        </div>

    </div>

</div>
```

</div>

{{-- =========================================================
CHART
========================================================== --}}
@push('scripts')

```
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const dailyTaskData = @json($dailyTaskData);

    const labels = dailyTaskData.map(item => item.label);

    const completedData = dailyTaskData.map(
        item => item.completed
    );

    const ctx = document.getElementById('weeklyTaskChart');

    if (ctx) {

        new Chart(ctx, {

            type: 'bar',

            data: {
                labels: labels,

                datasets: [{
                    label: 'Completed Tasks',

                    data: completedData,

                    borderWidth: 1,

                    borderRadius: 8
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {
                            precision: 0
                        }

                    }

                }

            }

        });

    }

</script>
```

@endpush

@endsection
