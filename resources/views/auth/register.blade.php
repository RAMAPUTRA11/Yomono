@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white flex flex-col justify-center py-12 px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <h2 class="text-2xl font-light tracking-[0.3em] uppercase text-gray-900">Create Account</h2>
        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-2">Join the YOMONO collective</p>
        
        {{-- Flash Message Alert --}}
        @if(session('info'))
            <p class="mt-4 text-[10px] text-gray-500 uppercase tracking-widest bg-gray-50 py-2 border border-gray-100">
                {{ session('info') }}
            </p>
        @endif
        @if(session('error'))
            <p class="mt-4 text-[10px] text-red-500 uppercase tracking-widest bg-red-50 py-2 border border-red-100">
                {{ session('error') }}
            </p>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 border border-gray-100 sm:px-10 shadow-xs">
            
            {{-- Form Register Manual --}}
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="mt-1 block w-full border-0 border-b border-gray-200 focus:border-black focus:ring-0 text-sm py-3 transition-all rounded-none outline-none">
                    @error('name') 
                        <span class="text-[9px] text-red-500 uppercase mt-1 block tracking-wider">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="mt-1 block w-full border-0 border-b border-gray-200 focus:border-black focus:ring-0 text-sm py-3 transition-all rounded-none outline-none">
                    @error('email') 
                        <span class="text-[9px] text-red-500 uppercase mt-1 block tracking-wider">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-4">
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold">Password</label>
                        <input type="password" name="password" required 
                            class="mt-1 block w-full border-0 border-b border-gray-200 focus:border-black focus:ring-0 text-sm py-3 transition-all rounded-none outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold">Confirm Password</label>
                        <input type="password" name="password_confirmation" required 
                            class="mt-1 block w-full border-0 border-b border-gray-200 focus:border-black focus:ring-0 text-sm py-3 transition-all rounded-none outline-none">
                    </div>
                    @error('password') 
                        <span class="col-span-1 sm:col-span-2 text-[9px] text-red-500 uppercase mt-1 block tracking-wider">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent text-[11px] uppercase tracking-[0.2em] font-bold text-white bg-black hover:bg-gray-800 transition-all active:scale-[0.99]">
                        Register
                    </button>
                </div>
            </form>

            {{-- Separator --}}
            <div class="relative flex py-8 items-center">
                <div class="flex-grow border-t border-gray-100"></div>
                <span class="flex-shrink mx-4 text-[9px] tracking-[0.3em] text-gray-300 uppercase">OR</span>
                <div class="flex-grow border-t border-gray-100"></div>
            </div>

            {{-- Google OAuth Register --}}
            <div>
                <a href="{{ route('google.login') }}" 
                   class="w-full flex justify-center items-center py-4 px-4 border border-gray-200 text-[11px] uppercase tracking-[0.2em] font-bold text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all active:scale-[0.99]">
                    <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" class="w-4 h-4 mr-3" alt="Google">
                    Continue with Google
                </a>
            </div>
            
            <div class="mt-10 text-center">
                <p class="text-[10px] uppercase tracking-widest text-gray-400">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-black font-bold border-b border-black pb-1 ml-1 hover:text-gray-600 hover:border-gray-600 transition-all">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection