<script setup>
import { ref, computed } from 'vue';
import {
  LayoutDashboard, ShoppingBag, PlusCircle, History,
  LogOut, Menu, X, AlertTriangle
} from 'lucide-vue-next';

// IMPORT KOMPONEN PENDUKUNG KHUSUS SARPRAS YANG SUDAH DIPISAH
import UtamaSarpras from './UtamaSarpras.vue';
import PengajuanAlat from './PengajuanAlat.vue';
import KelolaAlatBaru from './KelolaAlatBaru.vue';
import RiwayatPembelian from './RiwayatPembelian.vue';
import KondisiAlat from './KondisiAlat.vue';

// State Navigasi & UI
const activeView = ref('dashboard');
const isSidebarOpen = ref(false);
const namaUser = ref('Pak Rudi (Admin Sarpras)');

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const navigateToView = (viewName) => {
  activeView.value = viewName;
  isSidebarOpen.value = false;
};

// ================= DATA STATE UTAMA SARPRAS =================

// 1. Data Pengajuan Pembelian Alat dari Jurusan
const daftarPengajuan = ref([
  { id: 1, nama_alat: 'Solder Station Atten AT937', jumlah: 5, tgl_pengajuan: '01 Juni 2026', estimasi_harga: 1750000, status: 'Menunggu Persetujuan', pemohon: 'TKJ' },
  { id: 2, nama_alat: 'Fluke 101 Digital Multimeter', jumlah: 3, tgl_pengajuan: '28 Mei 2026', estimasi_harga: 2400000, status: 'Disetujui', pemohon: 'Elektronika' },
  { id: 3, nama_alat: 'Oscilloscope Rigol DS1054Z', jumlah: 1, tgl_pengajuan: '15 Mei 2026', estimasi_harga: 6500000, status: 'Sudah Dibeli', pemohon: 'Mekatronika' }
]);

// 2. Data Alat Baru yang Baru Dibeli & Belum Masuk Inventaris Utama
const alatBaruDibeli = ref([
  { id: 201, nama_barang: 'Oscilloscope Rigol DS1054Z', jumlah: 1, asal_dana: 'BOS Reguler 2026', tgl_terima: '09 Juni 2026' },
  { id: 202, nama_barang: 'Fluke 101 Digital Multimeter', jumlah: 3, asal_dana: 'Komite Sekolah', tgl_terima: '08 Juni 2026' }
]);

// 3. Data Riwayat Kondisi Alat di Jurusan
const riwayatKondisi = ref([
  { id: 301, nama_alat: 'Multitester Digital Sanwa', kode_barang: 'TLS-003', kondisi: 'Rusak Ringan', tgl_pengecekan: '10 Juni 2026', keterangan: 'Layar LCD agak pudar, baterai baru diganti.', petugas: 'Teknisi Lab' },
  { id: 302, nama_alat: 'Proyektor Epson X41', kode_barang: 'PRJ-001', kondisi: 'Baik', tgl_pengecekan: '05 Juni 2026', keterangan: 'Lensa dibersihkan, tingkat kecerahan normal.', petugas: 'Toolman' },
  { id: 303, nama_alat: 'Power Supply DC 30V', kode_barang: 'ELC-005', kondisi: 'Rusak Berat', tgl_pengecekan: '20 Mei 2026', keterangan: 'Transformator terbakar akibat lonjakan arus.', petugas: 'Teknisi Lab' }
]);

// Kalkulasi Statistik untuk Counter Dashboard Atas
const statistikSarpras = computed(() => {
  return {
    totalPengajuan: daftarPengajuan.value.length,
    menungguPersetujuan: daftarPengajuan.value.filter(i => i.status === 'Menunggu Persetujuan').length,
    belumRegistrasi: alatBaruDibeli.value.length,
    alatBermasalah: riwayatKondisi.value.filter(i => i.kondisi !== 'Baik').length
  };
});

// Aksi simulasikan input kode barang
const handleMasukInventaris = (alat) => {
  const kodeBaru = prompt(`Masukkan Kode Barang/Aset untuk "${alat.nama_barang}":`, "INV-SMK2-");
  if (!kodeBaru) return;

  alert(`Berhasil! ${alat.nama_barang} dengan kode ${kodeBaru} telah dimasukkan ke daftar alat jurusan.`);
  alatBaruDibeli.value = alatBaruDibeli.value.filter(item => item.id !== alat.id);
};

const handleLogout = () => {
    alert('Berhasil keluar dari sesi Sarpras!');
    window.location.href = '/login';
};
</script>

<template>
  <div class="flex min-h-screen bg-[#F4F6FA] font-sans text-slate-800 relative overflow-x-hidden">
    <div v-if="isSidebarOpen" @click="toggleSidebar" class="fixed inset-0 bg-slate-900/40 z-40 lg:hidden transition-opacity duration-300"></div>

    <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="w-72 bg-white border-r border-slate-100 flex flex-col justify-between p-6 shrink-0 fixed inset-y-0 left-0 z-50 lg:static transition-transform duration-300 ease-in-out">
      <div>
        <div class="mb-10 px-2 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold tracking-wider text-slate-900">SARPRAS</h2>
            <p class="text-xs font-semibold text-indigo-600 tracking-widest uppercase">SMKN 2 Tasikmalaya</p>
          </div>
          <button @click="toggleSidebar" class="lg:hidden p-2 text-slate-500 hover:bg-slate-50 rounded-xl">
            <X :size="20" />
          </button>
        </div>

        <nav class="space-y-1">
          <p class="px-3 text-xxs font-bold text-slate-400 uppercase tracking-wider mb-2">Menu Utama</p>

          <button @click="navigateToView('dashboard')"
            :class="activeView === 'dashboard' ? 'bg-cyan-50 text-cyan-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <LayoutDashboard :size="20" class="text-cyan-500" />
            Dashboard
          </button>

          <button @click="navigateToView('pengajuan')"
            :class="activeView === 'pengajuan' ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <ShoppingBag :size="20" :class="activeView === 'pengajuan' ? 'text-indigo-600' : 'text-indigo-500'" />
            Pengajuan Beli Alat
          </button>

          <button @click="navigateToView('kelola')"
            :class="activeView === 'kelola' ? 'bg-amber-50 text-amber-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <PlusCircle :size="20" :class="activeView === 'kelola' ? 'text-amber-600' : 'text-amber-500'" />
            Kelola Alat
          </button>

          <button @click="navigateToView('kondisi')"
            :class="activeView === 'kondisi' ? 'bg-rose-50 text-rose-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <AlertTriangle :size="20" :class="activeView === 'kondisi' ? 'text-rose-600' : 'text-rose-500'" />
            Kondisi Alat
          </button>

<button @click="navigateToView('riwayat_pembelian')"
  :class="activeView === 'riwayat_pembelian' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
  class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
  <History :size="20" :class="activeView === 'riwayat_pembelian' ? 'text-emerald-600' : 'text-emerald-500'" />
  Riwayat Pembelian
</button>

        </nav>
      </div>

      <button @click="handleLogout" class="flex items-center gap-3 px-4 py-3 rounded-xl text-rose-600 hover:bg-rose-50 font-semibold transition border border-dashed border-rose-100 mt-6">
        <LogOut :size="20" /> Keluar Panel
      </button>
    </aside>

    <main class="flex-1 p-4 sm:p-6 lg:p-10 overflow-y-auto w-full">
      <header class="flex items-center justify-between mb-8 gap-4">
        <div class="flex items-center gap-3">
          <button @click="toggleSidebar" class="lg:hidden p-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl shadow-xs">
            <Menu :size="22" />
          </button>
          <div>
            <h1 class="text-xl sm:text-3xl font-bold text-slate-900 tracking-tight">
              <span v-if="activeView === 'dashboard'">Dashboard Sarpras</span>
              <span v-else-if="activeView === 'pengajuan'">Daftar Pengajuan Alat Jurusan</span>
              <span v-else-if="activeView === 'kelola'">Registrasi Aset Barang Baru</span>
              <span v-else-if="activeView === 'kondisi'">Log Kondisi Fisik Inventaris</span>
              <span v-else-if="activeView === 'riwayat_pembelian'">Riwayat Pengadaan Alat</span>
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-0.5 hidden sm:block">
              Sesi Aktif: <span class="font-semibold text-indigo-600">{{ namaUser }}</span> (Petugas Sarpras) 👋
            </p>
          </div>
        </div>

        <div class="flex items-center gap-3 bg-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-2xl border border-slate-100 shadow-sm shrink-0">
          <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm sm:text-base">
            SP
          </div>
          <div class="text-left hidden md:block">
            <p class="text-sm font-bold text-slate-800 leading-tight">Administrator</p>
            <p class="text-xs text-emerald-500 font-medium flex items-center gap-1">● Hak Akses Penuh</p>
          </div>
        </div>
      </header>

      <div class="transition-all duration-300">
        <UtamaSarpras
          v-if="activeView === 'dashboard'"
          :statistikSarpras="statistikSarpras"
          @changeView="navigateToView"
        />

        <PengajuanAlat
          v-else-if="activeView === 'pengajuan'"
          :daftarPengajuan="daftarPengajuan"
        />

        <KelolaAlatBaru
          v-else-if="activeView === 'kelola'"
          :alatBaruDibeli="alatBaruDibeli"
          @registrasiAset="handleMasukInventaris"
        />

        <KondisiAlat
          v-else-if="activeView === 'kondisi'"
          :riwayatKondisi="riwayatKondisi"
        />
        <RiwayatPembelian
  v-else-if="activeView === 'riwayat_pembelian'"
/>
      </div>
    </main>
  </div>
</template>

<style>
.text-xxs { font-size: 0.65rem; }
</style>
