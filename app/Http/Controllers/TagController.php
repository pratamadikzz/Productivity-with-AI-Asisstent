<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = auth()->user()
            ->tags()
            ->withCount('notes')
            ->orderBy('name')
            ->get();

        return view('tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tags', 'name')
                    ->where(
                        fn ($query) =>
                            $query->where('user_id', auth()->id())
                    ),
            ],
        ]);

        auth()->user()
            ->tags()
            ->create($validated);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag berhasil dibuat');
    }

    public function edit(Tag $tag): View
    {
        abort_unless(
            $tag->user_id === auth()->id(),
            403
        );

        return view('tags.edit', compact('tag'));
    }

    public function update(
        Request $request,
        Tag $tag
    ): RedirectResponse {
        abort_unless(
            $tag->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tags', 'name')
                    ->where(
                        fn ($query) =>
                            $query->where('user_id', auth()->id())
                    )
                    ->ignore($tag->id),
            ],
        ]);

        $tag->update($validated);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag berhasil diperbarui');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        abort_unless(
            $tag->user_id === auth()->id(),
            403
        );

        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('success', 'Tag berhasil dihapus');
    }
}