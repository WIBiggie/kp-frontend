<script setup>
import { ref, computed } from 'vue';
import { Search, Layers, Info, CheckCircle2, AlertTriangle, QrCode } from 'lucide-vue-next';

const props = defineProps({
  daftarBarang: Array
});

// PERBAIKAN 1: Tambahkan 'selectBarangToScan' ke dalam defineEmits
const emit = defineEmits(['changeView', 'selectBarangToScan']);

const searchQuery = ref('');
const selectedKategori = ref('Semua');

const listKategori = computed(() => {
  const kategoriSet = new Set(props.daftarBarang.map(item => item.kategori || 'Peralatan'));
  return ['Semua', ...kategoriSet];
});

const filteredBarang = computed(() => {
  return props.daftarBarang.filter(barang => {
    const matchSearch = barang.nama_barang.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                        (barang.kode_barang && barang.kode_barang.toLowerCase().includes(searchQuery.value.toLowerCase()));

    if (selectedKategori.value !== 'Semua') {
      return matchSearch && (barang.kategori || 'Peralatan') === selectedKategori.value;
    }
    return matchSearch;
  });
});
</script>

<template>
  <div class="space-y-6">
    <div class="mb-4">
      <button @click="emit('changeView', 'dashboard')" class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition">
        ← Kembali ke Dashboard Utama
      </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="sm:col-span-2 relative">
        <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" :size="18" />
        <input v-model="searchQuery" type="text" placeholder="Cari nama alat atau kode barang..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition" />
      </div>
      <div class="relative">
        <Layers class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" :size="18" />
        <select v-model="selectedKategori" class="w-full pl-11 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-500 custom-select font-medium text-slate-700">
          <option v-for="kat in listKategori" :key="kat" :value="kat">{{ kat }}</option>
        </select>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto w-full">
        <table class="w-full text-left border-collapse min-w-[700px]">
          <thead>
            <tr class="bg-indigo-500 text-xxs font-bold uppercase tracking-wider text-white">
              <th class="py-4 px-6 text-center w-16">No</th>
              <th class="py-4 px-6">Informasi Alat / Barang</th>
              <th class="py-4 px-6">Kategori</th>
              <th class="py-4 px-6 text-center">Stok Tersedia</th>
              <th class="py-4 px-6">Status Keberadaan</th>
              <th class="py-4 px-6 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
            <tr v-if="filteredBarang.length === 0">
              <td colspan="6" class="text-center py-12 text-slate-400">
                <Info class="mx-auto mb-2 text-slate-300" :size="32" />
                <p class="font-medium text-sm">Barang tidak ditemukan</p>
              </td>
            </tr>
            <tr v-for="(barang, index) in filteredBarang" :key="barang.id" class="hover:bg-slate-50/80 transition">
              <td class="py-4 px-6 text-center font-bold text-slate-400 text-xs">{{ index + 1 }}</td>
              <td class="py-4 px-6">
                <h4 class="font-bold text-slate-900 text-sm leading-none">{{ barang.nama_barang }}</h4>
                <span class="px-1.5 py-0.5 text-[10px] font-mono bg-slate-100 border text-slate-500 rounded inline-block mt-1.5">{{ barang.kode_barang || 'INV-00' }}</span>
              </td>
              <td class="py-4 px-6">
                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 text-slate-600">{{ barang.kategori || 'Peralatan' }}</span>
              </td>
              <td class="py-4 px-6 text-center font-bold" :class="barang.jumlah > 0 ? 'text-slate-800' : 'text-rose-500'">{{ barang.jumlah }} <span class="text-xs font-normal text-slate-400">unit</span></td>
              <td class="py-4 px-6">
                <span :class="barang.jumlah > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-rose-50 text-rose-700 border-rose-100'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border">
                  <component :is="barang.jumlah > 0 ? CheckCircle2 : AlertTriangle" :size="13" /> {{ barang.jumlah > 0 ? 'Tersedia' : 'Habis' }}
                </span>
              </td>
              <td class="py-4 px-6 text-center">
                <button
                  @click="emit('selectBarangToScan', barang.kode_barang)"
                  :disabled="barang.jumlah === 0"
                  :class="barang.jumlah > 0 ? 'border-indigo-200 text-indigo-600 hover:border-indigo-600 hover:bg-indigo-50' : 'border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed'"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 border-2 rounded-xl text-xs font-bold transition"
                >
                  <QrCode :size="14" /> Pinjam
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
.custom-select {
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
  background-position: right 0.75rem center;
  background-repeat: no-repeat;
  background-size: 1.25rem;
}
</style>
