@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')

<div class="p-8">

    <div class="mx-auto max-w-3xl">

        {{-- Header --}}
        <div class="mb-8">

            <a
                href="{{ route('projects.index') }}"
                class="text-sm text-slate-500 hover:text-slate-900"
            >
                ← Back to Projects
            </a>

            <h1 class="mt-4 text-2xl font-bold text-slate-900">
                Edit Project
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Perbarui informasi project kamu.
            </p>

        </div>


        {{-- Form --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <form
                action="{{ route('projects.update', $project) }}"
                method="POST"
                class="space-y-6"
            >

                @csrf
                @method('PUT')


                {{-- Project Name --}}
                <div>

                    <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Project Name
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name', $project->name) }}"
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    @error('name')
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
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description', $project->description) }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Status --}}
                <div>

                    <label
                        for="status"
                        class="mb-2 block text-sm font-medium text-slate-700"
                    >
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                        <option
                            value="planned"
                            @selected(old('status', $project->status) === 'planned')
                        >
                            Planned
                        </option>

                        <option
                            value="in_progress"
                            @selected(old('status', $project->status) === 'in_progress')
                        >
                            In Progress
                        </option>

                        <option
                            value="completed"
                            @selected(old('status', $project->status) === 'completed')
                        >
                            Completed
                        </option>

                        <option
                            value="archived"
                            @selected(old('status', $project->status) === 'archived')
                        >
                            Archived
                        </option>

                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Dates --}}
                <div class="grid gap-6 md:grid-cols-2">

                    {{-- Start Date --}}
                    <div>

                        <label
                            for="start_date"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Start Date
                        </label>

                        <input
                            id="start_date"
                            type="date"
                            name="start_date"
                            value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Deadline --}}
                    <div>

                        <label
                            for="deadline"
                            class="mb-2 block text-sm font-medium text-slate-700"
                        >
                            Deadline
                        </label>

                        <input
                            id="deadline"
                            type="date"
                            name="deadline"
                            value="{{ old('deadline', $project->deadline?->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('deadline')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">

                    <a
                        href="{{ route('projects.index') }}"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection