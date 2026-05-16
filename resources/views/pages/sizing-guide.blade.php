@extends('layouts.app')

@section('content')
<div class="max-w-[900px] mx-auto px-6 py-24">
    <div class="mb-16 text-center">
        <h1 class="text-2xl font-light tracking-[0.4em] uppercase mb-4 text-gray-900">Sizing Guide</h1>
        <div class="h-[1px] w-12 bg-black mx-auto mb-6"></div>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Find your perfect fit</p>
    </div>

    {{-- Kategori: Tops --}}
    <div class="mb-20">
        <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-8 border-b border-black pb-2 inline-block">Tops (Atasan)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[11px] uppercase tracking-widest">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-4 font-semibold text-gray-900">Size</th>
                        <th class="py-4 font-semibold text-gray-900">Chest (LD)</th>
                        <th class="py-4 font-semibold text-gray-900">Length (Pjg)</th>
                        <th class="py-4 font-semibold text-gray-900">Sleeve (Lengan)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-black font-medium">S</td>
                        <td class="py-4">100 CM</td>
                        <td class="py-4">68 CM</td>
                        <td class="py-4">22 CM</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-black font-medium">M</td>
                        <td class="py-4">105 CM</td>
                        <td class="py-4">70 CM</td>
                        <td class="py-4">23 CM</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-black font-medium">L</td>
                        <td class="py-4">110 CM</td>
                        <td class="py-4">72 CM</td>
                        <td class="py-4">24 CM</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-black font-medium">XL</td>
                        <td class="py-4">115 CM</td>
                        <td class="py-4">74 CM</td>
                        <td class="py-4">25 CM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kategori: Bottoms --}}
    <div>
        <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-8 border-b border-black pb-2 inline-block">Bottoms (Bawahan)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[11px] uppercase tracking-widest">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-4 font-semibold text-gray-900">Size</th>
                        <th class="py-4 font-semibold text-gray-900">Waist (LP)</th>
                        <th class="py-4 font-semibold text-gray-900">Hip (Pinggul)</th>
                        <th class="py-4 font-semibold text-gray-900">Length (Pjg)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500">
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-black font-medium">28-29</td>
                        <td class="py-4">76-78 CM</td>
                        <td class="py-4">98 CM</td>
                        <td class="py-4">95 CM</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-black font-medium">30-31</td>
                        <td class="py-4">80-82 CM</td>
                        <td class="py-4">102 CM</td>
                        <td class="py-4">97 CM</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-4 text-black font-medium">32-33</td>
                        <td class="py-4">84-86 CM</td>
                        <td class="py-4">106 CM</td>
                        <td class="py-4">99 CM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-20 p-8 bg-gray-50 text-[11px] text-gray-400 leading-loose uppercase tracking-widest">
        * Toleransi perbedaan ukuran 1-2 CM karena proses produksi massal. <br>
        * Model pria biasanya menggunakan size L (TB 180cm / BB 75kg).
    </div>
</div>
@endsection