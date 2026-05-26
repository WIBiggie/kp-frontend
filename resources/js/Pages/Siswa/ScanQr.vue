<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// State Kontrol Modal Pop-up
const isModalOpen = ref(false);
const selectedTool = ref(null);
const scanError = ref('');

// Data dummy alat (Sebagai simulasi data yang dicocokkan dari hasil Scan QR)
const mockToolsDatabase = {
    'QR-ALAT-001': { id: 1, code: 'KTG-ELC-01', name: 'Oscilloscope Digital x10', category: 'Elektronik', stock: 5, location: 'Rak A-1' },
    'QR-ALAT-002': { id: 2, code: 'KTG-PRK-02', name: 'Solder Station Listrik', category: 'Perkakas', stock: 2, location: 'Rak B-3' },
    'QR-ALAT-003': { id: 3, code: 'KTG-UKR-03', name: 'Multimeter Digital Fluke', category: 'Ukur', stock: 12, location: 'Rak C-2' }
};

// Fungsi Simulasi Scan QR (Jika diklik, seolah-olah kamera berhasil membaca QR Code tertentu)
const handleSimulateScan = (qrPayload) => {
    scanError.value = '';
    const toolData = mockToolsDatabase[qrPayload];

    if (toolData) {
        selectedTool.value = toolData;
        isModalOpen.value = true; // Buka Pop-up
    } else {
        scanError.value = 'Kode QR tidak dikenali atau alat tidak terdaftar di sistem.';
    }
};

// Fungsi Aksi Transaksi dari Pop-up Modal
const handleAction = (type) => {
    if (type === 'pinjam') {
        alert(`Berhasil mengajukan PEMINJAMAN untuk: ${selectedTool.value.name}`);
    } else if (type === 'kembali') {
        alert(`Berhasil mengajukan PENGEMBALIAN untuk: ${selectedTool.value.name}`);
    }

    // Tutup modal setelah memilih aksi
    isModalOpen.value = false;
    selectedTool.value = null;
};
</script>

<template>
    <Head title="Scan QR Inventaris" />

    <AuthenticatedLayout>
        <div class="p-6 sm:p-8 max-w-[1400px] mx-auto space-y-8 text-gray-900">

            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Pindai Kode QR</h1>
                <p class="text-sm text-slate-500 mt-1">Arahkan kamera ke kode QR kategori alat yang tertempel di rak penyimpanan.</p>
            </div>

            <div class="max-w-xl mx-auto bg-white border border-gray-100 rounded-3xl p-6 shadow-sm text-center space-y-6">
                <div class="relative aspect-square w-full max-w-[360px] mx-auto bg-slate-900 rounded-2xl flex flex-col items-center justify-center text-white overflow-hidden border-4 border-slate-200 shadow-inner">

                    <div class="absolute inset-x-0 h-1 bg-cyan-400 shadow-[0_0_15px_#22d3ee] animate-bounce top-0 bottom-0 my-auto"></div>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-16 h-16 text-slate-500 opacity-60 animate-pulse">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                    </svg>

                    <span class="text-xs text-slate-400 mt-2 z-10">Mencari Kode QR Otomatis...</span>
                </div>

                <p v-if="scanError" class="text-sm font-semibold text-red-600 bg-red-50 p-3 rounded-xl">
                    {{ scanError }}
                </p>

                <div class="pt-2 border-t border-slate-100">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-3">Simulasi Pindai Rak Alat</span>
                    <div class="flex flex-wrap justify-center gap-2">
                        <button @click="handleSimulateScan('QR-ALAT-001')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-xs font-semibold rounded-lg text-slate-700 transition">
                            Scan Rak Elektronik
                        </button>
                        <button @click="handleSimulateScan('QR-ALAT-002')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-xs font-semibold rounded-lg text-slate-700 transition">
                            Scan Rak Perkakas
                        </button>
                        <button @click="handleSimulateScan('QR-ALAT-003')" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-xs font-semibold rounded-lg text-slate-700 transition">
                            Scan Rak Ukur
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4 z-50 transition-all">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden border border-slate-100 transform scale-100 transition-all">

                <div class="bg-[#5c53a3] text-white p-5 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <h3 class="font-bold text-base tracking-wide">QR Code Terdeteksi</h3>
                    </div>
                    <button @click="isModalOpen = false" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-1 rounded-full transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-blue-600 uppercase bg-blue-50 px-2.5 py-0.5 rounded-md">{{ selectedTool?.category }}</span>
                        <h4 class="text-lg font-bold text-slate-800 mt-1">{{ selectedTool?.name }}</h4>
                    </div>

                    <hr class="border-slate-100" />

                    <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-sm font-medium">
                        <div>
                            <span class="text-xs text-slate-400 block">Kode Logistik</span>
                            <span class="text-slate-700">{{ selectedTool?.code }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">Lokasi Penyimpanan</span>
                            <span class="text-slate-700">{{ selectedTool?.location }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block">Sisa Stok</span>
                            <span class="text-slate-700 font-bold text-slate-800">{{ selectedTool?.stock }} Unit</span>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-xl p-3 border border-amber-100 mt-2">
                        <p class="text-xs text-amber-800 font-normal leading-relaxed">
                            *Silakan konfirmasi aktivitas Anda. Pilih <b>Peminjaman Alat</b> jika ingin mengambil barang, atau <b>Pengembalian Alat</b> jika ingin mengembalikan barang ke rak ini.
                        </p>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 grid grid-cols-2 gap-3">
                    <button
                        @click="handleAction('pinjam')"
                        class="w-full bg-[#0084ff] hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-xl shadow-md active:scale-[0.98] transition text-sm flex items-center justify-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Peminjaman
                    </button>

                    <button
                        @click="handleAction('kembali')"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl shadow-md active:scale-[0.98] transition text-sm flex items-center justify-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        Pengembalian
                    </button>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
