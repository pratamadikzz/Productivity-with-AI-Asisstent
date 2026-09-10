@php
    $notesCount = auth()->user()->notes()->where('is_archived', false)->count();
@endphp

<button type="button" class="sidebar-mobile-toggle" @click="sidebarOpen = true" aria-label="Open navigation">
    <span></span><span></span><span></span>
</button>

<div class="sidebar-overlay" x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" aria-hidden="true">
</div>

<aside class="app-sidebar" :class="{ 'is-open': sidebarOpen }">

    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="sidebar-brand-link">
            <span class="sidebar-brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
            <span>productivity<span>.</span>os</span>
        </a>
        <button type="button" class="sidebar-close" @click="sidebarOpen = false"
            aria-label="Close navigation">×</button>
    </div>

    <nav class="sidebar-nav">

        {{-- OVERVIEW --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Overview</p>

            <a href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <span class="sidebar-icon">⌂</span>
                Dashboard
            </a>
        </div>


        {{-- WORK --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Work</p>

            <div class="space-y-1">

                <a href="{{ route('tasks.index') }}"
                    class="sidebar-link {{ request()->routeIs('tasks.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon">✓</span>
                    Tasks
                </a>

                <a href="{{ route('projects.index') }}"
                    class="sidebar-link {{ request()->routeIs('projects.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon">◆</span>
                    Projects
                </a>

                <a href="{{ route('calendar.index') }}"
                    class="sidebar-link {{ request()->routeIs('calendar.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon">□</span>
                    Calendar
                </a>

            </div>
        </div>


        {{-- PERSONAL --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Personal</p>

            <div class="space-y-1">

                <a href="{{ route('goals.index') }}"
                    class="sidebar-link {{ request()->routeIs('goals.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon">◎</span>
                    Goals
                </a>

                <a href="{{ route('habits.index') }}"
                    class="sidebar-link {{ request()->routeIs('habits.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon">↗</span>
                    Habits
                </a>

                <a href="{{ route('notes.index') }}"
                    class="sidebar-link {{ request()->routeIs('notes.*') ? 'is-active' : '' }}">
                    <span class="sidebar-link-label">
                        <span class="sidebar-icon">✎</span>
                        Notes
                    </span>

                    @if ($notesCount > 0)
                        <span class="sidebar-count">
                            {{ $notesCount }}
                        </span>
                    @endif
                </a>

            </div>
        </div>


        {{-- INSIGHTS --}}
        <div class="sidebar-section">
            <p class="sidebar-section-label">Insights</p>

            <div class="space-y-1">

                <a href="{{ route('analytics.index') }}"
                    class="sidebar-link {{ request()->routeIs('analytics.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon">▥</span>
                    Analytics
                </a>

                <a href="{{ route('ai.index') }}"
                    class="sidebar-link {{ request()->routeIs('ai.*') ? 'is-active' : '' }}">
                    <span class="sidebar-icon">✦</span>
                    AI Assistant
                </a>

            </div>
        </div>

    </nav>


    {{-- USER --}}
    <div class="sidebar-user">

        <div class="flex items-center justify-between">

            <div class="sidebar-user-info">

                <div class="sidebar-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div>
                    <p class="sidebar-user-name">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="sidebar-user-role">
                        Personal workspace
                    </p>
                </div>

            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="sidebar-logout" aria-label="Log out">
                    ↗
                </button>
            </form>

        </div>

    </div>

</aside>
