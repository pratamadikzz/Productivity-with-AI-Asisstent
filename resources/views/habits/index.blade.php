@extends('layouts.app')

@section('title', 'Habits')

@section('content')

<div class="p-8">

    {{-- HEADER --}}
    <div class="mb-8 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Habits
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Bangun kebiasaan baik secara konsisten.
            </p>
        </div>

        <a
            href="{{ route('habits.create') }}"
            class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700"
        >
            + New Habit
        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- HABITS --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

        @forelse($habits as $habit)

            <div class="rounded-2xl border border-slate-200 bg-white p-6">

                {{-- TOP --}}
                <div class="flex items-start justify-between gap-4">

                    <div class="min-w-0">

                        <h2 class="truncate text-lg font-semibold text-slate-900">
                            {{ $habit->name }}
                        </h2>

                        @if($habit->description)

                            <p class="mt-2 line-clamp-2 text-sm text-slate-500">
                                {{ $habit->description }}
                            </p>

                        @endif

                    </div>


                    {{-- ACTIVE --}}
                    @if($habit->is_active)

                        <span class="shrink-0 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                            Active
                        </span>

                    @else

                        <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                            Inactive
                        </span>

                    @endif

                </div>


                {{-- INFO --}}
                <div class="mt-6 grid grid-cols-2 gap-3">

                    <div class="rounded-xl bg-slate-50 p-3">

                        <p class="text-xs text-slate-500">
                            Frequency
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ ucfirst($habit->frequency) }}
                        </p>

                    </div>


                    <div class="rounded-xl bg-slate-50 p-3">

                        <p class="text-xs text-slate-500">
                            Target
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $habit->target_per_week }}x / week
                        </p>

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="mt-6 flex items-center gap-2">

                    <a
                        href="{{ route('habits.show', $habit) }}"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        View
                    </a>

                    <a
                        href="{{ route('habits.edit', $habit) }}"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                    >
                        Edit
                    </a>

                    <form
                        action="{{ route('habits.destroy', $habit) }}"
                        method="POST"
                        class="ml-auto"
                        onsubmit="return confirm('Yakin ingin menghapus habit ini?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                        >
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        @empty

            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">

                <div class="text-4xl">
                    🎯
                </div>

                <h2 class="mt-4 text-lg font-semibold text-slate-900">
                    Belum ada habit
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Mulai dengan membuat habit pertama kamu.
                </p>

                <a
                    href="{{ route('habits.create') }}"
                    class="mt-5 inline-block rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700"
                >
                    Create Habit
                </a>

            </div>

        @endforelse

    </div>

</div>

@endsection