@extends('layouts.app')

@section('title', $event->title)

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="mb-8 flex items-center justify-between">

        <div>
            <a
                href="{{ route('events.index') }}"
                class="text-sm text-slate-500 hover:text-indigo-600"
            >
                ← Back to Events
            </a>

            <h1 class="mt-3 text-2xl font-bold text-slate-900">
                {{ $event->title }}
            </h1>
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('events.edit', $event) }}"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
            >
                Edit
            </a>

            <form
                action="{{ route('events.destroy', $event) }}"
                method="POST"
                onsubmit="return confirm('Hapus event ini?')"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                >
                    Delete
                </button>

            </form>

        </div>

    </div>


    {{-- EVENT DETAIL --}}
    <div class="max-w-3xl rounded-2xl border border-slate-200 bg-white p-6">

        <div class="space-y-6">

            {{-- DATE --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Date
                </p>

                <p class="mt-1 text-slate-900">
                    {{ $event->start_at->format('l, d F Y') }}
                </p>

            </div>


            {{-- TIME --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Time
                </p>

                <p class="mt-1 text-slate-900">
                    {{ $event->start_at->format('H:i') }}

                    @if($event->end_at)
                        — {{ $event->end_at->format('H:i') }}
                    @endif
                </p>

            </div>


            {{-- LOCATION --}}
            @if($event->location)

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Location
                    </p>

                    <p class="mt-1 text-slate-900">
                        {{ $event->location }}
                    </p>

                </div>

            @endif


            {{-- DESCRIPTION --}}
            @if($event->description)

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                        Description
                    </p>

                    <p class="mt-1 whitespace-pre-line text-slate-700">
                        {{ $event->description }}
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection