<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\HabitLogController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/tasks/kanban', [TaskController::class, 'kanban'])->name('tasks.kanban');

    Route::get('/calendar', [CalendarController::class, 'index'])
        ->name('calendar.index');

    Route::get(
        '/ai-assistant',
        [AIController::class, 'index']
    )->name('ai.index');

    Route::post(
        '/ai-assistant/chat',
        [AIController::class, 'chat']
    )->name('ai.chat');

    Route::get(
        '/ai-assistant/conversations/{conversation}',
        [AIController::class, 'conversation']
    )->name('ai.conversation');

    Route::resource('tasks', TaskController::class);

    Route::resource('projects', ProjectController::class);

    Route::get('/projects/{project}/tasks/create', [TaskController::class, 'createForProject'])->name('projects.tasks.create');

    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

    Route::resource('goals', GoalController::class);

    Route::resource('events', EventController::class);

    Route::post(
        '/goals/{goal}/milestones',
        [MilestoneController::class, 'store']
    )->name('goals.milestones.store');

    Route::patch(
        '/milestones/{milestone}/status',
        [MilestoneController::class, 'updateStatus']
    )->name('milestones.update-status');

    Route::delete(
        '/milestones/{milestone}',
        [MilestoneController::class, 'destroy']
    )->name('milestones.destroy');

    Route::resource('habits', HabitController::class);

    Route::post(
        '/habits/{habit}/toggle',
        [HabitLogController::class, 'toggle']
    )->name('habits.toggle');

    Route::resource('notes', NoteController::class);

    Route::post(
        '/notes/{note}/pin',
        [NoteController::class, 'togglePin']
    )->name('notes.pin');

    Route::post(
        '/notes/{note}/archive',
        [NoteController::class, 'toggleArchive']
    )->name('notes.archive');

    Route::resource('categories', CategoryController::class)
        ->except(['show']);

    Route::resource('tags', TagController::class)
        ->except(['show']);

    Route::get(
        '/analytics',
        [AnalyticsController::class, 'index']
    )->name('analytics.index');
});



require __DIR__ . '/auth.php';
