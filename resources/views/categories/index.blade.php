@extends('layouts.app')

@section('title', 'Categories')

@section('content')

<div class="p-8">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold">
                Categories
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Organize your knowledge.
            </p>
        </div>

        <a
            href="{{ route('categories.create') }}"
            class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white"
        >
            + New Category
        </a>

    </div>

    <div class="mt-8">

        @foreach($categories as $category)

            <div class="mb-3 rounded-xl border bg-white p-5">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="font-semibold">
                            {{ $category->name }}
                        </h2>

                        <p class="text-sm text-slate-500">
                            {{ $category->notes_count }} notes
                        </p>
                    </div>

                    <a
                        href="{{ route('categories.edit', $category) }}"
                        class="text-sm text-slate-600"
                    >
                        Edit
                    </a>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection