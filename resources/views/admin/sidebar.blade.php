{{-- Mobile Header Trigger --}}
<div class="w-full bg-white border-b border-gray-100 p-4 flex items-center justify-between md:hidden sticky top-0 z-50 shadow-xs">
    <div>
        <h1 class="text-sm font-bold tracking-tighter">YOMONO</h1>
        <p class="text-[8px] text-gray-400 uppercase tracking-[0.3em]">Admin Portal</p>
    </div>
    <button onclick="toggleSidebar()" class="text-gray-500 hover:text-black focus:outline-none p-1">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
</div>

{{-- Sidebar Container --}}
<div id="admin-sidebar" class="w-64 bg-white border-r border-gray-100 fixed md:sticky top-0 bottom-0 left-0 z-50 md:z-auto transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out min-h-screen flex flex-col shadow-xl md:shadow-none">
    
    {{-- Sidebar Brand Header --}}
    <div class="p-8 flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold tracking-tighter">YOMONO</h1>
            <p class="text-[9px] text-gray-400 uppercase tracking-[0.3em]">Administrator</p>
        </div>
        <button onclick="toggleSidebar()" class="text-gray-300 hover:text-black md:hidden text-2xl font-light leading-none">
            ×
        </button>
    </div>

    {{-- Nav Links --}}
    <nav class="mt-4 px-6 space-y-1 text-[11px] uppercase tracking-[0.2em] font-medium text-gray-500 flex-1">
        
        <a href="{{ route('admin.dashboard') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white font-bold' : 'hover:bg-gray-50 hover:text-black' }} rounded-sm transition">
            Dashboard
        </a>

        <a href="{{ route('admin.categories.index') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.categories.*') ? 'bg-black text-white font-bold' : 'hover:bg-gray-50 hover:text-black' }} rounded-sm transition">
            Manage Categories
        </a>

        <a href="{{ route('admin.products.index') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.products.*') ? 'bg-black text-white font-bold' : 'hover:bg-gray-50 hover:text-black' }} rounded-sm transition">
            Manage Products
        </a>

        <a href="{{ route('admin.orders.index') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.orders.*') ? 'bg-black text-white font-bold' : 'hover:bg-gray-50 hover:text-black' }} rounded-sm transition">
            Transactions
        </a>
        
        <a href="{{ route('admin.attributes.index') }}" 
           class="block py-3 px-4 {{ request()->routeIs('admin.attributes.*') ? 'bg-black text-white font-bold' : 'hover:bg-gray-50 hover:text-black' }} rounded-sm transition">
            Manage Attributes
        </a>

        <hr class="my-6 border-gray-100">

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full text-left py-3 px-4 text-red-400 hover:text-red-600 transition flex items-center gap-2 font-bold tracking-widest">
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div>

{{-- Overlay Backdrop untuk Mobile Sidebar --}}
<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }
</script>