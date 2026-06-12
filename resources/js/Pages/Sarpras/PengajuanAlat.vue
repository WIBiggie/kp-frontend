<script setup>
defineProps({
  daftarPengajuan: Array
});

const formatRupiah = (angka) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka);
};
</script>

<template>
  <section class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-6 bg-slate-50 border-b border-slate-200">
      <h3 class="text-lg font-bold text-slate-900">Daftar Ajuan Beli Alat dari Jurusan</h3>
      <p class="text-xs text-slate-500">Gunakan daftar di bawah ini untuk memvalidasi kebutuhan pengadaan alat baru sekolah.</p>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse min-w-[700px]">
        <thead>
          <tr class="bg-indigo-900 text-xxs sm:text-xs font-bold uppercase tracking-wider text-white">
            <th class="px-6 py-4">Nama Alat / Barang</th>
            <th class="px-6 py-4">Asal Jurusan</th>
            <th class="px-6 py-4 text-center">Volume</th>
            <th class="px-6 py-4">Estimasi Harga</th>
            <th class="px-6 py-4">Tgl Usulan</th>
            <th class="px-6 py-4 text-center">Status Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700">
          <tr v-for="item in daftarPengajuan" :key="item.id" class="hover:bg-slate-50/80 transition">
            <td class="px-6 py-4 font-bold text-slate-900">{{ item.nama_alat }}</td>
            <td class="px-6 py-4 font-semibold text-indigo-600">Kejuruan {{ item.pemohon }}</td>
            <td class="px-6 py-4 text-center font-bold bg-slate-50/50">{{ item.jumlah }} unit</td>
            <td class="px-6 py-4 font-bold text-emerald-600">{{ formatRupiah(item.estimasi_harga) }}</td>
            <td class="px-6 py-4 text-slate-500">{{ item.tgl_pengajuan }}</td>
            <td class="px-6 py-4 text-center">
              <span :class="{
                'bg-amber-50 text-amber-700 border-amber-200': item.status === 'Menunggu Persetujuan',
                'bg-blue-50 text-blue-700 border-blue-200': item.status === 'Disetujui',
                'bg-emerald-50 text-emerald-700 border-emerald-200': item.status === 'Sudah Dibeli'
              }" class="inline-block px-3 py-1 rounded-full text-xxs font-bold border">
                {{ item.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
