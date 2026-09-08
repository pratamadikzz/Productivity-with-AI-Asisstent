@extends('layouts.app')

@section('title', $note->title)

@section('content')

    <div class="p-8">

        {{-- Back --}}
        <a href="{{ route('notes.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-500 transition hover:text-slate-900">
            ← Back to Notes
        </a>


        {{-- Header --}}
        <div class="mt-8 flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

            <div class="max-w-3xl">

                @if ($note->is_pinned)
                    <div
                        class="mb-3 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                        📌 Pinned
                    </div>
                @endif

                <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                    {{ $note->title }}
                </h1>

                <p class="mt-2 text-sm text-slate-400">
                    Created {{ $note->created_at->format('d M Y, H:i') }}
                </p>

            </div>


            {{-- Actions --}}
            <div class="flex flex-wrap items-center gap-2">

                {{-- Pin --}}
                <form action="{{ route('notes.pin', $note) }}" method="POST">
                    @csrf

                    <button type="submit"
                        class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        {{ $note->is_pinned ? 'Unpin' : '📌 Pin' }}
                    </button>
                </form>


                {{-- Archive --}}
                <form action="{{ route('notes.archive', $note) }}" method="POST">
                    @csrf

                    <button type="submit"
                        class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        {{ $note->is_archived ? 'Unarchive' : 'Archive' }}
                    </button>
                </form>


                {{-- Edit --}}
                <a href="{{ route('notes.edit', $note) }}"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Edit
                </a>


                {{-- Delete --}}
                <form action="{{ route('notes.destroy', $note) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus note ini?')">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                        Delete
                    </button>

                </form>

            </div>

        </div>


        {{-- Content --}}
        <article class="mt-8 max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">

            @if ($note->content)
                <div class="whitespace-pre-line text-sm leading-7 text-slate-700">
                    {{ $note->content }}
                </div>
            @else
                <p class="text-sm italic text-slate-400">
                    This note has no content.
                </p>
            @endif

            @if ($note->tags->count())

                <div class="mt-4 flex flex-wrap gap-2">

                    @foreach ($note->tags as $tag)
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                            #{{ $tag->name }}
                        </span>
                    @endforeach

                </div>

            @endif

        </article>

    </div>

@endsection
