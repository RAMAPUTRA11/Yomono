@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-20 px-4">
    <h1 class="text-2xl font-light tracking-[0.2em] uppercase mb-10 text-center">Your Profile</h1>

    <div class="bg-white border border-gray-100 p-8 shadow-sm">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Name --}}
                <div>
                    <label class="text-[10px] uppercase tracking-widest text-gray-500 block mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" 
                           class="w-full border-gray-200 focus:border-black focus:ring-0 transition py-3 text-sm">
                </div>

                {{-- Email --}}
                <div>
                    <label class="text-[10px] uppercase tracking-widest text-gray-500 block mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" 
                           class="w-full border-gray-200 focus:border-black focus:ring-0 transition py-3 text-sm">
                </div>

                {{-- Update Button --}}
                <div class="pt-6">
                    <button type="submit" 
                            class="w-full bg-black text-white py-4 text-[11px] uppercase tracking-[0.3em] font-bold hover:bg-gray-800 transition">
                        Update Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection