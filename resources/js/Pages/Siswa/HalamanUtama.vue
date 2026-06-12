<script setup>
import { ref, computed } from 'vue';
import { Wrench, Clock, History, Search, QrCode, AlertTriangle } from 'lucide-vue-next';

const props = defineProps({
  statistik: Object,
  daftarBarang: Array,
  loading: Boolean
});

const emit = defineEmits(['changeView']);

const searchQuery = ref('');

const filteredBarang = computed(() => {
  return props.daftarBarang.filter(barang =>
    barang.nama_barang.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    (barang.kode_barang && barang.kode_barang.toLowerCase().includes(searchQuery.value.toLowerCase()))
  );
});
</script>

<template>
  <div>
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
      <div @click="emit('changeView', 'alat_tersedia')" class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-indigo-300 hover:shadow-sm cursor-pointer transition relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-indigo-500"></div>
        <div class="space-y-1 pl-2">
          <p class="text-xs sm:text-sm font-medium text-slate-400">Alat Tersedia</p>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ statistik.alatTersedia }}</h3>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition shrink-0">
          <Wrench :size="20" />
        </div>
      </div>

      <div @click="emit('changeView', 'alat_dipinjam')" class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-amber-400 hover:shadow-sm cursor-pointer transition relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-amber-500"></div>
        <div class="space-y-1 pl-2">
          <p class="text-xs sm:text-sm font-medium text-slate-400">Dipinjam</p>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ statistik.dipinjam }}</h3>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition shrink-0">
          <Clock :size="20" />
        </div>
      </div>

      <div @click="emit('changeView', 'belum_kembali')" class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-rose-300 hover:shadow-sm cursor-pointer transition relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-rose-500"></div>
        <div class="space-y-1 pl-2">
          <p class="text-xs sm:text-sm font-medium text-slate-400">Belum Kembali</p>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ statistik.belumDikembalikan }}</h3>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition shrink-0">
          <AlertTriangle :size="20" />
        </div>
      </div>

      <div @click="emit('changeView', 'riwayat_pinjaman')" class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-emerald-300 hover:shadow-sm cursor-pointer transition relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-emerald-500"></div>
        <div class="space-y-1 pl-2">
          <p class="text-xs sm:text-sm font-medium text-slate-400">Total Riwayat</p>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ statistik.riwayat }}</h3>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition shrink-0">
          <History :size="20" />
        </div>
      </div>
    </section>

    <section class="bg-white rounded-2xl sm:rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
      <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-50/50">
        <div>
          <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Alat Tersedia</h2>
          <p class="text-xs text-slate-500 mt-0.5">Daftar inventaris bengkel / alat praktek sekolah.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
          <div class="relative flex-1 sm:w-64">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" :size="18" />
            <input v-model="searchQuery" type="text" placeholder="Cari Alat..." class="w-full pl-10 pr-4 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500" />
          </div>
          <button @click="emit('changeView', 'scan_qr')" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition">
            <QrCode :size="16" /> Scan QR
          </button>
        </div>
      </div>

      <div v-if="loading" class="py-16 text-center text-slate-400 font-medium">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-4"></div>
        Sinkronisasi data server Lumen...
      </div>
      <div v-else-if="filteredBarang.length === 0" class="py-16 text-center text-slate-400">
        <Wrench class="mx-auto text-slate-300 mb-2" :size="36" />
        <p class="font-medium text-sm">Alat tidak ditemukan.</p>
      </div>

      <div v-else class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse min-w-[650px]">
          <thead>
            <tr class="bg-indigo-500 text-xxs sm:text-xs font-bold uppercase tracking-wider text-white">
              <th class="px-4 py-3.5 sm:px-6 sm:py-4 w-12 text-center">No</th>
              <th class="px-4 py-3.5 sm:px-6 sm:py-4">Nama Alat</th>
              <th class="px-4 py-3.5 sm:px-6 sm:py-4">Kode Barang</th>
              <th class="px-4 py-3.5 sm:px-6 sm:py-4">Stok</th>
              <th class="px-4 py-3.5 sm:px-6 sm:py-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700">
            <tr v-for="(barang, index) in filteredBarang" :key="barang.id" :class="index % 2 === 1 ? 'bg-[#b6add5]/20' : 'bg-white'" class="hover:bg-indigo-50/50 transition cursor-pointer" @click="emit('changeView', 'alat_tersedia')">
              <td class="px-4 py-4 sm:px-6 text-center font-semibold text-slate-400">{{ index + 1 }}</td>
              <td class="px-4 py-4 sm:px-6">
                <div class="font-bold text-slate-800">{{ barang.nama_barang }}</div>
                <span class="text-xxs text-slate-400 block mt-0.5">SMKN 2 Tasikmalaya</span>
              </td>
              <td class="px-4 py-4 sm:px-6">
                <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xxs font-mono text-slate-600 border border-slate-200">
                  {{ barang.kode_barang || 'INVENTARIS-01' }}
                </span>
              </td>
              <td class="px-4 py-4 sm:px-6 font-extrabold text-slate-700">{{ barang.jumlah }} <span class="text-xxs font-normal text-slate-400">unit</span></td>
              <td class="px-4 py-4 sm:px-6 text-center">
                <span :class="barang.jumlah > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100'" class="inline-block px-2.5 py-0.5 rounded-full text-xxs font-semibold">
                  {{ barang.jumlah > 0 ? '● Tersedia' : '✕ Habis' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</template>
