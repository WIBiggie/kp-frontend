<script setup>
import { ref, computed } from 'vue';
import { Search, Info, Calendar, UserCheck } from 'lucide-vue-next';

const props = defineProps({
  daftarRiwayat: Array
});

const emit = defineEmits(['changeView']);
const searchQuery = ref('');

const filteredRiwayat = computed(() => {
  const listArr = props.daftarRiwayat || [];

  return listArr.filter(item => {
    const namaBarang = item.nama_barang || (item.barang && item.barang.nama_barang) || '';
    const kodeBarang = item.kode_barang || (item.barang && item.barang.kode_barang) || '';
    const status = item.status || '';
    const query = searchQuery.value.toLowerCase();

    return namaBarang.toLowerCase().includes(query) ||
           kodeBarang.toLowerCase().includes(query) ||
           status.toLowerCase().includes(query);
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
        <input v-model="searchQuery" type="text" placeholder="Cari data riwayat barang atau status pinjaman..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-indigo-500 transition" />
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-emerald-500 bg-amber-500 text-xxs font-bold uppercase tracking-wider text-white">
              <th class="py-4 px-6 text-center w-16">No</th>
              <th class="py-4 px-6">Nama & Kode Alat</th>
              <th class="py-4 px-6">Keperluan</th>
              <th class="py-4 px-6 text-center w-28">Jumlah</th>
              <th class="py-4 px-6 w-52">Waktu Transaksi</th>
              <th class="py-4 px-6 text-center w-40">Status Log</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            <tr v-if="filteredRiwayat.length === 0">
              <td colspan="6" class="py-10 text-center text-slate-400 text-sm">
                <Info :size="24" class="mx-auto mb-2 text-slate-300" />
                Belum memiliki rekaman riwayat transaksi apapun.
              </td>
            </tr>
            <tr v-for="(log, index) in filteredRiwayat" :key="log.id" class="hover:bg-slate-50/50 transition">
              <td class="py-4 px-6 text-center font-bold text-slate-400 text-xs">{{ index + 1 }}</td>
              <td class="py-4 px-6">
                <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ log.barang ? log.barang.nama_barang : log.nama_barang }}</h4>
                <span class="text-xxs font-mono text-slate-400 block mt-1">Kode: {{ log.barang ? log.barang.kode_barang : log.kode_barang }}</span>
              </td>
              <td class="py-4 px-6 max-w-xs">
                <p class="text-xs text-slate-600 line-clamp-2">{{ log.keperluan || '-' }}</p>
              </td>
              <td class="py-4 px-6 text-center font-bold text-slate-900">
                {{ log.jumlah_pinjam || log.jumlah }} <span class="text-xs font-normal text-slate-400">unit</span>
              </td>
              <td class="py-4 px-6 space-y-1">
                <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                  <Calendar :size="12" class="text-indigo-500" /> Pinjam: {{ log.tanggal_pinjam || log.tgl_pinjam }}
                </div>
                <div class="flex items-center gap-1.5 text-xs font-medium text-slate-400">
                  <UserCheck :size="12" class="text-emerald-500" /> Kembali: {{ log.tanggal_kembali || log.tgl_kembali || '-' }}
                </div>
              </td>
              <td class="py-4 px-6 text-center">
                <span v-if="log.status && (log.status.toLowerCase() === 'selesai' || log.status.toLowerCase() === 'dikembalikan')" class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                  ✓ Selesai
                </span>
                <span v-else-if="log.status && log.status.toLowerCase() === 'terlambat'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                  ● Terlambat
                </span>
                <span v-else class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                  ● Aktif Dipinjam
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
