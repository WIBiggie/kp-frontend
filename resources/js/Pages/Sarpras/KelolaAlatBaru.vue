<script setup>
import { CheckCircle, PlusCircle } from 'lucide-vue-next';

defineProps({
  alatBaruDibeli: Array
});

const emit = defineEmits(['registrasiAset']);
</script>

<template>
  <section class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-6 bg-slate-50 border-b border-slate-200">
      <h3 class="text-lg font-bold text-slate-900">Registrasi Kode Aset Hasil Pembelian Baru</h3>
      <p class="text-xs text-slate-500">Klik tombol registrasi untuk memberikan tag nomor inventaris sekolah agar barang siap dipakai siswa.</p>
    </div>

    <div v-if="alatBaruDibeli.length === 0" class="py-16 text-center text-slate-400">
      <CheckCircle class="mx-auto text-emerald-500 mb-2" :size="40" />
      <p class="font-bold text-sm">Selesai! Semua barang pembelian baru sudah masuk gudang inventaris.</p>
    </div>

    <div v-else class="grid gap-4 p-6 bg-slate-50/40">
      <div v-for="alat in alatBaruDibeli" :key="alat.id" class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-white border border-slate-200 rounded-2xl gap-4 shadow-xs">
        <div class="space-y-1">
          <span class="inline-block text-xxs font-extrabold px-2 py-0.5 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded uppercase tracking-wider">{{ alat.asal_dana }}</span>
          <h4 class="text-base font-black text-slate-800">{{ alat.nama_barang }}</h4>
          <p class="text-xs text-slate-500">Diterima: <span class="font-medium text-slate-700">{{ alat.tgl_terima }}</span> — Kuantitas: <span class="font-bold text-slate-900">{{ alat.jumlah }} Unit</span></p>
        </div>
        <button @click="emit('registrasiAset', alat)" class="flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition whitespace-nowrap">
          <PlusCircle :size="16" /> Registrasi Kode Aset
        </button>
      </div>
    </div>
  </section>
</template>
