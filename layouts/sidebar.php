<div class="flex min-h-screen fixed top-0 left-0 bottom-0 z-50">
    <aside class="w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white flex flex-col shadow-2xl">
        <div class="p-6 border-b border-blue-500/30">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white/20 backdrop-blur-sm rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Admin Panel</h1>
                    <p class="text-blue-200 text-xs mt-1">Pemilu Digital</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="/pemilu-digital/admin/dashboard.php"
                class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-white/10 hover:pl-5 active:scale-95">
                <div class="mr-3 p-2 bg-blue-500/30 group-hover:bg-blue-400 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="/pemilu-digital/admin/paslon/index.php"
                class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-white/10 hover:pl-5 active:scale-95">
                <div class="mr-3 p-2 bg-indigo-500/30 group-hover:bg-indigo-400 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="font-medium">Data Paslon</span>
            </a>

            <a href="/pemilu-digital/admin/user/index.php"
                class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 hover:bg-white/10 hover:pl-5 active:scale-95">
                <div class="mr-3 p-2 bg-emerald-500/30 group-hover:bg-emerald-400 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12 4a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7ZM6.5 7.5a5.5 5.5 0 1 1 11 0a5.5 5.5 0 0 1-11 0ZM3 19a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v3H3v-3Zm5-3a3 3 0 0 0-3 3v1h14v-1a3 3 0 0 0-3-3H8Z" />
                    </svg>
                </div>
                <span class="font-medium">Data User</span>
            </a>
        </nav>

        <div class="p-4 border-t border-blue-500/30">
            <a href="../../auth/logout.php"
                class="group flex items-center justify-center w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-3 px-4 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl active:scale-95">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </a>

            <div class="mt-4 text-center">
                <p class="text-blue-200 text-xs">Pemilu Digital v1.0</p>
                <p class="text-blue-300/50 text-xs mt-1">© <?= date('Y'); ?> Ujian Akhir Semester</p>
            </div>
        </div>

        <button id="sidebarToggle" class="lg:hidden absolute -right-3 top-6 bg-blue-600 text-white p-2 rounded-full shadow-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </aside>

    <div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>
</div>

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('aside');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const body = document.body;

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
            body.classList.toggle('overflow-hidden');
        });

        mobileOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
            body.classList.remove('overflow-hidden');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.add('transition-transform', 'duration-300', 'ease-in-out');
        }
    });
</script>