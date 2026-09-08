@extends('layouts.app')

@section('title', 'Create Project')

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
                Create Project
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Buat project baru untuk mengorganisir pekerjaanmu.
            </p>

        </div>


        {{-- Form --}}
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

            <form
                action="{{ route('projects.store') }}"
                method="POST"
                class="space-y-6"
            >

                @csrf


                {{-- Name --}}
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
                        value="{{ old('name') }}"
                        placeholder="Contoh: Website Portfolio"
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
                        placeholder="Jelaskan project ini..."
                        class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>

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
                            @selected(old('status', 'planned') === 'planned')
                        >
                            Planned
                        </option>

                        <option
                            value="in_progress"
                            @selected(old('status') === 'in_progress')
                        >
                            In Progress
                        </option>

                        <option
                            value="completed"
                            @selected(old('status') === 'completed')
                        >
                            Completed
                        </option>

                        <option
                            value="archived"
                            @selected(old('status') === 'archived')
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
                            value="{{ old('start_date') }}"
                            class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >

                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


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
                            value="{{ old('deadline') }}"
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
                        Create Project
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection