@extends('layouts.app')

@section('content')
<div class="max-w-[800px] mx-auto px-6 py-24">
    {{-- Header --}}
    <div class="mb-20 text-center">
        <h1 class="text-2xl font-light tracking-[0.4em] uppercase mb-4 text-gray-900">Sizing Guide</h1>
        <div class="h-[1px] w-12 bg-black mx-auto mb-6"></div>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Sistem Ukuran Gabungan (Dual-Sizing) YOMONO</p>
    </div>

    {{-- Kategori 1: Tops (Atasan) --}}
    <div class="mb-20">
        <div class="flex justify-between items-baseline border-b border-black pb-2 mb-6">
            <h2 class="text-[12px] font-bold uppercase tracking-[0.2em] text-gray-900">Tops (Atasan)</h2>
            <span class="text-[10px] text-gray-400 uppercase tracking-wider">* Satuan dalam Centimeter (CM)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[11px] uppercase tracking-widest">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-400 text-[10px]">
                        <th class="py-4 font-bold">Size</th>
                        <th class="py-4 font-bold">Chest (Lebar Dada)</th>
                        <th class="py-4 font-bold">Body Length (Panjang Baju)</th>
                        <th class="py-4 font-bold">Sleeve (Panjang Lengan)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500 divide-y divide-gray-100">
                    <tr>
                        <td class="py-5 text-black font-bold text-[12px]">S - M</td>
                        <td class="py-5">102 cm</td>
                        <td class="py-5">69 cm</td>
                        <td class="py-5">23 cm</td>
                    </tr>
                    <tr>
                        <td class="py-5 text-black font-bold text-[12px]">L - XL</td>
                        <td class="py-5">112 cm</td>
                        <td class="py-5">73 cm</td>
                        <td class="py-5">25 cm</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kategori 2: Bottoms (Bawahan) --}}
    <div class="mb-20">
        <div class="flex justify-between items-baseline border-b border-black pb-2 mb-6">
            <h2 class="text-[12px] font-bold uppercase tracking-[0.2em] text-gray-900">Bottoms (Bawahan)</h2>
            <span class="text-[10px] text-gray-400 uppercase tracking-wider">* Satuan dalam Centimeter (CM)</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[11px] uppercase tracking-widest">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-400 text-[10px]">
                        <th class="py-4 font-bold">Size</th>
                        <th class="py-4 font-bold">Waist (Lingkar Pinggang)</th>
                        <th class="py-4 font-bold">Equivalent Nomor Celana</th>
                        <th class="py-4 font-bold">Outseam (Panjang Celana)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-500 divide-y divide-gray-100">
                    <tr>
                        <td class="py-5 text-black font-bold text-[12px]">S - M</td>
                        <td class="py-5">70 - 82 cm</td>
                        <td class="py-5 text-gray-400">Setara No. 27 - 30</td>
                        <td class="py-5">96 cm</td>
                    </tr>
                    <tr>
                        <td class="py-5 text-black font-bold text-[12px]">L - XL</td>
                        <td class="py-5">82 - 94 cm</td>
                        <td class="py-5 text-gray-400">Setara No. 31 - 34</td>
                        <td class="py-5">99 cm</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Catatan Produksi --}}
    <div class="p-8 bg-gray-50 text-[11px] text-gray-400 leading-relaxed tracking-wider border border-gray-100">
        <p class="mb-2">* **Keterangan Ukuran:** Jika kamu biasa mengenakan ukuran pakaian S atau M, silakan pilih opsi **S-M**. Jika biasa menggunakan L atau XL, pilih opsi **L-XL**.</p>
        <p class="mb-2">* Toleransi perbedaan ukuran 1 - 2 cm wajar terjadi dikarenakan pengerjaan proses potong produksi massal.</p>
        <p class="mb-0">* Model katalog pria umumnya mengenakan ukuran **L-XL** (Tinggi Badan 180 cm / Berat Badan 75 kg).</p>
    </div>
</div>
@endsection