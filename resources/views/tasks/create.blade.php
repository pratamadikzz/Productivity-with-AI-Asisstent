@extends('layouts.app')

@section('title', 'Create Task')

@section('content')

<div class="mx-auto max-w-3xl p-8">

    <div class="mb-8">

        <a
            href="{{ route('tasks.index') }}"
            class="text-sm text-slate-400 hover:text-slate-900"
        >
            ← Back to Tasks
        </a>

        <h1 class="mt-4 text-3xl font-bold">
            Create Task
        </h1>

        <p class="mt-2 text-slate-500">
            Add something you need to accomplish.
        </p>

    </div>


    <form
        method="POST"
        action="{{ route('tasks.store') }}"
        class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6"
    >

        @csrf


        {{-- TITLE --}}
        <div>

            <label class="text-sm font-medium">
                Task title
            </label>

            <input
                type="text"
                name="title"
                value="{{ old('title') }}"
                placeholder="e.g. Finish portfolio homepage"
                class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
                required
            >

            @error('title')
                <p class="mt-1 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- DESCRIPTION --}}
        <div>

            <label class="text-sm font-medium">
                Description
            </label>

            <textarea
                name="description"
                rows="5"
                placeholder="Add some details..."
                class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
            >{{ old('description') }}</textarea>

        </div>


        {{-- PRIORITY --}}
        <div>

            <label class="text-sm font-medium">
                Priority
            </label>

            <select
                name="priority"
                class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
            >

                <option value="low">
                    Low
                </option>

                <option value="medium" selected>
                    Medium
                </option>

                <option value="high">
                    High
                </option>

            </select>

        </div>


        {{-- DUE DATE --}}
        <div>

            <label class="text-sm font-medium">
                Due date
            </label>

            <input
                type="date"
                name="due_date"
                value="{{ old('due_date') }}"
                class="mt-2 w-full rounded-xl border-slate-300 focus:border-slate-900 focus:ring-slate-900"
            >

        </div>


        {{-- ACTION --}}
        <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">

            <a
                href="{{ route('tasks.index') }}"
                class="rounded-xl px-5 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800"
            >
                Create Task
            </button>

        </div>

    </form>

</div>

@endsection