@extends('layouts.app')

@section('content')
{{-- Container Utama: Menggunakan max-w-[1100px] agar layout terkumpul rapat di tengah (Aesthetic Centered) --}}
<div class="max-w-[1100px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 py-12 md:py-24">
    
    {{-- Header Halaman: Berada di Tengah (Centered Header) --}}
    <div class="border-b border-gray-100 pb-8 mb-12 text-center">
        <h1 class="text-xl md:text-2xl font-light tracking-[0.3em] text-black uppercase">PENGATURAN AKUN</h1>
        <p class="text-[10px] md:text-[11px] text-gray-400 tracking-widest uppercase mt-2">Perbarui profil, alamat pengiriman, dan proteksi keamanan kata sandi Anda.</p>
    </div>

    {{-- Grid Layout Responsif: 
         - HP (skala bawaan): 1 Kolom Vertikal Ke bawah
         - Tablet (md & lg): Mulai membagi ruang dengan seimbang
         - Laptop/Desktop: Layout Lebar Sempurna Berdampingan --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
        
        {{-- SISI KIRI: Form Input Data Akun & Sistem Alamat (Makan 7 Kolom di Layar Besar) --}}
        <div class="lg:col-span-7 space-y-20">
            
            {{-- Form Update Profil & Password --}}
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-8">
                @csrf
                @method('PATCH')

                <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black border-b border-gray-50 pb-2 mb-6">Informasi Profil</h2>

                {{-- Input Nama --}}
                <div class="group">
                    <label class="block text-[9px] font-bold tracking-widest uppercase text-gray-400 mb-1 group-focus-within:text-black transition-colors">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                           class="w-full text-[12px] border-b border-gray-200 py-2 outline-none focus:border-black transition bg-transparent normal-case tracking-wide placeholder-gray-300">
                    @error('name') <p class="text-red-500 text-[10px] mt-1 tracking-wide">{{ $message }}</p> @enderror
                </div>

                {{-- Input Email --}}
                <div class="group">
                    <label class="block text-[9px] font-bold tracking-widest uppercase text-gray-400 mb-1 group-focus-within:text-black transition-colors">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                           class="w-full text-[12px] border-b border-gray-200 py-2 outline-none focus:border-black transition bg-transparent normal-case tracking-wide placeholder-gray-300">
                    @error('email') <p class="text-red-500 text-[10px] mt-1 tracking-wide">{{ $message }}</p> @enderror
                </div>

                {{-- Seksi Ubah Kata Sandi --}}
                <div class="pt-4">
                    <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black border-b border-gray-50 pb-2 mb-6">Ubah Kata Sandi (Opsional)</h2>
                </div>

                <div class="group">
                    <label class="block text-[9px] font-bold tracking-widest uppercase text-gray-400 mb-1 group-focus-within:text-black transition-colors">Kata Sandi Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti sandi lama"
                           class="w-full text-[11px] border-b border-gray-200 py-2 outline-none focus:border-black transition bg-transparent tracking-wide placeholder-gray-300">
                    @error('password') <p class="text-red-500 text-[10px] mt-1 tracking-wide">{{ $message }}</p> @enderror
                </div>

                <div class="group">
                    <label class="block text-[9px] font-bold tracking-widest uppercase text-gray-400 mb-1 group-focus-within:text-black transition-colors">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru Anda"
                           class="w-full text-[11px] border-b border-gray-200 py-2 outline-none focus:border-black transition bg-transparent tracking-wide placeholder-gray-300">
                </div>

                {{-- Tombol Submit Profil --}}
                <div class="pt-2">
                    <button type="submit" class="w-full sm:w-auto bg-black text-white hover:bg-gray-800 transition-colors text-[10px] font-bold tracking-[0.2em] uppercase px-8 py-4 rounded-none shadow-sm active:scale-[0.99]">
                        SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>

            {{-- Form Tambah Alamat Baru --}}
            <div class="pt-4">
                <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black border-b border-gray-50 pb-2 mb-6">Tambah Alamat Baru</h2>
                
                <form action="{{ route('profile.address.store') }}" method="POST" class="space-y-6">
                    @csrf
                    {{-- Grid Input Internal: Otomatis 1 Kolom di HP, Berjejer 2 Kolom di Tablet & Laptop --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        
                        {{-- Dropdown Select Pilihan Label Alamat (Aesthetic Custom Dropdown) --}}
                        <div class="group">
                            <label class="block text-[9px] font-bold tracking-widest uppercase text-gray-400 mb-1 group-focus-within:text-black transition-colors">Label Alamat</label>
                            <div class="relative">
                                <select name="label" required
                                        class="w-full text-[12px] border-b border-gray-200 py-2 outline-none focus:border-black transition bg-transparent uppercase tracking-wider rounded-none cursor-pointer appearance-none text-gray-900 pr-8">
                                    <option value="" disabled selected class="normal-case text-gray-300">Pilih Label Alamat</option>
                                    <option value="Rumah" class="bg-white text-black">Rumah</option>
                                    <option value="Kantor" class="bg-white text-black">Kantor</option>
                                    <option value="Kosan" class="bg-white text-black">Kosan</option>
                                    <optgroup label="Label Lainnya" class="text-gray-400 uppercase text-[8px] tracking-widest">
                                    <option value="Lainnya" class="bg-white text-black">Lainnya</option>
                                </select>
                                {{-- Icon Panah Minimalis Custom --}}
                                <div class="absolute inset-y-0 right-0 flex items-center pr-1 pointer-events-none text-gray-400 group-focus-within:text-black">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[9px] font-bold tracking-widest uppercase text-gray-400 mb-1 group-focus-within:text-black transition-colors">Nama Penerima</label>
                            <input type="text" name="receiver_name" placeholder="Nama Lengkap Penerima" required
                                   class="w-full text-[12px] border-b border-gray-200 py-2 outline-none focus:border-black transition bg-transparent placeholder-gray-300">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-bold tracking-widest uppercase text-gray-400 mb-1 group-focus-within:text-black transition-colors">Nomor Telepon</label>
                        <input type="text" name="phone_number" placeholder="Contoh: 08XXXXXXXXXX" required
                               class="w-full text-[12px] border-b border-gray-200 py-2 outline-none focus:border-black transition bg-transparent placeholder-gray-300">
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-bold tracking-widest uppercase text-gray-400 mb-2 group-focus-within:text-black transition-colors">Alamat Lengkap</label>
                        <textarea name="full_address" placeholder="Tuliskan jalan, nomor rumah, RT/RW, kecamatan, dan kota secara rinci..." rows="3" required
                                  class="w-full text-[12px] border border-gray-200 p-3 outline-none focus:border-black transition bg-transparent resize-none placeholder-gray-300"></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_default" id="is_default" value="1" class="w-4 h-4 text-black border-gray-300 rounded focus:ring-transparent accent-black cursor-pointer">
                        <label for="is_default" class="ml-2 text-[10px] text-gray-500 uppercase tracking-widest cursor-pointer select-none hover:text-black transition-colors">Jadikan Alamat Utama</label>
                    </div>

                    <button type="submit" class="w-full sm:w-auto border border-black bg-transparent text-black hover:bg-black hover:text-white transition-colors text-[10px] font-bold tracking-[0.2em] uppercase px-8 py-4 rounded-none active:scale-[0.99]">
                        SIMPAN ALAMAT
                    </button>
                </form>
            </div>

            {{-- Daftar Alamat Tersimpan --}}
            <div class="pt-4">
                <h2 class="text-[11px] font-bold tracking-[0.2em] uppercase text-black border-b border-gray-50 pb-2 mb-6">Daftar Alamat Saya</h2>
                
                @if($addresses->isEmpty())
                    <p class="text-[11px] text-gray-400 italic tracking-wider">Belum ada alamat pengiriman yang tersimpan.</p>
                @else
                    <div class="space-y-4">
                        @foreach($addresses as $addr)
                            <div class="p-5 border border-gray-100 rounded-none flex flex-col sm:flex-row justify-between sm:items-center gap-4 bg-white hover:border-black transition-colors">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[11px] font-bold uppercase tracking-widest text-black">{{ $addr->label }}</span>
                                        @if($addr->is_default) 
                                            <span class="text-[8px] bg-black text-white px-2 py-0.5 uppercase tracking-[0.2em] font-semibold scale-90">Utama</span> 
                                        @endif
                                    </div>
                                    <p class="text-[12px] text-gray-900 font-medium tracking-wide">
                                        {{ $addr->receiver_name }} <span class="text-gray-400 font-normal">({{ $addr->phone_number }})</span>
                                    </p>
                                    <p class="text-[11px] text-gray-400 leading-relaxed max-w-md tracking-wide">{{ $addr->full_address }}</p>
                                </div>
                                
                                <div class="sm:text-right">
                                    <form action="{{ route('profile.address.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[9px] font-bold text-gray-400 hover:text-black uppercase tracking-widest transition-colors py-1">
                                            Hapus Alamat
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- SISI KANAN: Kotak Informasi Tambahan & Keamanan (Makan 5 Kolom di Layar Besar) --}}
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-28 h-fit w-full">
            <div class="bg-[#fafafa] p-6 border border-gray-50 rounded-none">
                <h4 class="text-[10px] font-bold tracking-[0.2em] text-black uppercase mb-4 flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    Keamanan Akun
                </h4>
                <ul class="text-[11px] text-gray-400 space-y-3 list-disc list-inside font-light tracking-wide leading-relaxed">
                    <li>Gunakan email aktif Anda agar mempermudah pemantauan status tagihan & resi pengiriman otomatis.</li>
                    <li>Jika mengubah password, pastikan minimal terdiri atas 8 karakter kombinasi huruf dan angka.</li>
                    <li>Guna menjaga privasi, dilarang keras membagikan data kredensial login akun Anda kepada pihak luar.</li>
                </ul>
            </div>
            
            <div class="bg-[#fafafa] p-6 border border-gray-50 rounded-none">
                <h4 class="text-[10px] font-bold tracking-[0.2em] text-black uppercase mb-4 flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                    </svg>
                    Buku Alamat Pengiriman
                </h4>
                <p class="text-[11px] text-gray-400 font-light tracking-wide leading-relaxed">
                    Anda dapat menyimpan lebih dari satu alamat untuk memudahkan pengiriman hadiah kepada keluarga atau rekan kerja. Pastikan memilih salah satu sebagai alamat utama agar otomatis terpilih saat proses checkout berlangsung.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection