@php
    $notesCount = auth()->user()->notes()->where('is_archived', false)->count();
@endphp

<aside class="fixed inset-y-0 left-0 z-40 w-64 border-r border-slate-200 bg-white">

    <div class="flex h-16 items-center border-b border-slate-200 px-6">
        <div>
            <h1 class="text-lg font-bold tracking-tight">
                Productivity OS
            </h1>

            <p class="text-xs text-slate-400">
                Personal workspace
            </p>
        </div>
    </div>


    <nav class="space-y-6 p-4">

        {{-- OVERVIEW --}}
        <div>
            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                Overview
            </p>

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-lg bg-slate-100 px-3 py-2.5 text-sm font-medium">
                <span>⌂</span>
                Dashboard
            </a>
        </div>


        {{-- WORK --}}
        <div>
            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                Work
            </p>

            <div class="space-y-1">

                <a href="{{ route('tasks.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 hover:bg-slate-100">
                    <span>✓</span>
                    Tasks
                </a>

                <a href="{{ route('projects.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 hover:bg-slate-100">
                    <span>◆</span>
                    Projects
                </a>

                <a href="{{ route('calendar.index') }}"
                    class="{{ request()->routeIs('calendar.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium">
                    <span>📅</span>
                    Calendar
                </a>

            </div>
        </div>


        {{-- PERSONAL --}}
        <div>
            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                Personal
            </p>

            <div class="space-y-1">

                <a href="{{ route('goals.index') }}"
                    class="{{ request()->routeIs('goals.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50' }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium">
                    <span>🎯</span>
                    Goals
                </a>

                <a href="#"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 hover:bg-slate-100">
                    <span>♨</span>
                    Habits
                </a>

                <a href="{{ route('habits.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 hover:bg-slate-100">
                    <span>✎</span>
                    Notes
                </a>

                <a href="{{ route('notes.index') }}"
                    class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-medium text-slate-600 hover:bg-slate-100">
                    <span class="flex items-center gap-3">
                        <span>📝</span>
                        <span>Notes</span>
                    </span>

                    @if ($notesCount > 0)
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-500">
                            {{ $notesCount }}
                        </span>
                    @endif
                </a>
                
            </div>
        </div>


        {{-- INSIGHTS --}}
        <div>
            <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                Insights
            </p>

            <div class="space-y-1">

                <a href="#"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 hover:bg-slate-100">
                    <span>▥</span>
                    Analytics
                </a>

                <a href="#"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-600 hover:bg-slate-100">
                    <span>✦</span>
                    AI Assistant
                </a>

            </div>
        </div>

    </nav>


    {{-- USER --}}
    <div class="absolute bottom-0 w-full border-t border-slate-200 p-4">

        <div class="flex items-center justify-between">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    <p class="text-sm font-medium">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-slate-400">
                        Personal
                    </p>
                </div>

            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="text-xs text-slate-400 hover:text-red-500">
                    Logout
                </button>
            </form>

        </div>

    </div>

</aside>
