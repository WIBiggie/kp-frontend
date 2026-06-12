<script setup>
import { ref, computed } from 'vue';
import { Search, Info, AlertTriangle, Calendar } from 'lucide-vue-next';

const props = defineProps({
  daftarRiwayat: Array
});

const emit = defineEmits(['changeView']);
const searchQuery = ref('');

// Memfilter hanya yang statusnya "Terlambat" atau Belum Kembali
const daftarTerlambat = computed(() => {
  const listArr = props.daftarRiwayat || [];

  const listBelumKembali = listArr.filter(item => {
    const status = item.status ? item.status.toLowerCase() : '';
    return status === 'terlambat' || status === 'dipinjam';
  });

  return listBelumKembali.filter(item => {
    const namaBarang = item.nama_barang || (item.barang && item.barang.nama_barang) || '';
    const kodeBarang = item.kode_barang || (item.barang && item.barang.kode_barang) || '';
    const query = searchQuery.value.toLowerCase();

    return namaBarang.toLowerCase().includes(query) || kodeBarang.toLowerCase().includes(query);
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

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
      <div class="relative w-full">
        <Search class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" :size="18" />
        <input v-model="searchQuery" type="text" placeholder="Cari alat yang terlambat..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition" />
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-rose-500 bg-amber-500 text-xxs font-bold uppercase tracking-wider text-white">
              <th class="py-4 px-6 text-center w-16">No</th>
              <th class="py-4 px-6">Informasi Alat</th>
              <th class="py-4 px-6">Keperluan</th>
              <th class="py-4 px-6 text-center w-28">Jumlah</th>
              <th class="py-4 px-6 w-44">Tanggal Pinjam</th>
              <th class="py-4 px-6 text-center w-40">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-if="daftarTerlambat.length === 0">
              <td colspan="6" class="py-10 text-center text-slate-400 text-sm">
                <Info :size="24" class="mx-auto mb-2 text-slate-300" />
                Tidak ada data alat yang belum kembali.
              </td>
            </tr>
            <tr v-for="(log, index) in daftarTerlambat" :key="log.id" class="hover:bg-slate-50/50 transition">
              <td class="py-4 px-6 text-center font-bold text-rose-400 text-xs">{{ index + 1 }}</td>
              <td class="py-4 px-6">
                <h4 class="font-bold text-slate-900 text-sm leading-tight truncate">{{ log.barang ? log.barang.nama_barang : log.nama_barang }}</h4>
                <span class="text-xxs font-mono text-rose-600 font-bold block mt-1">Kode: {{ log.barang ? log.barang.kode_barang : log.kode_barang }}</span>
              </td>
              <td class="py-4 px-6">
                <p class="text-xs text-slate-600 line-clamp-2 pr-2">{{ log.keperluan || '-' }}</p>
              </td>
              <td class="py-4 px-6 text-center font-bold text-rose-600">
                {{ log.jumlah_pinjam || log.jumlah }} <span class="text-xs font-normal text-slate-400">unit</span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                  <Calendar :size="12" class="text-rose-500 shrink-0" />
                  <span class="truncate">{{ log.tanggal_pinjam || log.tgl_pinjam }}</span>
                </div>
              </td>
              <td class="py-4 px-6 text-center">
                <button @click="emit('changeView', 'scan_qr', log.barang ? log.barang.kode_barang : log.kode_barang)" class="bg-rose-600 hover:bg-rose-700 text-white px-2.5 py-1.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-1">
                  Kembalikan Alat
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
