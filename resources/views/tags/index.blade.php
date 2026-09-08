@extends('layouts.app')

@section('title', 'Tags')

@section('content')

<div class="px-8 py-8">

    <div class="flex items-center justify-between mb-8">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Tags
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Kelola tag untuk mengorganisir notes.
            </p>
        </div>

        <a
            href="{{ route('tags.create') }}"
            class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
        >
            + New Tag
        </a>

    </div>


    @if($tags->count())

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

            @foreach($tags as $tag)

                <div class="rounded-2xl border border-slate-200 bg-white p-5">

                    <div class="flex items-start justify-between">

                        <div>

                            <div class="flex items-center gap-2">

                                <span class="text-lg font-semibold text-slate-900">
                                    #{{ $tag->name }}
                                </span>

                            </div>

                            <p class="mt-2 text-sm text-slate-500">
                                {{ $tag->notes_count }}
                                {{ $tag->notes_count == 1 ? 'note' : 'notes' }}
                            </p>

                        </div>

                        <div class="flex items-center gap-2">

                            <a
                                href="{{ route('tags.edit', $tag) }}"
                                class="rounded-lg px-3 py-2 text-sm text-slate-600 hover:bg-slate-100"
                            >
                                Edit
                            </a>

                            <form
                                action="{{ route('tags.destroy', $tag) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus tag ini?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    Delete
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

            <div class="text-4xl">
                #
            </div>

            <h2 class="mt-4 text-lg font-semibold text-slate-900">
                Belum ada tag
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Buat tag pertama untuk mengorganisir notes kamu.
            </p>

            <a
                href="{{ route('tags.create') }}"
                class="mt-6 inline-flex rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
            >
                Create Tag
            </a>

        </div>

    @endif

</div>

@endsection