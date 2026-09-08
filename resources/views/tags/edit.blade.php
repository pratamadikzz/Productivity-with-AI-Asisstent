@extends('layouts.app')

@section('title', 'Edit Tag')

@section('content')

<div class="px-8 py-8">

    <div class="mb-8">

        <h1 class="text-2xl font-bold text-slate-900">
            Edit Tag
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Perbarui nama tag.
        </p>

    </div>


    <div class="max-w-xl rounded-2xl border border-slate-200 bg-white p-6">

        <form
            action="{{ route('tags.update', $tag) }}"
            method="POST"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            <div>

                <label
                    for="name"
                    class="mb-2 block text-sm font-medium text-slate-700"
                >
                    Tag Name
                </label>

                <div class="relative">

                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        #
                    </span>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $tag->name) }}"
                        class="w-full rounded-xl border border-slate-300 py-3 pl-8 pr-4 text-sm outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                        required
                    >

                </div>

                @error('name')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div class="flex items-center justify-end gap-3">

                <a
                    href="{{ route('tags.index') }}"
                    class="rounded-xl px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-100"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
                >
                    Update Tag
                </button>

            </div>

        </form>

    </div>

</div>

@endsection