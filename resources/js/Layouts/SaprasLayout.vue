<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const isSidebarOpen = ref(true);
const toggleSidebar = () => { isSidebarOpen.value = !isSidebarOpen.value; };

// Menu Navigasi Sesuai Gambar Manajemen.png
const menusSapras = [
    { name: 'Dashboard', icon: 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z' },
    { name: 'Pengajuan Alat', icon: 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z' },
    { name: 'Pengadaan Alat', icon: 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .415.162.791.425 1.056a1.507 1.507 0 001.056.425c.415 0 .791-.162 1.056-.425a1.507 1.507 0 00.425-1.056c0-.231-.035-.454-.1-.664m-1.838 0a48.393 48.393 0 00-.947-.08C11.512 2.81 10.667 3.773 10.667 4.908V17.5a2.25 2.25 0 002.25 2.25h6.75a2.25 2.25 0 002.25-2.25v-4.667' },
    { name: 'Jurusan', icon: 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 003.182 0l4.318-4.318a2.25 2.25 0 000-3.182L11.159 3.659A2.25 2.25 0 009.568 3z' },
    { name: 'Akun Jurusan', icon: 'M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.83-5.83m0 0a2.978 2.978 0 01-4.18-4.18m4.18 4.18L19.5 21M15.17 11.42L21 17.25M10.5 10.5a2.121 2.121 0 003 3m-3-3a2.121 2.121 0 013-3m-3 3H9m3 3V15' },
    { name: 'Laporan', icon: 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H3.75A2.25 2.25 0 001.5 4.5v15a2.25 2.25 0 002.25 2.25h16.5a2.25 2.25 0 002.25-2.25V14.25z' },
];

const subMenus = [
    { name: 'Profil', icon: 'M17.982 18.725A7.488 7.488 0 0012 15.75A7.488 7.488 0 006.018 18.725m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z' },
    { name: 'Pengaturan', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.42 1.15-2.01 2.37-2.37z' },
];
</script>

<template>
    <div class="min-h-screen bg-[#f4f6fa] flex relative overflow-x-hidden">

        <div v-if="isSidebarOpen" @click="toggleSidebar" class="fixed inset-0 bg-black/20 backdrop-blur-xs z-20 lg:hidden"></div>

        <aside class="fixed lg:static top-0 bottom-0 left-0 w-[260px] bg-white border-r border-gray-200 flex flex-col justify-between shrink-0 transition-all duration-300 ease-in-out z-30"
               :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:-ml-[260px]'">
            <div>
                <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100">
                    <span class="text-sm font-bold tracking-wider text-slate-800 uppercase">SMKN 2 TASIKMALAYA</span>
                    <button @click="toggleSidebar" class="lg:hidden p-1 rounded-lg hover:bg-slate-100 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <nav class="p-4 space-y-1.5">
                    <Link v-for="menu in menusSapras" :key="menu.name" href="#"
                          class="flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold transition duration-150"
                          :class="menu.name === 'Dashboard' ? 'bg-slate-100 text-slate-900' : 'text-slate-500 hover:bg-slate-50'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path :d="menu.icon" stroke-width="2"/></svg>
                        {{ menu.name }}
                    </Link>
                </nav>
            </div>

            <div class="p-4 border-t border-gray-100 space-y-1.5">
                <Link v-for="menu in subMenus" :key="menu.name" href="#" class="flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold text-slate-500 hover:bg-slate-50 transition duration-150">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path :d="menu.icon" stroke-width="2"/></svg>
                    {{ menu.name }}
                </Link>
                <Link href="#" method="post" as="button" class="w-full flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" stroke-width="2"/></svg>
                    Keluar
                </Link>
            </div>
        </aside>

        <div class="flex-1 min-w-0 flex flex-col transition-all duration-300">
            <header class="h-20 bg-white border-b border-gray-200 flex items-center px-6 justify-between lg:bg-transparent lg:border-none">
                <button @click="toggleSidebar" class="p-2 rounded-xl bg-white border border-gray-200 text-slate-700 shadow-sm hover:bg-slate-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-width="2"/></svg>
                </button>
            </header>

            <main class="flex-1 overflow-y-auto p-6 pt-2 lg:pt-0">
                <slot />
            </main>
        </div>
    </div>
</template>
