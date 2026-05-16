<div class="w-64 bg-white border-r border-gray-100 hidden md:block min-h-screen">
    <div class="p-8">
        <h1 class="text-lg font-bold tracking-tighter">YOMONO</h1>
        <p class="text-[9px] text-gray-400 uppercase tracking-[0.3em]">Administrator</p>
    </div>

    <nav class="mt-4 px-6 space-y-2 text-[11px] uppercase tracking-[0.2em] font-medium text-gray-500">
        
        <a href="{{ route('admin.dashboard') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : 'hover:bg-gray-50' }} rounded-sm transition">
            Dashboard
        </a>

        <a href="{{ route('admin.categories.index') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.categories.*') ? 'bg-black text-white' : 'hover:bg-gray-50' }} rounded-sm transition">
            Manage Categories
        </a>

        <a href="{{ route('admin.products.index') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.products.*') ? 'bg-black text-white' : 'hover:bg-gray-50' }} rounded-sm transition">
            Manage Products
        </a>

        <a href="{{ route('admin.orders.index') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.orders.*') ? 'bg-black text-white' : 'hover:bg-gray-50' }} rounded-sm transition">
            Transactions
        </a>
        <a href="{{ route('admin.attributes.index') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.attributes.*') ? 'bg-black text-white' : 'hover:bg-gray-50' }} rounded-sm transition">
            Manage Attributes
        </a>

        <hr class="my-6 border-gray-100">

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left py-3 px-4 text-red-400 hover:text-red-600 transition flex items-center gap-2">
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div>