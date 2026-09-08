<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = auth()->user()
            ->tasks()
            ->with('project')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'description' => [
                'nullable',
                'string'
            ],

            'priority' => [
                'required',
                'in:low,medium,high'
            ],

            'due_date' => [
                'nullable',
                'date'
            ],

            'project_id' => [
                'nullable',
                'exists:projects,id,user_id,' . auth()->id(),
            ],
        ]);

        $task = auth()->user()
            ->tasks()
            ->create($validated);

        if ($task->project_id) {
            return redirect()->route('projects.show', $task->project_id)->with('success', 'Task berhasil ditambahkan ke project');
        }

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        abort_unless(
            $task->user_id === auth()->id(),
            403
        );

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        abort_unless(
            $task->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'status' => ['required', 'in:todo,in_progress,completed'],

            'priority' => ['required', 'in:low,medium,high'],

            'due_date' => ['nullable', 'date'],

        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        abort_unless(
            $task->user_id === auth()->id(),
            403
        );

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task berhasil dihapus');
    }

    public function createForProject(Project $project): View
    {
        abort_unless(
            $project->user_id === auth()->id(),
            403
        );

        return view('tasks.create-project', compact('project'));
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        abort_unless(
            $task->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'status' => ['required', 'in:todo,in_progress,completed'],
        ]);

        $task->update([
            'status' => $validated['status'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status task berhasil diperbarui',
            ]);
        }

        return back()->with('success', 'Status task berhasil diperbarui');
    }

    public function kanban(): View
    {
        $tasks = auth()->user()
            ->tasks()
            ->with('project')
            ->latest()
            ->get();

        $columns = [
            'todo' => 'Todo',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
        ];

        return view('tasks.kanban', compact('tasks', 'columns'));
    }
}
