@extends('layouts.app')

@section('title', 'Create Goal')

@section('content')

<div class="p-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">
            Create Goal
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Tentukan tujuan yang ingin kamu capai.
        </p>
    </div>


    <div class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-6">

        <form
            action="{{ route('goals.store') }}"
            method="POST"
            class="space-y-6"
        >

            @csrf


            {{-- TITLE --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Goal Title
                </label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title') }}"
                    required
                    placeholder="Contoh: Become Full Stack Developer"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                >

                @error('title')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- DESCRIPTION --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    placeholder="Jelaskan tujuan yang ingin kamu capai..."
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('description') }}</textarea>

            </div>


            {{-- TARGET DATE --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Target Date
                </label>

                <input
                    type="date"
                    name="target_date"
                    value="{{ old('target_date') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                >

                @error('target_date')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- BUTTON --}}
            <div class="flex gap-3">

                <a
                    href="{{ route('goals.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Create Goal
                </button>

            </div>

        </form>

    </div>

</div>

@endsection