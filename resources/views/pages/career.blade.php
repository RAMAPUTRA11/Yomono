@extends('layouts.app')

@section('content')
<div class="max-w-[800px] mx-auto px-6 py-24">
    <div class="text-center mb-20">
        <h1 class="text-2xl font-light tracking-[0.5em] uppercase mb-4 text-gray-900">Join #yomonoteam</h1>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">We're always looking for creative minds</p>
    </div>

    <div class="space-y-12">
        <div class="border border-gray-100 p-10 text-center hover:border-black transition duration-500">
            <h3 class="text-[13px] font-bold uppercase tracking-[0.2em] mb-4">No Current Openings</h3>
            <p class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-widest mb-8">
                Saat ini kami belum membuka posisi baru. Namun, kamu tetap bisa mengirimkan CV dan Portfolio terbaikmu untuk pertimbangan di masa depan.
            </p>
            <a href="mailto:career@yomono.id" class="bg-black text-white px-10 py-4 text-[10px] uppercase tracking-[0.3em] hover:bg-gray-800 transition">Send Your CV</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-[11px] uppercase tracking-widest text-gray-400">
            <div class="p-6 bg-gray-50">Creative Marketing</div>
            <div class="p-6 bg-gray-50">Retail Associate</div>
            <div class="p-6 bg-gray-50">Fashion Designer</div>
            <div class="p-6 bg-gray-50">Operation Team</div>
        </div>
    </div>
</div>
@endsection