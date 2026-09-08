@extends('layouts.app')

@section('title', 'Events')

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="mb-8 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Events
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Kelola jadwal dan kegiatan kamu.
            </p>
        </div>

        <a
            href="{{ route('events.create') }}"
            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
        >
            + New Event
        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- EVENT LIST --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">

        @forelse($events as $event)

            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 last:border-b-0">

                <div class="min-w-0">

                    <a
                        href="{{ route('events.show', $event) }}"
                        class="font-semibold text-slate-900 hover:text-indigo-600"
                    >
                        {{ $event->title }}
                    </a>

                    @if($event->description)

                        <p class="mt-1 truncate text-sm text-slate-500">
                            {{ $event->description }}
                        </p>

                    @endif

                    <div class="mt-2 flex flex-wrap gap-3 text-xs text-slate-500">

                        <span>
                            📅 {{ $event->start_at->format('d M Y') }}
                        </span>

                        <span>
                            🕐 {{ $event->start_at->format('H:i') }}
                        </span>

                        @if($event->location)

                            <span>
                                📍 {{ $event->location }}
                            </span>

                        @endif

                    </div>

                </div>


                <div class="ml-6 flex shrink-0 items-center gap-3">

                    <a
                        href="{{ route('events.edit', $event) }}"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600"
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
                            class="text-sm font-medium text-red-600 hover:text-red-700"
                        >
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="px-6 py-16 text-center">

                <div class="text-4xl">
                    📅
                </div>

                <h3 class="mt-4 font-semibold text-slate-900">
                    Belum ada event
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Buat event pertama kamu.
                </p>

                <a
                    href="{{ route('events.create') }}"
                    class="mt-5 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    + New Event
                </a>

            </div>

        @endforelse

    </div>

</div>

@endsection