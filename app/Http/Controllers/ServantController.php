<?php

namespace App\Http\Controllers;

use App\Models\Servant;
use App\Models\Category;
use Illuminate\Http\Request;

class ServantController extends Controller
{
    public function index(Request $request)
    {
        $query = Servant::with('category');

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $servants = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('servants.index', compact('servants', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('servants.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        Servant::create($validated);

        return redirect()->route('servants.index')
            ->with('success', 'Data pelayan berhasil ditambahkan');
    }

    public function show(Servant $servant)
    {
        return view('servants.show', compact('servant'));
    }

    public function edit(Servant $servant)
    {
        $categories = Category::all();
        return view('servants.edit', compact('servant', 'categories'));
    }

    public function update(Request $request, Servant $servant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        $servant->update($validated);

        return redirect()->route('servants.index')
            ->with('success', 'Data pelayan berhasil diperbarui');
    }

    public function destroy(Servant $servant)
    {
        $servant->delete();

        return redirect()->route('servants.index')
            ->with('success', 'Data pelayan berhasil dihapus');
    }
}
