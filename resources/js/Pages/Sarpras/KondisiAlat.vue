<script setup>
defineProps({
  riwayatKondisi: Array
});
</script>

<template>
  <section class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-6 bg-slate-50 border-b border-slate-200">
      <h3 class="text-lg font-bold text-slate-900">Log Pengawasan & Riwayat Kondisi Fisik Alat</h3>
      <p class="text-xs text-slate-500">Laporan berkala hasil inspeksi kelayakan barang oleh teknisi/toolman jurusan.</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse min-w-[700px]">
        <thead>
          <tr class="bg-rose-900 text-xxs sm:text-xs font-bold uppercase tracking-wider text-white">
            <th class="px-6 py-4">Nama Inventaris Alat</th>
            <th class="px-6 py-4">Kode Unik Barang</th>
            <th class="px-6 py-4">Kondisi</th>
            <th class="px-6 py-4">Detail Catatan / Kerusakan</th>
            <th class="px-6 py-4">Tgl Cek</th>
            <th class="px-6 py-4">Pemeriksa</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700">
          <tr v-for="log in riwayatKondisi" :key="log.id" class="hover:bg-slate-50 transition">
            <td class="px-6 py-4 font-bold text-slate-900">{{ log.nama_alat }}</td>
            <td class="px-6 py-4">
              <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xxs font-mono text-slate-600 border border-slate-200">
                {{ log.kode_barang }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span :class="{
                'bg-emerald-50 text-emerald-700 border-emerald-200': log.kondisi === 'Baik',
                'bg-amber-50 text-amber-700 border-amber-200': log.kondisi === 'Rusak Ringan',
                'bg-rose-50 text-rose-700 border-rose-200': log.kondisi === 'Rusak Berat'
              }" class="inline-block px-2.5 py-0.5 rounded-full text-xxs font-extrabold border">
                {{ log.kondisi }}
              </span>
            </td>
            <td class="px-6 py-4 text-xs italic text-slate-600 max-w-xs truncate">{{ log.keterangan }}</td>
            <td class="px-6 py-4 text-slate-500">{{ log.tgl_pengecekan }}</td>
            <td class="px-6 py-4 text-xs font-bold text-slate-800">{{ log.petugas }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
