<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Tag;

class NoteController extends Controller
{
    /**
     * Display a listing of the notes.
     */
    public function index(Request $request): View
    {
        $query = auth()->user()
            ->notes()
            ->with(['category', 'tags']);

        $view = $request->get('view', 'all');

        if ($view === 'archived') {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);

            if ($view === 'pinned') {
                $query->where('is_pinned', true);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category
        if ($request->filled('category_id')) {
            $categoryId = $request->category_id;

            $query->whereHas('category', function ($query) use ($categoryId) {
                $query
                    ->where('categories.id', $categoryId)
                    ->where('categories.user_id', auth()->id());
            });
        }

        // Tag
        if ($request->filled('tag_id')) {
            $tagId = $request->tag_id;

            $query->whereHas('tags', function ($query) use ($tagId) {
                $query
                    ->where('tags.id', $tagId)
                    ->where('tags.user_id', auth()->id());
            });
        }

        $notes = $query
            ->orderByDesc('is_pinned')
            ->latest()
            ->get();

        $categories = auth()->user()
            ->categories()
            ->orderBy('name')
            ->get();

        $tags = auth()->user()
            ->tags()
            ->orderBy('name')
            ->get();

        // Counter
        $pinnedCount = auth()->user()
            ->notes()
            ->where('is_pinned', true)
            ->where('is_archived', false)
            ->count();

        $archivedCount = auth()->user()
            ->notes()
            ->where('is_archived', true)
            ->count();

        return view(
            'notes.index',
            compact(
                'notes',
                'categories',
                'tags',
                'view',
                'pinnedCount',
                'archivedCount'
            )
        );
    }

    /**
     * Show the form for creating a new note.
     */
    public function create(): View
    {
        $categories = auth()->user()
            ->categories()
            ->orderBy('name')
            ->get();

        $tags = auth()->user()
            ->tags()
            ->orderBy('name')
            ->get();

        return view(
            'notes.create',
            compact('categories', 'tags')
        );
    }

    /**
     * Store a newly created note.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'content' => ['nullable', 'string'],

            'category_id' => [
                'nullable',
                'exists:categories,id,user_id,' . auth()->id(),
            ],

            'tags' => [
                'nullable',
                'array',
            ],

            'tags.*' => [
                'integer',
                'exists:tags,id,user_id,' . auth()->id(),
            ],
        ]);

        $tagIds = $validated['tags'] ?? [];

        unset($validated['tags']);

        $note = auth()->user()
            ->notes()
            ->create($validated);

        $note->tags()->sync($tagIds);

        return redirect()
            ->route('notes.show', $note)
            ->with('success', 'Note berhasil dibuat');
    }

    /**
     * Display the specified note.
     */
    public function show(Note $note): View
    {
        abort_unless(
            $note->user_id === auth()->id(),
            403
        );

        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified note.
     */
    public function edit(Note $note): View
    {
        abort_unless(
            $note->user_id === auth()->id(),
            403
        );

        $categories = auth()->user()
            ->categories()
            ->orderBy('name')
            ->get();

        $tags = auth()->user()
            ->tags()
            ->orderBy('name')
            ->get();

        $note->load('tags');

        return view(
            'notes.edit',
            compact('note', 'categories', 'tags')
        );
    }

    /**
     * Update the specified note.
     */
    public function update(
        Request $request,
        Note $note
    ): RedirectResponse {
        abort_unless(
            $note->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'category_id' => [
                'nullable',
                'exists:categories,id,user_id,' . auth()->id(),
            ],

            'tags' => [
                'nullable',
                'array',
            ],

            'tags.*' => [
                'integer',
                'exists:tags,id,user_id,' . auth()->id(),
            ],
        ]);

        $tagIds = $validated['tags'] ?? [];

        unset($validated['tags']);

        $note->update($validated);

        $note->tags()->sync($tagIds);

        return redirect()
            ->route('notes.show', $note)
            ->with('success', 'Note berhasil diperbarui');
    }

    /**
     * Remove the specified note.
     */
    public function destroy(Note $note): RedirectResponse
    {
        abort_unless(
            $note->user_id === auth()->id(),
            403
        );

        $note->delete();

        return redirect()
            ->route('notes.index')
            ->with('success', 'Note berhasil dihapus');
    }

    public function togglePin(Note $note): RedirectResponse
    {
        abort_unless(
            $note->user_id === auth()->id(),
            403
        );

        $note->update([
            'is_pinned' => ! $note->is_pinned,
        ]);

        return back()->with(
            'success',
            $note->is_pinned
                ? 'Note berhasil dipin'
                : 'Note berhasil di-unpin'
        );
    }

    public function toggleArchive(Note $note): RedirectResponse
    {
        abort_unless(
            $note->user_id === auth()->id(),
            403
        );

        $note->update([
            'is_archived' => ! $note->is_archived,
        ]);

        return redirect()
            ->route('notes.index')
            ->with(
                'success',
                $note->is_archived
                    ? 'Note berhasil diarsipkan'
                    : 'Note berhasil dikembalikan'
            );
    }
}
