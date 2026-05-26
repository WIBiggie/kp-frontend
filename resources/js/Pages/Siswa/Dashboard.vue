<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// State pencarian
const searchQuery = ref('');

// Data dummy untuk tabel
const tools = ref([
    { id: 1, name: 'Nama Alat 1', category: 'Elektronik', status: 'Tersedia', stock: 5 },
    { id: 2, name: 'Nama Alat 2', category: 'Perkakas', status: 'Dipinjam', stock: 2 },
    { id: 3, name: 'Nama Alat 3', category: 'Ukur', status: 'Tersedia', stock: 12 },
    { id: 4, name: 'Nama Alat 4', category: 'Elektronik', status: 'Tersedia', stock: 3 },
    { id: 5, name: 'Nama Alat 5', category: 'Perkakas', status: 'Maintenance', stock: 0 },
    { id: 6, name: 'Nama Alat 6', category: 'Ukur', status: 'Tersedia', stock: 8 },
]);
</script>

<template>
    <Head title="Dashboard Siswa" />

    <AuthenticatedLayout>
        <div class="p-6 sm:p-8 max-w-[1400px] mx-auto space-y-8 text-gray-900">

            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Dashboard Siswa</h1>
                    <p class="text-sm text-slate-500 mt-1">Selamat datang kembali! Berikut ringkasan inventaris jurusan hari ini.</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center border-2 border-slate-300 shadow-sm cursor-pointer hover:bg-slate-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-slate-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-slate-100 rounded-xl text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">12</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-4">Alat Tersedia</span>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-slate-100 rounded-xl text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">3</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-4">Dipinjam</span>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-slate-100 rounded-xl text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">2</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-4">Belum Dikembalikan</span>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-slate-100 rounded-xl text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-4.25m16.5 0a2.507 2.507 0 00-3.32-2.3a2.503 2.503 0 00-4.68 0 2.503 2.503 0 00-4.68 0 2.507 2.507 0 00-3.32 2.3m16.5 0c0 .534-.154 1.03-.42 1.455A2.498 2.498 0 0118 15.75a2.498 2.498 0 01-1.76-.745 2.498 2.498 0 01-3.48 0 2.498 2.498 0 01-1.76.745 2.498 2.498 0 01-1.76-.745 2.498 2.498 0 01-3.48 0A2.498 2.498 0 013.3 14.55a2.493 2.493 0 01-.42-1.455V6.75A3.375 3.375 0 016.25 3.375h11.5A3.375 3.375 0 0121 6.75v5.955z" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">5</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-4">Riwayat</span>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800">Alat Tersedia</h2>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative w-full sm:w-64">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari Alat..."
                                class="w-full pl-4 pr-10 py-2 text-sm border border-slate-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            />
                        </div>

                        <Link :href="route('scan.qr')" class="flex items-center justify-center gap-2 bg-[#0084ff] hover:bg-blue-600 active:scale-95 text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-md transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" /></svg>
                            <span>Scan QR</span>
                        </Link>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#5c53a3] text-white text-sm font-semibold">
                                <th class="py-3.5 px-4 w-16 text-center">No</th>
                                <th class="py-3.5 px-6">Nama Alat</th>
                                <th class="py-3.5 px-6">Kategori</th>
                                <th class="py-3.5 px-6 text-center">Status</th>
                                <th class="py-3.5 px-6 text-center w-28">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-100">
                            <tr
                                v-for="(tool, index) in tools"
                                :key="tool.id"
                                :class="index % 2 === 1 ? 'bg-[#c3badb]' : 'bg-white'"
                                class="hover:bg-slate-50/60 transition"
                            >
                                <td class="py-3.5 px-4 text-center text-slate-500 font-normal">{{ index + 1 }}</td>
                                <td class="py-3.5 px-6 font-semibold">{{ tool.name }}</td>
                                <td class="py-3.5 px-6 text-slate-600">{{ tool.category }}</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        :class="{
                                            'text-green-600 bg-green-50 px-2.5 py-1 rounded-full text-xs font-bold': tool.status === 'Tersedia',
                                            'text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full text-xs font-bold': tool.status === 'Dipinjam',
                                            'text-red-600 bg-red-50 px-2.5 py-1 rounded-full text-xs font-bold': tool.status === 'Maintenance',
                                        }"
                                    >
                                        {{ tool.status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-center font-bold">{{ tool.stock }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
