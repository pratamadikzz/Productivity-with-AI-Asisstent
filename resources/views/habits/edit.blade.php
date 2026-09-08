@extends('layouts.app')

@section('title', 'Edit Habit')

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
            Edit Habit
        </h1>
    </div>


    <div class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-6">

        <form
            action="{{ route('habits.update', $habit) }}"
            method="POST"
            class="space-y-5"
        >

            @csrf
            @method('PUT')


            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Habit Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $habit->name) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                >{{ old('description', $habit->description) }}</textarea>

            </div>


            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Frequency
                </label>

                <select
                    name="frequency"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                >

                    <option
                        value="daily"
                        {{ old('frequency', $habit->frequency) === 'daily' ? 'selected' : '' }}
                    >
                        Daily
                    </option>

                    <option
                        value="weekly"
                        {{ old('frequency', $habit->frequency) === 'weekly' ? 'selected' : '' }}
                    >
                        Weekly
                    </option>

                </select>

            </div>


            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Target per Week
                </label>

                <input
                    type="number"
                    name="target_per_week"
                    value="{{ old('target_per_week', $habit->target_per_week) }}"
                    min="1"
                    max="7"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                >

            </div>


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
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

@endsection