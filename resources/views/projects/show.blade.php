@extends('layouts.app')

@section('title', $project->name)

@section('content')

    <div class="p-8">

        <div class="mx-auto max-w-7xl">

            {{-- Back --}}
            <a href="{{ route('projects.index') }}" class="text-sm text-slate-500 hover:text-slate-900">
                ← Back to Projects
            </a>


            {{-- Project Header --}}
            <div class="mt-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                <div class="flex flex-col justify-between gap-6 md:flex-row md:items-start">

                    <div>

                        <div class="mb-3 flex items-center gap-3">

                            <h1 class="text-2xl font-bold text-slate-900">
                                {{ $project->name }}
                            </h1>

                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium capitalize text-slate-600">
                                {{ str_replace('_', ' ', $project->status) }}
                            </span>

                        </div>

                        <p class="max-w-3xl text-sm leading-relaxed text-slate-500">
                            {{ $project->description ?: 'Tidak ada deskripsi.' }}
                        </p>

                    </div>


                    <a href="{{ route('projects.edit', $project) }}"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                        Edit Project
                    </a>

                </div>


                {{-- Project Info --}}
                <div class="mt-6 grid gap-4 border-t border-slate-100 pt-6 sm:grid-cols-3">

                    <div>
                        <p class="text-xs text-slate-400">
                            Start Date
                        </p>

                        <p class="mt-1 text-sm font-medium text-slate-700">
                            {{ $project->start_date?->format('d M Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400">
                            Deadline
                        </p>

                        <p class="mt-1 text-sm font-medium text-slate-700">
                            {{ $project->deadline?->format('d M Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400">
                            Total Tasks
                        </p>

                        <p class="mt-1 text-sm font-medium text-slate-700">
                            {{ $project->tasks->count() }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Tasks --}}
            <div class="mt-8">

                <div class="mb-4 flex items-center justify-between">

                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">
                            Project Tasks
                        </h2>

                        <p class="text-sm text-slate-500">
                            Task yang berkaitan dengan project ini.
                        </p>
                    </div>

                    <a href="{{ route('projects.tasks.create', $project) }}"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        + Add Task
                    </a>

                </div>


                @if ($project->tasks->count())

                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                        <div class="divide-y divide-slate-100">

                            @foreach ($project->tasks as $task)
                                <div class="flex items-center justify-between gap-4 p-5">

                                    <div>

                                        <h3 class="font-medium text-slate-900">
                                            {{ $task->title }}
                                        </h3>

                                        <p class="mt-1 text-xs capitalize text-slate-500">
                                            {{ str_replace('_', ' ', $task->status) }}
                                            ·
                                            {{ $task->priority }} priority
                                        </p>

                                    </div>

                                    <span class="text-xs text-slate-400">
                                        {{ $task->due_date?->format('d M Y') ?? 'No deadline' }}
                                    </span>

                                </div>
                            @endforeach

                        </div>

                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center">

                        <h3 class="font-semibold text-slate-900">
                            Belum ada task
                        </h3>

                        <p class="mt-2 text-sm text-slate-500">
                            Project ini belum memiliki task.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
