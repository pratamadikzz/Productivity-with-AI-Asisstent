@extends('layouts.app')

@section('title', 'Projects')

@section('content')

    <div class="module-page projects-page">

        <div class="mx-auto max-w-7xl">

            {{-- Header --}}
            <div class="mb-8 flex items-center justify-between">

                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        Projects
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Kelola semua project dan pekerjaanmu.
                    </p>
                </div>

                <a href="{{ route('projects.create') }}"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">
                    + New Project
                </a>

            </div>


            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif


            {{-- Projects --}}
            @if ($projects->count())

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                    @foreach ($projects as $project)
                        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

                            {{-- Header --}}
                            <div class="mb-4 flex items-start justify-between gap-4">

                                <h2 class="text-lg font-semibold text-slate-900">
                                    {{ $project->name }}
                                </h2>

                                <span
                                    class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium capitalize text-slate-600">
                                    {{ str_replace('_', ' ', $project->status) }}
                                </span>

                            </div>


                            {{-- Description --}}
                            <p class="mb-5 text-sm leading-relaxed text-slate-500">
                                {{ $project->description ?: 'Tidak ada deskripsi.' }}
                            </p>


                            {{-- Dates --}}
                            <div class="mb-5 space-y-2 text-sm text-slate-500">

                                <div class="flex justify-between">
                                    <span>Start Date</span>

                                    <span class="font-medium text-slate-700">
                                        {{ $project->start_date?->format('d M Y') ?? '-' }}
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span>Deadline</span>

                                    <span class="font-medium text-slate-700">
                                        {{ $project->deadline?->format('d M Y') ?? '-' }}
                                    </span>
                                </div>

                            </div>


                            {{-- Actions --}}
                            <div class="flex gap-2">

                                <a href="{{ route('projects.show', $project) }}"
                                    class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">
                                    View
                                </a>

                                <a href="{{ route('projects.edit', $project) }}"
                                    class="rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-100">
                                    Edit
                                </a>

                                <form action="{{ route('projects.destroy', $project) }}" method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" onclick="return confirm('Hapus project ini?')"
                                        class="rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-100">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                {{-- Empty State --}}
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">

                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-2xl">
                        📁
                    </div>

                    <h3 class="text-lg font-semibold text-slate-900">
                        Belum ada project
                    </h3>

                    <p class="mt-2 mb-6 text-sm text-slate-500">
                        Buat project pertama kamu untuk mulai mengorganisir pekerjaan.
                    </p>

                    <a href="{{ route('projects.create') }}"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Buat Project
                    </a>

                </div>

            @endif

        </div>

    </div>

@endsection
