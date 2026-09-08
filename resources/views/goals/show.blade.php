@extends('layouts.app')

@section('title', $goal->title)

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="mb-8 flex items-start justify-between">

        <div>

            <a
                href="{{ route('goals.index') }}"
                class="text-sm text-slate-500 hover:text-indigo-600"
            >
                ← Back to Goals
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-900">
                {{ $goal->title }}
            </h1>

            @if($goal->description)

                <p class="mt-2 max-w-2xl text-sm text-slate-500">
                    {{ $goal->description }}
                </p>

            @endif

        </div>


        <a
            href="{{ route('goals.edit', $goal) }}"
            class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
        >
            Edit Goal
        </a>

    </div>


    {{-- PROGRESS CARD --}}
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-sm font-medium text-slate-500">
                    Progress
                </p>

                <p class="mt-1 text-3xl font-bold text-slate-900">
                    {{ $goal->progress }}%
                </p>

            </div>


            @if($goal->target_date)

                <div class="text-right">

                    <p class="text-xs text-slate-400">
                        Target Date
                    </p>

                    <p class="mt-1 text-sm font-medium text-slate-700">
                        {{ $goal->target_date->format('d M Y') }}
                    </p>

                </div>

            @endif

        </div>


        <div class="mt-5 h-3 overflow-hidden rounded-full bg-slate-100">

            <div
                class="h-full rounded-full bg-indigo-600 transition-all"
                style="width: {{ $goal->progress }}%"
            ></div>

        </div>

    </div>


    {{-- MILESTONES --}}
    <d{{-- MILESTONES --}}
<div class="rounded-2xl border border-slate-200 bg-white p-6">

    <div class="mb-5 flex items-center justify-between">

        <div>

            <h2 class="font-semibold text-slate-900">
                Milestones
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Langkah-langkah untuk mencapai goal.
            </p>

        </div>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- ADD MILESTONE --}}
    <form
        action="{{ route('goals.milestones.store', $goal) }}"
        method="POST"
        class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4"
    >

        @csrf

        <div class="grid gap-3 md:grid-cols-[1fr_180px_auto]">

            <input
                type="text"
                name="title"
                required
                placeholder="Contoh: Pelajari REST API"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm"
            >

            <input
                type="date"
                name="due_date"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm"
            >

            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
            >
                + Add
            </button>

        </div>

    </form>


    {{-- MILESTONE LIST --}}
    @if($goal->milestones->count())

        <div class="space-y-3">

            @foreach($goal->milestones as $milestone)

                <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-100 p-4">

                    <div class="flex min-w-0 items-center gap-3">

                        {{-- CHECKBOX --}}
                        <form
                            action="{{ route('milestones.update-status', $milestone) }}"
                            method="POST"
                        >

                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="
                                    flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 transition

                                    {{ $milestone->is_completed
                                        ? 'border-green-500 bg-green-500 text-white'
                                        : 'border-slate-300 hover:border-indigo-500'
                                    }}
                                "
                            >

                                @if($milestone->is_completed)
                                    ✓
                                @endif

                            </button>

                        </form>


                        {{-- TITLE --}}
                        <div class="min-w-0">

                            <p
                                class="
                                    text-sm font-medium

                                    {{ $milestone->is_completed
                                        ? 'text-slate-400 line-through'
                                        : 'text-slate-700'
                                    }}
                                "
                            >
                                {{ $milestone->title }}
                            </p>


                            @if($milestone->due_date)

                                <p class="mt-1 text-xs text-slate-400">
                                    Due {{ $milestone->due_date->format('d M Y') }}
                                </p>

                            @endif

                        </div>

                    </div>


                    {{-- DELETE --}}
                    <form
                        action="{{ route('milestones.destroy', $milestone) }}"
                        method="POST"
                        onsubmit="return confirm('Hapus milestone ini?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="text-xs font-medium text-red-500 hover:text-red-700"
                        >
                            Delete
                        </button>

                    </form>

                </div>

            @endforeach

        </div>

    @else

        <div class="py-8 text-center">

            <div class="text-3xl">
                📋
            </div>

            <p class="mt-3 text-sm text-slate-500">
                Belum ada milestone.
            </p>

        </div>

    @endif

</div>

</div>

@endsection