@extends('layouts.app')

@section('title', 'Create Category')

@section('content')

<div class="p-8">

    <div class="max-w-3xl">

        {{-- Back --}}
        <a
            href="{{ route('categories.index') }}"
            class="inline-flex items-center text-sm font-medium text-slate-500 transition hover:text-slate-900"
        >
            ← Back to Categories
        </a>


        {{-- Header --}}
        <div class="mt-8">

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Create Category
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Organize your notes into categories.
            </p>

        </div>


        {{-- Form --}}
        <form
            action="{{ route('categories.store') }}"
            method="POST"
            class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 sm:p-8"
        >

            @csrf


            {{-- Name --}}
            <div>

                <label
                    for="name"
                    class="text-sm font-medium text-slate-700"
                >
                    Category Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. Laravel"
                    required
                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Description --}}
            <div class="mt-6">

                <label
                    for="description"
                    class="text-sm font-medium text-slate-700"
                >
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Describe what this category is used for..."
                    class="mt-2 w-full rounded-lg border border-slate-200 px-4 py-3 text-sm leading-6 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Actions --}}
            <div class="mt-6 flex items-center justify-end gap-3">

                <a
                    href="{{ route('categories.index') }}"
                    class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800"
                >
                    Create Category
                </button>

            </div>

        </form>

    </div>

</div>

@endsection