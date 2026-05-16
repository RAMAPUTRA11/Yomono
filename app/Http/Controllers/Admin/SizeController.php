<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sizes,name|max:255',
        ]);

        Size::create([
            'name' => strtoupper($request->name)
        ]);

        return redirect()->back()->with('success', 'Size added successfully.');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->back()->with('success', 'Size deleted.');
    }
}