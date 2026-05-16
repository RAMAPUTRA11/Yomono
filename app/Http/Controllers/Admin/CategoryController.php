<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::latest()->get();
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:categories,name']);
        
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function destroy($id) {
        Category::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Kategori dihapus.');
    }
}