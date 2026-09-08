@extends('layouts.app')

@section('title', 'Add Task')

@section('content')

<div class="p-8">

    <div class="mx-auto max-w-3xl">

        {{-- Header --}}
        <div class="mb-8">

            <a
                href="{{ route('projects.show', $project) }}"
                class="text-sm text-slate-500 hover:text-slate-900"
            >
                ← Back to Project
            </a>

            <h1 class="mt-4 text-2xl font-bold text-slate-900">
                Add Task
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Tambahkan task ke project
                <span class="font-medium text-slate-700">
                    {{ $project->name }}
                </span>
            </p>

        </div>


        {{-- Form --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <form
                action="{{ route('tasks.store') }}"
                method="POST"
                class="space-y-6"
            >

                @csrf

                {{-- Project --}}
                <input
                    type="hidden"
                    name="project_id"
                    value="{{ $project->id }}"
                >


                {{-- Title --}}
                <div>

                    <label
                        for="title"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Task Title
                    </label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Contoh: Buat halaman homepage"
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('title')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Description --}}
                <div>

                    <label
                        for="description"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Jelaskan task..."
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Priority --}}
                <div>

                    <label
                        for="priority"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Priority
                    </label>

                    <select
                        id="priority"
                        name="priority"
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option
                            value="low"
                            @selected(old('priority', 'medium') === 'low')
                        >
                            Low
                        </option>

                        <option
                            value="medium"
                            @selected(old('priority', 'medium') === 'medium')
                        >
                            Medium
                        </option>

                        <option
                            value="high"
                            @selected(old('priority') === 'high')
                        >
                            High
                        </option>

                    </select>

                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Due Date --}}
                <div>

                    <label
                        for="due_date"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Due Date
                    </label>

                    <input
                        id="due_date"
                        type="date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Buttons --}}
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">

                    <a
                        href="{{ route('projects.show', $project) }}"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        Add Task
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection