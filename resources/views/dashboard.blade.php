@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="mb-8">

        <p class="text-sm text-slate-500">
            {{ now()->format('l, d F Y') }}
        </p>

        <h1 class="mt-1 text-3xl font-bold tracking-tight">
            Good morning, {{ auth()->user()->name }} 👋
        </h1>

        <p class="mt-2 text-slate-500">
            Here's what's happening with your productivity today.
        </p>

    </div>


    {{-- STATS --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Today's Tasks
            </p>

            <div class="mt-3 flex items-end justify-between">

                <p class="text-3xl font-bold">
                    0
                </p>

                <span class="text-sm text-slate-400">
                    / 0
                </span>

            </div>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Active Projects
            </p>

            <p class="mt-3 text-3xl font-bold">
                0
            </p>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Goals
            </p>

            <p class="mt-3 text-3xl font-bold">
                0
            </p>

        </div>


        <div class="rounded-2xl border border-slate-200 bg-white p-5">

            <p class="text-sm text-slate-500">
                Productivity
            </p>

            <p class="mt-3 text-3xl font-bold">
                0%
            </p>

        </div>

    </div>


    {{-- CONTENT --}}
    <div class="mt-6 grid gap-6 xl:grid-cols-3">

        {{-- TASKS --}}
        <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white">

            <div class="flex items-center justify-between border-b border-slate-200 p-5">

                <div>
                    <h2 class="font-semibold">
                        Today's Tasks
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        Stay focused on what matters.
                    </p>
                </div>

                <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                    + Add Task
                </button>

            </div>


            <div class="p-5">

                <div class="flex flex-col items-center justify-center py-16 text-center">

                    <div class="mb-4 text-4xl">
                        ✓
                    </div>

                    <h3 class="font-semibold">
                        No tasks yet
                    </h3>

                    <p class="mt-1 max-w-sm text-sm text-slate-400">
                        Add your first task and start organizing your day.
                    </p>

                </div>

            </div>

        </div>


        {{-- QUICK ACTIONS --}}
        <div class="rounded-2xl border border-slate-200 bg-white">

            <div class="border-b border-slate-200 p-5">

                <h2 class="font-semibold">
                    Quick Actions
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Quickly manage your workspace.
                </p>

            </div>


            <div class="space-y-2 p-5">

                <button class="w-full rounded-xl border border-slate-200 p-4 text-left hover:bg-slate-50">

                    <p class="font-medium">
                        + Create Task
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Add something you need to do.
                    </p>

                </button>


                <button class="w-full rounded-xl border border-slate-200 p-4 text-left hover:bg-slate-50">

                    <p class="font-medium">
                        + Create Project
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Start organizing a new project.
                    </p>

                </button>


                <button class="w-full rounded-xl border border-slate-200 p-4 text-left hover:bg-slate-50">

                    <p class="font-medium">
                        + Write Note
                    </p>

                    <p class="mt-1 text-xs text-slate-400">
                        Capture an idea or thought.
                    </p>

                </button>

            </div>

        </div>

    </div>

</div>

@endsection