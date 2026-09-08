@extends('layouts.app')

@section('title', 'Edit Task')

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
            Edit Task
        </h1>

    </div>


    <form
        method="POST"
        action="{{ route('tasks.update', $task) }}"
        class="space-y-6 rounded-2xl border border-slate-200 bg-white p-6"
    >

        @csrf
        @method('PUT')


        <div>

            <label class="text-sm font-medium">
                Task title
            </label>

            <input
                type="text"
                name="title"
                value="{{ old('title', $task->title) }}"
                class="mt-2 w-full rounded-xl border-slate-300"
                required
            >

            @error('title')
                <p class="mt-1 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror

        </div>


        <div>

            <label class="text-sm font-medium">
                Description
            </label>

            <textarea
                name="description"
                rows="5"
                class="mt-2 w-full rounded-xl border-slate-300"
            >{{ old('description', $task->description) }}</textarea>

        </div>


        <div>

            <label class="text-sm font-medium">
                Status
            </label>

            <select
                name="status"
                class="mt-2 w-full rounded-xl border-slate-300"
            >

                <option
                    value="todo"
                    @selected($task->status === 'todo')
                >
                    To Do
                </option>

                <option
                    value="in_progress"
                    @selected($task->status === 'in_progress')
                >
                    In Progress
                </option>

                <option
                    value="completed"
                    @selected($task->status === 'completed')
                >
                    Completed
                </option>

            </select>

        </div>


        <div>

            <label class="text-sm font-medium">
                Priority
            </label>

            <select
                name="priority"
                class="mt-2 w-full rounded-xl border-slate-300"
            >

                <option value="low" @selected($task->priority === 'low')>
                    Low
                </option>

                <option value="medium" @selected($task->priority === 'medium')>
                    Medium
                </option>

                <option value="high" @selected($task->priority === 'high')>
                    High
                </option>

            </select>

        </div>


        <div>

            <label class="text-sm font-medium">
                Due date
            </label>

            <input
                type="date"
                name="due_date"
                value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                class="mt-2 w-full rounded-xl border-slate-300"
            >

        </div>


        <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">

            <a
                href="{{ route('tasks.index') }}"
                class="rounded-xl px-5 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white"
            >
                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection