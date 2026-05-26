<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// State pencarian dan tab filter aktif
const searchQuery = ref('');
const activeTab = ref('semua'); // pilihan: semua, dipinjam, terlambat

// Data dummy riwayat peminjaman siswa
const loanHistory = ref([
    { id: 1, name: 'Solder Listrik 60W', category: 'Elektronik', borrowDate: '2026-05-26', returnDate: '-', status: 'Dipinjam' },
    { id: 2, name: 'Multimeter Digital', category: 'Ukur', borrowDate: '2026-05-25', returnDate: '-', status: 'Terlambat' },
    { id: 3, name: 'Tang Kombinasi 8 inch', category: 'Perkakas', borrowDate: '2026-05-20', returnDate: '2026-05-22', status: 'Selesai' },
    { id: 4, name: 'Oscilloscope Portable', category: 'Ukur', borrowDate: '2026-05-02', returnDate: '2026-05-05', status: 'Selesai' },
    { id: 5, name: 'Obeng Set Pro', category: 'Perkakas', borrowDate: '2025-12-10', returnDate: '2025-12-12', status: 'Selesai' },
]);

// Statistik Harian & Bulanan (di-hitung otomatis dari data dummy di atas)
const stats = computed(() => {
    return {
        hariIni: 2, // contoh jumlah aktivitas hari ini
        bulanIni: 4, // contoh total peminjaman bulan ini
        sedangDipinjam: loanHistory.value.filter(item => item.status === 'Dipinjam').length,
        belumKembali: loanHistory.value.filter(item => item.status === 'Terlambat').length,
    };
});

// Logika Filter Data berdasarkan Tab dan Kolom Pencarian
const filteredHistory = computed(() => {
    return loanHistory.value.filter(item => {
        // Cocokkan dengan pencarian nama atau kategori
        const matchesSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                              item.category.toLowerCase().includes(searchQuery.value.toLowerCase());

        // Cocokkan dengan tab aktif
        if (activeTab.value === 'dipinjam') {
            return matchesSearch && item.status === 'Dipinjam';
        } else if (activeTab.value === 'terlambat') {
            return matchesSearch && item.status === 'Terlambat';
        }
        return matchesSearch;
    });
});
</script>

<template>
    <Head title="Riwayat Pinjaman" />

    <AuthenticatedLayout>
        <div class="p-6 sm:p-8 max-w-[1400px] mx-auto space-y-8 text-gray-900">

            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Riwayat Pinjaman</h1>
                    <p class="text-sm text-slate-500 mt-1">Pantau data peminjaman alat praktik harian, bulanan, dan status pengembalian Anda.</p>
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
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">{{ stats.hariIni }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-4">Peminjaman Hari Ini</span>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">{{ stats.bulanIni }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-4">Total Bulan Ini</span>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">{{ stats.sedangDipinjam }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-4">Sedang Dipinjam</span>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between min-h-[140px] hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                        </div>
                        <span class="text-3xl font-bold text-slate-800">{{ stats.belumKembali }}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-500 mt-4">Belum Dikembalikan</span>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-6">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-100 pb-4">
                    <div class="flex bg-slate-100 p-1 rounded-xl w-fit">
                        <button
                            @click="activeTab = 'semua'"
                            :class="activeTab === 'semua' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-4 py-2 text-xs font-bold rounded-lg transition"
                        >
                            Semua Pinjaman
                        </button>
                        <button
                            @click="activeTab = 'dipinjam'"
                            :class="activeTab === 'dipinjam' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-4 py-2 text-xs font-bold rounded-lg transition"
                        >
                            Sedang Dipinjam
                        </button>
                        <button
                            @click="activeTab = 'terlambat'"
                            :class="activeTab === 'terlambat' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-4 py-2 text-xs font-bold rounded-lg transition"
                        >
                            Belum Dikembalikan
                        </button>
                    </div>

                    <div class="relative w-full md:w-64">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari riwayat alat..."
                            class="w-full pl-4 pr-10 py-2 text-sm border border-slate-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        />
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#5c53a3] text-white text-sm font-semibold">
                                <th class="py-3.5 px-4 w-16 text-center">No</th>
                                <th class="py-3.5 px-6">Nama Alat</th>
                                <th class="py-3.5 px-6">Kategori</th>
                                <th class="py-3.5 px-6">Tanggal Pinjam</th>
                                <th class="py-3.5 px-6">Tanggal Kembali</th>
                                <th class="py-3.5 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-100">
                            <tr v-if="filteredHistory.length === 0">
                                <td colspan="6" class="py-10 text-center text-slate-400 font-normal">
                                    Tidak ada data riwayat pinjaman yang cocok.
                                </td>
                            </tr>

                            <tr
                                v-for="(loan, index) in filteredHistory"
                                :key="loan.id"
                                :class="index % 2 === 1 ? 'bg-[#c3badb]/10' : 'bg-white'"
                                class="hover:bg-slate-50/60 transition"
                            >
                                <td class="py-3.5 px-4 text-center text-slate-500 font-normal">{{ index + 1 }}</td>
                                <td class="py-3.5 px-6 font-semibold text-slate-800">{{ loan.name }}</td>
                                <td class="py-3.5 px-6 text-slate-600">{{ loan.category }}</td>
                                <td class="py-3.5 px-6 text-slate-600">{{ loan.borrowDate }}</td>
                                <td class="py-3.5 px-6 text-slate-600">{{ loan.returnDate }}</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span
                                        :class="{
                                            'text-green-600 bg-green-50 px-2.5 py-1 rounded-full text-xs font-bold': loan.status === 'Selesai',
                                            'text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full text-xs font-bold': loan.status === 'Dipinjam',
                                            'text-red-600 bg-red-50 px-2.5 py-1 rounded-full text-xs font-bold': loan.status === 'Terlambat',
                                        }"
                                    >
                                        {{ loan.status === 'Terlambat' ? 'Belum Kembali' : loan.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
