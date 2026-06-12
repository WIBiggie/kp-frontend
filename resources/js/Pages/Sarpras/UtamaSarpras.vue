<script setup>
import { ShoppingBag, PlusCircle, AlertTriangle, History, ArrowUpRight } from 'lucide-vue-next';

defineProps({
  statistikSarpras: Object
});

const emit = defineEmits(['changeView']);
</script>

<template>
  <div class="space-y-6">
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

      <div @click="emit('changeView', 'pengajuan')"
        class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-indigo-300 hover:shadow-sm cursor-pointer transition relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-indigo-500"></div>
        <div class="space-y-1 pl-2">
          <p class="text-xs sm:text-sm font-medium text-slate-400">Alat Pengajuan</p>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ statistikSarpras.totalPengajuan }}</h3>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition shrink-0">
          <ShoppingBag :size="20" />
        </div>
      </div>

      <div @click="emit('changeView', 'kelola')"
        class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-amber-400 hover:shadow-sm cursor-pointer transition relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-amber-500"></div>
        <div class="space-y-1 pl-2">
          <p class="text-xs sm:text-sm font-medium text-slate-400">Kelola Alat Masuk</p>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ statistikSarpras.belumRegistrasi || statistikSarpras.totalPengajuan }}</h3>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition shrink-0">
          <PlusCircle :size="20" />
        </div>
      </div>

      <div @click="emit('changeView', 'kondisi')"
        class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-rose-300 hover:shadow-sm cursor-pointer transition relative overflow-hidden">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-rose-500"></div>
        <div class="space-y-1 pl-2">
          <p class="text-xs sm:text-sm font-medium text-slate-400">Kondisi Alat</p>
          <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ statistikSarpras.alatBermasalah || 1 }}</h3>
        </div>
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition shrink-0">
          <AlertTriangle :size="20" />
        </div>
      </div>

      <div @click="emit('changeView', 'riwayat_pembelian')"
  class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-100 flex items-center justify-between group hover:border-emerald-300 hover:shadow-sm cursor-pointer transition relative overflow-hidden">
  <div class="absolute inset-y-0 left-0 w-1.5 bg-emerald-500"></div>
  <div class="space-y-1 pl-2">
    <p class="text-xs sm:text-sm font-medium text-slate-400">Riwayat Pembelian</p>
    <h3 class="text-xl sm:text-3xl font-extrabold text-slate-900">{{ statistikSarpras.totalRiwayat || 0 }}</h3>
  </div>
  <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition shrink-0">
    <History :size="20" />
  </div>
</div>

    </section>

    <div class="p-6 bg-indigo-900 text-white rounded-3xl flex flex-col md:flex-row items-center justify-between gap-4 shadow-md">
      <div class="space-y-1 text-center md:text-left">
        <h3 class="text-lg sm:text-xl font-bold flex items-center gap-2 justify-center md:justify-start">
          Sistem Manajemen Sarpras SMKN 2
        </h3>
        <p class="text-slate-400 text-xs sm:text-sm">Silakan gunakan menu sebelah kiri atau klik kartu statistik di atas untuk memproses data jurusan.</p>
      </div>
      <div class="flex gap-2">
        <button @click="emit('changeView', 'pengajuan')" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-slate-950 text-xs font-bold rounded-xl transition flex items-center gap-1">
          Cek Pengajuan <ArrowUpRight :size="14" />
        </button>
      </div>
    </div>
  </div>
</template>
