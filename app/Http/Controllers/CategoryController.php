<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = auth()->user()
            ->categories()
            ->withCount('notes')
            ->latest()
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,NULL,id,user_id,' . auth()->id(),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ]);

        auth()->user()
            ->categories()
            ->create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category berhasil dibuat');
    }

    public function edit(Category $category): View
    {
        abort_unless(
            $category->user_id === auth()->id(),
            403
        );

        return view('categories.edit', compact('category'));
    }

    public function update(
        Request $request,
        Category $category
    ): RedirectResponse {
        abort_unless(
            $category->user_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' .
                $category->id .
                ',id,user_id,' .
                auth()->id(),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category berhasil diperbarui');
    }

    public function destroy(Category $category): RedirectResponse
    {
        abort_unless(
            $category->user_id === auth()->id(),
            403
        );

        $category->delete();

        return back()->with(
            'success',
            'Category berhasil dihapus'
        );
    }
}