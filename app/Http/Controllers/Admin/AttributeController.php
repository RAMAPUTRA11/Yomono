<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Size;

class AttributeController extends Controller
{
    public function index()
    {
        // Mengambil data terbaru untuk ditampilkan di halaman attributes
// Mengambil data berdasarkan nama (A-Z) agar rapi di tabel
        $colors = Color::orderBy('name', 'asc')->get();
        $sizes = Size::orderBy('name', 'asc')->get();

        return view('admin.attributes', compact('colors', 'sizes'));
    }
}