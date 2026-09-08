@extends('layouts.app')

@section('title', 'Goals')

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="mb-8 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Goals
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Tentukan tujuan dan pantau progress kamu.
            </p>
        </div>

        <a
            href="{{ route('goals.create') }}"
            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
        >
            + New Goal
        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- GOALS --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

        @forelse($goals as $goal)

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                {{-- HEADER --}}
                <div class="flex items-start justify-between gap-4">

                    <div class="min-w-0">

                        <a
                            href="{{ route('goals.show', $goal) }}"
                            class="font-semibold text-slate-900 hover:text-indigo-600"
                        >
                            {{ $goal->title }}
                        </a>

                        <span
                            class="mt-2 inline-block rounded-full px-2.5 py-1 text-xs font-medium
                            {{
                                $goal->status === 'active'
                                    ? 'bg-blue-50 text-blue-700'
                                    : ($goal->status === 'completed'
                                        ? 'bg-green-50 text-green-700'
                                        : 'bg-slate-100 text-slate-600')
                            }}"
                        >
                            {{ ucfirst($goal->status) }}
                        </span>

                    </div>

                </div>


                {{-- DESCRIPTION --}}
                @if($goal->description)

                    <p class="mt-4 line-clamp-2 text-sm text-slate-500">
                        {{ $goal->description }}
                    </p>

                @endif


                {{-- PROGRESS --}}
                <div class="mt-5">

                    <div class="mb-2 flex items-center justify-between">

                        <span class="text-xs font-medium text-slate-500">
                            Progress
                        </span>

                        <span class="text-xs font-semibold text-slate-700">
                            {{ $goal->progress }}%
                        </span>

                    </div>

                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">

                        <div
                            class="h-full rounded-full bg-indigo-600 transition-all"
                            style="width: {{ $goal->progress }}%"
                        ></div>

                    </div>

                </div>


                {{-- TARGET DATE --}}
                @if($goal->target_date)

                    <div class="mt-4 text-xs text-slate-500">
                        🎯 Target:
                        {{ $goal->target_date->format('d M Y') }}
                    </div>

                @endif


                {{-- ACTION --}}
                <div class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4">

                    <a
                        href="{{ route('goals.show', $goal) }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-700"
                    >
                        View
                    </a>

                    <a
                        href="{{ route('goals.edit', $goal) }}"
                        class="text-sm font-medium text-slate-600 hover:text-indigo-600"
                    >
                        Edit
                    </a>

                    <form
                        action="{{ route('goals.destroy', $goal) }}"
                        method="POST"
                        onsubmit="return confirm('Hapus goal ini?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="text-sm font-medium text-red-600 hover:text-red-700"
                        >
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

                <div class="text-4xl">
                    🎯
                </div>

                <h3 class="mt-4 font-semibold text-slate-900">
                    Belum ada goal
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Buat goal pertama kamu dan mulai pantau progress.
                </p>

                <a
                    href="{{ route('goals.create') }}"
                    class="mt-5 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    + New Goal
                </a>

            </div>

        @endforelse

    </div>

</div>

@endsection