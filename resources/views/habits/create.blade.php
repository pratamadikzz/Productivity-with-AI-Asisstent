@extends('layouts.app')

@section('title', 'Create Habit')

@section('content')

<div class="p-8">

    <div class="mb-8">
        <a
            href="{{ route('habits.index') }}"
            class="text-sm text-slate-500 hover:text-slate-900"
        >
            ← Back to Habits
        </a>

        <h1 class="mt-4 text-2xl font-bold text-slate-900">
            Create Habit
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Tambahkan kebiasaan yang ingin kamu bangun.
        </p>
    </div>


    <div class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-6">

        <form
            action="{{ route('habits.store') }}"
            method="POST"
            class="space-y-5"
        >

            @csrf


            {{-- NAME --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Habit Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Coding Laravel"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                >

                @error('name')
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
                    placeholder="Deskripsi habit..."
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- FREQUENCY --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Frequency
                </label>

                <select
                    name="frequency"
                    id="frequency"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                >

                    <option
                        value="daily"
                        {{ old('frequency', 'daily') === 'daily' ? 'selected' : '' }}
                    >
                        Daily
                    </option>

                    <option
                        value="weekly"
                        {{ old('frequency') === 'weekly' ? 'selected' : '' }}
                    >
                        Weekly
                    </option>

                </select>

            </div>


            {{-- TARGET --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Target per Week
                </label>

                <input
                    type="number"
                    name="target_per_week"
                    value="{{ old('target_per_week', 7) }}"
                    min="1"
                    max="7"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                >

                <p class="mt-1 text-xs text-slate-500">
                    Berapa kali habit ini ingin dilakukan dalam satu minggu?
                </p>

                @error('target_per_week')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- ACTION --}}
            <div class="flex items-center gap-3 pt-3">

                <a
                    href="{{ route('habits.index') }}"
                    class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Create Habit
                </button>

            </div>

        </form>

    </div>

</div>

@endsection