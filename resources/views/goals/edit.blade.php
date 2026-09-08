@extends('layouts.app')

@section('title', 'Edit Goal')

@section('content')

<div class="p-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">
            Edit Goal
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Perbarui informasi goal kamu.
        </p>
    </div>


    <div class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-6">

        <form
            action="{{ route('goals.update', $goal) }}"
            method="POST"
            class="space-y-6"
        >

            @csrf
            @method('PUT')


            {{-- TITLE --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Goal Title
                </label>

                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $goal->title) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
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
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                >{{ old('description', $goal->description) }}</textarea>

            </div>


            {{-- TARGET DATE --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Target Date
                </label>

                <input
                    type="date"
                    name="target_date"
                    value="{{ old('target_date', $goal->target_date?->format('Y-m-d')) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                >

            </div>


            {{-- STATUS --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                >

                    <option value="active" @selected(old('status', $goal->status) === 'active')>
                        Active
                    </option>

                    <option value="completed" @selected(old('status', $goal->status) === 'completed')>
                        Completed
                    </option>

                    <option value="archived" @selected(old('status', $goal->status) === 'archived')>
                        Archived
                    </option>

                </select>

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
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

@endsection