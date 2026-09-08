@extends('layouts.app')

@section('title', 'Notes')

@section('content')

    <div class="p-8">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    Notes
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Simpan ide, pengetahuan, dan catatan pentingmu.
                </p>
            </div>

            <a href="{{ route('notes.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                + New Note
            </a>

        </div>

        <div class="mb-6 flex flex-wrap gap-2">

            <a href="{{ route('notes.index') }}"
                class="rounded-xl px-4 py-2.5 text-sm font-semibold
            {{ $view === 'all'
                ? 'bg-slate-900 text-white'
                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                All Notes
            </a>

            <a href="{{ route('notes.index', ['view' => 'pinned']) }}"
                class="rounded-xl px-4 py-2.5 text-sm font-semibold
            {{ $view === 'pinned'
                ? 'bg-slate-900 text-white'
                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                📌 Pinned
            </a>

            <a href="{{ route('notes.index', ['view' => 'archived']) }}"
                class="rounded-xl px-4 py-2.5 text-sm font-semibold
            {{ $view === 'archived'
                ? 'bg-slate-900 text-white'
                : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                📦 Archived
            </a>

        </div>

        <div class="mb-6 grid gap-4 sm:grid-cols-3">

            {{-- All Notes --}}

            <a href="{{ route('notes.index') }}"
                class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-slate-300 hover:shadow-sm">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-slate-500">
                            Active Notes
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ auth()->user()->notes()->where('is_archived', false)->count() }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-100 p-3">
                        📝
                    </div>

                </div>

            </a>


            {{-- Pinned --}}

            <a href="{{ route('notes.index', ['view' => 'pinned']) }}"
                class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-slate-300 hover:shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Pinned
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $pinnedCount }}
                        </p>

                    </div>

                    <div class="rounded-xl bg-slate-100 p-3">
                        📌
                    </div>

                </div>

            </a>


            {{-- Archived --}}

            <a href="{{ route('notes.index', ['view' => 'archived']) }}"
                class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:border-slate-300 hover:shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm font-medium text-slate-500">
                            Archived
                        </p>

                        <p class="mt-2 text-2xl font-bold text-slate-900">
                            {{ $archivedCount }}
                        </p>

                    </div>

                    <div class="rounded-xl bg-slate-100 p-3">
                        📦
                    </div>

                </div>

            </a>

        </div>

        {{-- Search --}}
        <form action="{{ route('notes.index') }}" method="GET"
            class="mb-6 rounded-2xl border border-slate-200 bg-white p-4">

            <input type="hidden" name="view" value="{{ $view }}">

            <div class="grid gap-4 lg:grid-cols-3">

                {{-- Search --}}

                <div class="lg:col-span-1">

                    <label for="search" class="mb-2 block text-sm font-medium text-slate-700">
                        Search
                    </label>

                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        placeholder="Search notes..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">

                </div>


                {{-- Category --}}

                <div>

                    <label for="category_id" class="mb-2 block text-sm font-medium text-slate-700">
                        Category
                    </label>

                    <select id="category_id" name="category_id"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">

                        <option value="">
                            All categories
                        </option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>

                </div>


                {{-- Tag --}}

                <div>

                    <label for="tag_id" class="mb-2 block text-sm font-medium text-slate-700">
                        Tag
                    </label>

                    <select id="tag_id" name="tag_id"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900">

                        <option value="">
                            All tags
                        </option>

                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @selected(request('tag_id') == $tag->id)>
                                #{{ $tag->name }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>


            <div class="mt-4 flex items-center justify-between">

                <p class="text-sm text-slate-500">
                    {{ $notes->count() }}
                    {{ $notes->count() === 1 ? 'note' : 'notes' }}
                    found
                </p>

                <div class="flex gap-2">

                    <a href="{{ route('notes.index') }}"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100">
                        Reset
                    </a>

                    <button type="submit"
                        class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                        Apply Filter
                    </button>

                </div>

            </div>

        </form>


        {{-- Notes --}}
        <div class="mt-8">

            @if ($notes->count())

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">

                    @foreach ($notes as $note)
                        <div
                            class="group relative rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-sm">

                            {{-- Pin --}}
                            @if ($note->is_pinned)
                                <div
                                    class="mb-3 inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                    📌 Pinned
                                </div>
                            @endif


                            {{-- Title --}}
                            <h2 class="line-clamp-2 text-lg font-semibold text-slate-900">
                                {{ $note->title }}
                            </h2>

                            @if ($note->category)
                                <div class="mt-3">

                                    <span
                                        class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                        {{ $note->category->name }}
                                    </span>

                                </div>
                            @endif


                            {{-- Content Preview --}}
                            <p class="mt-2 line-clamp-4 text-sm leading-6 text-slate-500">
                                {{ $note->content ?: 'No content' }}
                            </p>


                            {{-- Footer --}}
                            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">

                                <span class="text-xs text-slate-400">
                                    {{ $note->created_at->format('d M Y') }}
                                </span>

                                <a href="{{ route('notes.show', $note) }}"
                                    class="text-sm font-medium text-slate-700 transition hover:text-slate-950">
                                    View →
                                </a>

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                {{-- Empty State --}}
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl">
                        📝
                    </div>

                    <h2 class="mt-5 text-lg font-semibold text-slate-900">
                        No notes yet
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Mulai simpan ide, catatan belajar, dokumentasi project,
                        atau pengetahuan pentingmu di sini.
                    </p>

                    <a href="{{ route('notes.create') }}"
                        class="mt-6 inline-flex items-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                        Create your first note
                    </a>

                </div>

            @endif

        </div>

    </div>

@endsection
