<script setup>
import { computed } from 'vue';
import { UserCheck } from 'lucide-vue-next';

const props = defineProps({ dataBarang: Array, loading: Boolean });
const dataFilter = computed(() => {
  return props.dataBarang.filter(p => p.status === 'ditolak');
});
</script>

<template>
  <section class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-100 bg-slate-50/50">
      <h2 class="text-lg font-bold text-rose-700">Daftar Pengajuan Peminjaman Ditolak</h2>
      <p class="text-xs text-slate-400 mt-0.5">Daftar berkas permohonan peminjaman yang tidak disetujui jurusan.</p>
    </div>

    <div v-if="loading" class="py-12 text-center text-slate-400">Memuat data...</div>
    <div v-else-if="dataFilter.length === 0" class="py-12 text-center text-slate-400">
      <UserCheck class="mx-auto mb-2 text-slate-300" :size="36" />
      <p class="text-sm font-bold">Tidak ada pengajuan yang ditolak.</p>
    </div>

    <div v-else class="overflow-x-auto">
      <table class="w-full text-left border-collapse min-w-[700px]">
        <thead>
          <tr class="bg-slate-900 text-xs font-bold uppercase text-white">
            <th class="px-6 py-4 text-center w-16">No</th>
            <th class="px-6 py-4">Nama Siswa</th>
            <th class="px-6 py-4">Nama Barang</th>
            <th class="px-6 py-4 text-center">Volume</th>
            <th class="px-6 py-4 text-center">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
          <tr v-for="(p, i) in dataFilter" :key="p.id" class="hover:bg-slate-50/50">
            <td class="px-6 py-4 text-center font-bold text-slate-400 bg-slate-50/30">{{ i + 1 }}</td>
            <td class="px-6 py-4 font-bold text-slate-900">{{ p.nama_user || 'Siswa' }}</td>
            <td class="px-6 py-4 font-semibold">{{ p.nama_barang }}</td>
            <td class="px-6 py-4 text-center font-extrabold bg-slate-50/30">{{ p.jumlah_pinjam || 1 }} unit</td>
            <td class="px-6 py-4 text-center">
              <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-xxs font-bold uppercase">● {{ p.status }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
