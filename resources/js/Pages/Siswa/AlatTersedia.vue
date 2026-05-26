<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// State pencarian dan filter
const searchQuery = ref('');
const selectedCategory = ref('');

// Data dummy (Nanti bisa diganti dengan data riil dari Controller Laravel)
const tools = ref([
    { id: 1, name: 'Nama Alat 1', category: 'Elektronik', status: 'Tersedia', stock: 5 },
    { id: 2, name: 'Nama Alat 2', category: 'Perkakas', status: 'Dipinjam', stock: 2 },
    { id: 3, name: 'Nama Alat 3', category: 'Ukur', status: 'Tersedia', stock: 12 },
    { id: 4, name: 'Nama Alat 4', category: 'Elektronik', status: 'Tersedia', stock: 3 },
    { id: 5, name: 'Nama Alat 5', category: 'Perkakas', status: 'Maintenance', stock: 0 },
    { id: 6, name: 'Nama Alat 6', category: 'Ukur', status: 'Tersedia', stock: 8 },
]);

// Mengambil daftar kategori unik secara dinamis untuk opsi filter dropdown
const categories = computed(() => {
    const allCategories = tools.value.map(tool => tool.category);
    return [...new Set(allCategories)];
});

// Logika pemfilteran gabungan (Search + Category)
const filteredTools = computed(() => {
    return tools.value.filter(tool => {
        const matchesSearch = tool.name.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesCategory = selectedCategory.value === '' || tool.category === selectedCategory.value;
        return matchesSearch && matchesCategory;
    });
});

// Menghitung statistik alat yang khusus berstatus 'Tersedia'
const totalTersedia = computed(() => {
    return tools.value.filter(tool => tool.status === 'Tersedia').length;
});
const totalStokTersedia = computed(() => {
    return tools.value
        .filter(tool => tool.status === 'Tersedia')
        .reduce((sum, tool) => sum + tool.stock, 0);
});
</script>

<template>
    <Head title="Alat Tersedia" />

    <AuthenticatedLayout>
        <div class="p-6 sm:p-8 max-w-[1400px] mx-auto space-y-8 text-gray-900">

            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Daftar Alat Tersedia</h1>
                    <p class="text-sm text-slate-500 mt-1">Cari dan filter alat praktik yang siap untuk dipinjam.</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center border-2 border-slate-300 shadow-sm cursor-pointer hover:bg-slate-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-slate-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-2xl">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[120px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-green-50 rounded-xl text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">{{ totalTersedia }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-2">Jenis Alat Siap Pinjam</span>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[120px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">{{ totalStokTersedia }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-2">Total Total Stok Item</span>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-6">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800">Data Eksplorasi Alat</h2>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                        <div class="relative w-full sm:w-64">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari nama alat..."
                                class="w-full pl-4 pr-10 py-2 text-sm border border-slate-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            />
                        </div>

                        <div class="w-full sm:w-48">
                            <select
                                v-model="selectedCategory"
                                class="w-full px-4 py-2 text-sm border border-slate-300 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition appearance-none cursor-pointer"
                                style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;"
                            >
                                <option value="">Semua Kategori</option>
                                <option v-for="cat in categories" :key="cat" :value="cat">
                                    {{ cat }}
                                </option>
                            </select>
                        </div>
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
                                v-for="(tool, index) in filteredTools"
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

                            <tr v-if="filteredTools.length === 0">
                                <td colspan="5" class="py-10 text-center text-slate-400 font-normal bg-white">
                                    Tidak ada alat yang cocok dengan pencarian Anda.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
