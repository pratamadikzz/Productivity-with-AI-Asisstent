@extends('layouts.app')

@section('title', 'Create Note')

@section('content')

    <div class="notes-page notes-editor-page">

        <div class="max-w-4xl">

            {{-- Back --}}
            <a href="{{ route('notes.index') }}" class="notes-back">
                ← Back to Notes
            </a>


            {{-- Header --}}
            <div class="mt-8">

                <p class="notes-eyebrow">Personal library</p>
                <h1 class="notes-title text-2xl font-bold tracking-tight text-slate-900">
                    Create Note
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Capture an idea, knowledge, or anything worth remembering.
                </p>

            </div>


            {{-- Form --}}
            <form action="{{ route('notes.store') }}" method="POST"
                class="notes-editor mt-8 rounded-2xl border border-slate-200 bg-white p-6 sm:p-8">

                @csrf


                {{-- Title --}}
                <div>

                    <label for="title" class="text-sm font-medium text-slate-700">
                        Title
                    </label>

                    <input id="title" type="text" name="title" value="{{ old('title') }}"
                        placeholder="e.g. Laravel Middleware" required
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200">

                    @error('title')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Category --}}
                <div class="mt-6">

                    <label for="category_id" class="text-sm font-medium text-slate-700">
                        Category
                    </label>

                    <select id="category_id" name="category_id"
                        class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200">

                        <option value="">
                            Uncategorized
                        </option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">
                        Tags
                    </label>

                    @if ($tags->count())

                        <div class="grid gap-3 sm:grid-cols-2">

                            @foreach ($tags as $tag)
                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 hover:bg-slate-50">

                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">

                                    <span class="text-sm text-slate-700">
                                        #{{ $tag->name }}
                                    </span>

                                </label>
                            @endforeach

                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4">

                            <p class="text-sm text-slate-500">
                                Belum ada tag.
                            </p>

                            <a href="{{ route('tags.create') }}"
                                class="mt-2 inline-block text-sm font-semibold text-slate-900 hover:underline">
                                Buat tag terlebih dahulu →
                            </a>

                        </div>

                    @endif

                    @error('tags')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('tags.*')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Content --}}
                <div class="mt-6">

                    <label for="content" class="text-sm font-medium text-slate-700">
                        Content
                    </label>

                    <textarea id="content" name="content" rows="16" placeholder="Write your note here..."
                        class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm leading-6 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200">{{ old('content') }}</textarea>

                    @error('content')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Actions --}}
                <div class="mt-6 flex items-center justify-end gap-3">

                    <a href="{{ route('notes.index') }}"
                        class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </a>

                    <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                        Save Note
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection
