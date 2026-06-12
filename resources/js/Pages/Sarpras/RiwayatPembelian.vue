<script setup>
import { ref } from 'vue';
import { CheckCircle } from 'lucide-vue-next';

// Data Mockup riwayat alat yang sukses dibeli dan selesai diproses oleh Sarpras
const riwayatPembelian = ref([
  { id: 401, nama_alat: 'Oscilloscope Rigol DS1054Z', jumlah: 1, total_harga: 6500000, tgl_selesai: '09 Juni 2026', anggaran: 'BOS Reguler 2026', pemohon: 'Mekatronika', kode_aset: 'INV-SMK2-MEK01' },
  { id: 402, nama_alat: 'Solder Dekko 40W', jumlah: 10, total_harga: 1200000, tgl_selesai: '01 Juni 2026', anggaran: 'BOS Provinsi', pemohon: 'Elektronika', kode_aset: 'INV-SMK2-ELK05' },
  { id: 403, nama_alat: 'Router Board Mikrotik RB951', jumlah: 5, total_harga: 4500000, tgl_selesai: '15 Mei 2026', anggaran: 'Komite Sekolah', pemohon: 'TKJ', kode_aset: 'INV-SMK2-TKJ12' }
]);

const formatRupiah = (angka) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka);
};
</script>

<template>
  <section class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-6 bg-slate-50 border-b border-slate-200">
      <h3 class="text-lg font-bold text-slate-900">Riwayat Pengadaan & Pembelian Alat</h3>
      <p class="text-xs text-slate-500">Arsip records pembelian alat jurusan yang telah sukses diregistrasikan ke dalam kode aset inventaris sekolah.</p>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse min-w-[800px]">
        <thead>
          <tr class="bg-emerald-900 text-xxs sm:text-xs font-bold uppercase tracking-wider text-white">
            <th class="px-6 py-4">Nama Alat / Barang</th>
            <th class="px-6 py-4">Kode Aset Terbit</th>
            <th class="px-6 py-4">Jurusan Pemohon</th>
            <th class="px-6 py-4 text-center">Jumlah</th>
            <th class="px-6 py-4">Total Biaya</th>
            <th class="px-6 py-4">Sumber Dana</th>
            <th class="px-6 py-4">Tanggal Rampung</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700">
          <tr v-for="item in riwayatPembelian" :key="item.id" class="hover:bg-slate-50 transition">
            <td class="px-6 py-4">
              <div class="font-bold text-slate-900">{{ item.nama_alat }}</div>
            </td>
            <td class="px-6 py-4">
              <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-xxs font-mono text-emerald-700 border border-emerald-200 font-bold">
                {{ item.kode_aset }}
              </span>
            </td>
            <td class="px-6 py-4 font-medium text-slate-600">Teknik {{ item.pemohon }}</td>
            <td class="px-6 py-4 text-center font-bold">{{ item.jumlah }} unit</td>
            <td class="px-6 py-4 font-bold text-emerald-600">{{ formatRupiah(item.total_harga) }}</td>
            <td class="px-6 py-4">
              <span class="text-xxs font-semibold px-2 py-0.5 bg-slate-100 text-slate-600 rounded border border-slate-200 uppercase">
                {{ item.anggaran }}
              </span>
            </td>
            <td class="px-6 py-4 text-slate-500 font-medium">{{ item.tgl_selesai }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>
