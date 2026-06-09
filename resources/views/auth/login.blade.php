@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white flex flex-col justify-center py-12 px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <h2 class="text-2xl font-light tracking-[0.3em] uppercase text-gray-900">Login</h2>
        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-2">Welcome back to YOMONO</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 border border-gray-100 sm:px-10 shadow-xs">
            
            {{-- Alert Notifikasi System / Validasi Global --}}
            @if(session('error') || $errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-2 border-red-500 text-red-700 text-[10px] uppercase tracking-widest">
                    @if(session('error'))
                        <p class="font-bold">{{ session('error') }}</p>
                    @else
                        <p class="font-bold">Invalid credentials. Please try again.</p>
                    @endif
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-2 border-green-500 text-green-700 text-[10px] uppercase tracking-widest font-bold">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form Login --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full border-0 border-b border-gray-200 focus:border-black focus:ring-0 text-sm py-3 transition-all rounded-none outline-none">
                    @error('email') 
                        <span class="text-[9px] text-red-500 uppercase mt-1 block tracking-wider">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div>
                    <div class="flex justify-between items-center">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[9px] uppercase tracking-widest text-gray-400 hover:text-black transition-all">
                                Forgot?
                            </a>
                        @endif
                    </div>
                    <input type="password" name="password" required 
                        class="mt-1 block w-full border-0 border-b border-gray-200 focus:border-black focus:ring-0 text-sm py-3 transition-all rounded-none outline-none">
                    @error('password') 
                        <span class="text-[9px] text-red-500 uppercase mt-1 block tracking-wider">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent text-[11px] uppercase tracking-[0.2em] font-bold text-white bg-black hover:bg-gray-800 transition-all active:scale-[0.99]">
                        Sign In
                    </button>
                </div>
            </form>
            
            {{-- Separator --}}
            <div class="relative flex py-8 items-center">
                <div class="flex-grow border-t border-gray-100"></div>
                <span class="flex-shrink mx-4 text-[9px] tracking-[0.3em] text-gray-300 uppercase">OR</span>
                <div class="flex-grow border-t border-gray-100"></div>
            </div>

            {{-- Google OAuth Login --}}
            <div>
                <a href="{{ route('google.login') }}" 
                   class="w-full flex justify-center items-center py-4 px-4 border border-gray-200 text-[11px] uppercase tracking-[0.2em] font-bold text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all active:scale-[0.99]">
                    <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-4 h-4 mr-3" alt="Google">
                    Continue with Google
                </a>
            </div>
            
            <div class="mt-10 text-center">
                <p class="text-[10px] uppercase tracking-widest text-gray-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-black font-bold border-b border-black pb-1 ml-1 hover:text-gray-600 hover:border-gray-600 transition-all">
                        Register here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection