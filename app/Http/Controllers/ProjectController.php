<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $projects = auth()->user()
        ->projects()
        ->latest()
        ->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'status' => ['required', 'in:planned,in_progress,completed,archived'],

            'start_date' => ['nullable', 'date'],

            'deadline' => ['nullable', 'date']
        ]);

        auth()->user()
        ->projects()
        ->create($validated);

        return redirect()->route('projects.index')->with('success', 'Project berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        abort_unless(
            $project->user_id === auth()->id(),
            403
        );

        $project->load('tasks');

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        abort_unless(
            $project->user_id === auth()->id(),
            403
        );

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        abort_unless(
            $project->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string'],

            'status' => ['required', 'in:planned,in_progress,completed,archieved'],
            
            'start_date' => ['nullable', 'date'],
            'deadline' => ['nullable', 'date'], 
        ]);

        $project->update();

        return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        abort_unless(
            $project->user_id === auth()->id(),
            403
        );

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus');
    }
}
