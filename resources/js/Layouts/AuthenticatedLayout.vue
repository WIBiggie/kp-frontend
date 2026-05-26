<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3'; // Tambahkan usePage

const isSidebarOpen = ref(true);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

// Hubungkan name dengan nama route asli Laravel Anda
const mainMenus = [
    { name: 'Dashboard', route: 'dashboard', icon: 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z' },
    { name: 'Alat Tersedia', route: 'alat.tersedia', icon: 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z' },
    { name: 'Scan QR', route: 'scan.qr', icon: 'M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z' },
    { name: 'Riwayat Pinjaman', route: 'dashboard', icon: 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z' },
    { name: 'Belum Dikembalikan', route: 'dashboard', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
];

const subMenus = [
    { name: 'Profil', route: 'dashboard', icon: 'M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z' },
    { name: 'Pengaturan', route: 'dashboard', icon: 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.767a1.123 1.123 0 00-.417 1.03c.004.074.006.148.006.222 0 .074-.002.148-.006.222a1.123 1.123 0 00.417 1.03l1.003.767c.379.29.507.82.26 1.43l-1.296 2.247a1.125 1.125 0 01-1.37.49l-1.216-.456a1.125 1.125 0 00-1.075.124c-.073.044-.146.087-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281a1.125 1.125 0 00-.645-.87c-.074-.04-.147-.083-.22-.127a1.124 1.124 0 00-1.075-.124l-1.217.456a1.125 1.125 0 01-1.37-.49l-1.296-2.247a1.125 1.125 0 01.26-1.43l1.003-.767c.313-.24.475-.62.417-1.03a3.47 3.47 0 01-.006-.222c0-.074.002-.148.006-.222a1.123 1.123 0 00-.417-1.03l-1.003-.767a1.125 1.125 0 01-.26-1.43l1.296-2.247a1.125 1.125 0 011.37-.49l1.216.456c.356.133.751.072 1.076-.124.072-.041.146-.084.218-.128.332-.183.582-.495.645-.869l.214-1.28z' },
];

// Fungsi helper untuk mengecek apakah komponen menu sedang aktif saat ini
const isRouteActive = (routeName) => {
    return usePage().url.startsWith(route(routeName, {}, false));
};
</script>

<template>
    <div class="min-h-screen bg-[#f4f6fa] flex relative overflow-x-hidden">

        <div
            v-if="isSidebarOpen"
            @click="toggleSidebar"
            class="fixed inset-0 bg-black/20 backdrop-blur-xs z-20 lg:hidden transition-opacity duration-300"
        ></div>

        <aside
            class="fixed lg:static top-0 bottom-0 left-0 w-[260px] bg-white border-r border-gray-200 flex flex-col justify-between shrink-0 transition-all duration-300 ease-in-out z-30"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:-ml-[260px]'"
        >
            <div>
                <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100">
                    <span class="text-base font-bold tracking-wider text-slate-800 uppercase">
                        SMKN 2 TASIKMALAYA
                    </span>
                    <button @click="toggleSidebar" class="lg:hidden p-1 rounded-lg hover:bg-slate-100 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <nav class="p-4 space-y-1.5">
                    <Link
                        v-for="menu in mainMenus"
                        :key="menu.name"
                        :href="route(menu.route)"
                        class="flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold transition duration-150"
                        :class="isRouteActive(menu.route)
                            ? 'bg-slate-100 text-slate-900'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="menu.icon" />
                        </svg>
                        {{ menu.name }}
                    </Link>
                </nav>
            </div>

            <div class="p-4 border-t border-gray-100 space-y-1.5">
                <Link
                    v-for="menu in subMenus"
                    :key="menu.name"
                    :href="route(menu.route)"
                    class="flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition duration-150"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="menu.icon" />
                    </svg>
                    {{ menu.name }}
                </Link>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition duration-150 text-left"
                >
                    <svg xmlns="http://www.w3.org/2000/xl" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Keluar
                </Link>
            </div>
        </aside>

        <div class="flex-1 min-w-0 flex flex-col transition-all duration-300">
            <header class="h-20 bg-white border-b border-gray-200 flex items-center px-6 shrink-0 lg:bg-transparent lg:border-none">
                <button
                    @click="toggleSidebar"
                    class="p-2 rounded-xl bg-white border border-gray-200 text-slate-700 shadow-sm hover:bg-slate-50 transition active:scale-95"
                >
                    <svg v-if="!isSidebarOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                    </svg>
                </button>
            </header>

            <main class="flex-1 overflow-y-auto pt-2 lg:pt-0">
                <slot />
            </main>
        </div>
    </div>
</template>
