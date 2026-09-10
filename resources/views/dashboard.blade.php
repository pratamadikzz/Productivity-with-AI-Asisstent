@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-page">
        <header class="dashboard-header">
            <div>
                <p class="dashboard-eyebrow">{{ now()->format('l, d F Y') }}</p>
                <h1>Good morning, {{ auth()->user()->name }} <span>✦</span></h1>
                <p class="dashboard-subtitle">A clear view of what deserves your attention today.</p>
            </div>
            <a href="{{ route('tasks.create') }}" class="dashboard-primary-action">
                New task <span>+</span>
            </a>
        </header>

        <section class="dashboard-metrics" aria-label="Productivity overview">
            <article class="metric-card metric-card-coral">
                <div class="metric-card-top"><span class="metric-icon">✓</span><span class="metric-trend">Today</span></div>
                <p>Today's tasks</p>
                <strong>0 <small>/ 0</small></strong>
                <div class="metric-bar"><span style="width: 0%"></span></div>
            </article>
            <article class="metric-card metric-card-green">
                <div class="metric-card-top"><span class="metric-icon">◆</span><span class="metric-trend">Workspace</span>
                </div>
                <p>Active projects</p>
                <strong>0</strong>
                <div class="metric-bar"><span style="width: 0%"></span></div>
            </article>
            <article class="metric-card metric-card-yellow">
                <div class="metric-card-top"><span class="metric-icon">◎</span><span class="metric-trend">In progress</span>
                </div>
                <p>Goals this season</p>
                <strong>0</strong>
                <div class="metric-bar"><span style="width: 0%"></span></div>
            </article>
            <article class="metric-card metric-card-ink">
                <div class="metric-card-top"><span class="metric-icon">↗</span><span class="metric-trend">This week</span>
                </div>
                <p>Productivity score</p>
                <strong>0<small>%</small></strong>
                <div class="metric-bar"><span style="width: 0%"></span></div>
            </article>
        </section>

        <div class="dashboard-grid">
            <section class="dashboard-panel">
                <div class="panel-heading">
                    <div>
                        <p class="panel-kicker">Focus list</p>
                        <h2>Today's tasks</h2>
                        <p>Stay focused on what matters.</p>
                    </div>
                    <a href="{{ route('tasks.index') }}" class="panel-link">View all <span>↗</span></a>
                </div>
                <div class="task-empty-state">
                    <div class="empty-check">✓</div>
                    <h3>Your day is open.</h3>
                    <p>Add a task to turn today's intention into visible progress.</p>
                    <a href="{{ route('tasks.create') }}" class="empty-action">Create your first task <span>→</span></a>
                </div>
            </section>

            <section class="dashboard-panel">
                <div class="panel-heading">
                    <div>
                        <p class="panel-kicker">Shortcuts</p>
                        <h2>Quick actions</h2>
                        <p>Move your workspace forward.</p>
                    </div>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('tasks.create') }}" class="quick-action">
                        <span class="quick-action-icon coral">+</span>
                        <span><strong>Create task</strong><small>Add something you need to do.</small></span>
                        <b>→</b>
                    </a>
                    <a href="{{ route('projects.create') }}" class="quick-action">
                        <span class="quick-action-icon green">◆</span>
                        <span><strong>Start a project</strong><small>Give a bigger idea its home.</small></span>
                        <b>→</b>
                    </a>
                    <a href="{{ route('notes.create') }}" class="quick-action">
                        <span class="quick-action-icon yellow">✎</span>
                        <span><strong>Write a note</strong><small>Capture an idea before it wanders.</small></span>
                        <b>→</b>
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection
