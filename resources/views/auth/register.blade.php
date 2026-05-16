@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white flex flex-col justify-center py-12 px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <h2 class="text-2xl font-light tracking-[0.3em] uppercase text-gray-900">Create Account</h2>
        @if(session('info'))
            <p class="mt-4 text-[10px] text-gray-500 uppercase tracking-widest bg-gray-50 py-2 border border-gray-100">
                {{ session('info') }}
            </p>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 border border-gray-100 sm:px-10">
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-600">Full Name</label>
                    <input type="text" name="name" required class="mt-1 block w-full border-gray-300 focus:border-black focus:ring-0 text-sm py-3 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-600">Email Address</label>
                    <input type="email" name="email" required class="mt-1 block w-full border-gray-300 focus:border-black focus:ring-0 text-sm py-3 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-600">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full border-gray-300 focus:border-black focus:ring-0 text-sm py-3 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-gray-600">Confirm Password</label>
                    <input type="password" name="password_confirmation" required class="mt-1 block w-full border-gray-300 focus:border-black focus:ring-0 text-sm py-3 transition-all">
                </div>

                <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent text-[11px] uppercase tracking-[0.2em] font-bold text-white bg-black hover:bg-gray-800 transition-all duration-300">
                    Register
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-[10px] uppercase tracking-widest text-gray-400">
                    Already have an account? <a href="{{ route('login') }}" class="text-black font-bold border-b border-black pb-1 ml-1">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection