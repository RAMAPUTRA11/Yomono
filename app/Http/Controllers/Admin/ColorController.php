<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:colors,name|max:255',
        ]);

        Color::create([
            'name' => strtoupper($request->name) // Paksa jadi uppercase agar rapi
        ]);

        return redirect()->back()->with('success', 'Color added successfully.');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->back()->with('success', 'Color deleted.');
    }
}