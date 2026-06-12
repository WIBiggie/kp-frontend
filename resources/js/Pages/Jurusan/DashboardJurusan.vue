<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import {
  LayoutDashboard,
  FileText,
  LogOut,
  Menu,
  X,
  CheckCircle,
  Clock,
  AlertCircle
} from 'lucide-vue-next';

// Import sub-halaman kaprodi yang dipisah
import SemuaLogKajur from './SemuaLogKajur.vue';
import ButuhValidasiKajur from './ButuhValidasiKajur.vue';
import DisetujuiKajur from './DisetujuiKajur.vue';
import DitolakKajur from './DitolakKajur.vue';

// State Navigasi Multi-Halaman
const activeView = ref('dashboard'); // 'dashboard', 'butuh_validasi', 'telah_disetujui', 'telah_ditolak'
const isSidebarOpen = ref(false);
const loading = ref(true);

// State Data dari API Backend
const daftarPengajuan = ref([]);
const namaKajur = ref('Ketua Jurusan SMKN 2');

const statistik = ref({
    totalPengajuan: 0,
    perluPersetujuan: 0,
    disetujui: 0,
    ditolak: 0
});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const navigateToView = (viewName) => {
    activeView.value = viewName;
    isSidebarOpen.value = false;
};

// Mengambil data dari API Lumen
const fetchPengajuanJurusan = async () => {
    try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
            alert('Sesi habis, silakan login kembali!');
            window.location.href = '/login';
            return;
        }

        const response = await axios.get('http://127.0.0.1:8000/api/pengajuan', {
            headers: { Authorization: `Bearer ${token}` }
        });

        daftarPengajuan.value = response.data.data || response.data;
        hitungStatistik();
    } catch (error) {
        console.error("Gagal memuat data pengajuan:", error);
    } finally {
        loading.value = false;
    }
};

const hitungStatistik = () => {
    statistik.value.totalPengajuan = daftarPengajuan.value.length;
    statistik.value.perluPersetujuan = daftarPengajuan.value.filter(p => p.status === 'pending' || p.status === 'proses').length;
    statistik.value.disetujui = daftarPengajuan.value.filter(p => p.status === 'disetujui' || p.status === 'disetujui_jurusan').length;
    statistik.value.ditolak = daftarPengajuan.value.filter(p => p.status === 'ditolak').length;
};

const handleLogout = () => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user_role');
    alert('Berhasil keluar!');
    window.location.href = '/login';
};

onMounted(() => {
    fetchPengajuanJurusan();
});
</script>

<template>
  <div class="flex min-h-screen bg-[#F8FAFC] font-sans text-slate-800 relative overflow-x-hidden">
    <div v-if="isSidebarOpen" @click="toggleSidebar" class="fixed inset-0 bg-slate-900/40 z-40 lg:hidden transition-opacity duration-300"></div>

    <aside :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="w-72 bg-white border-r border-slate-200/80 flex flex-col justify-between p-6 shrink-0 fixed inset-y-0 left-0 z-50 lg:static transition-transform duration-300 ease-in-out">
      <div>
        <div class="mb-10 px-2 flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold tracking-wider text-slate-900">KAPRODI</h2>
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
            <LayoutDashboard :size="20" :class="activeView === 'dashboard' ? 'text-cyan-500' : 'text-cyan-400'" />
            Dashboard Validasi
          </button>

          <button @click="navigateToView('butuh_validasi')"
            :class="activeView === 'butuh_validasi' ? 'bg-amber-50 text-amber-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <Clock :size="20" :class="activeView === 'butuh_validasi' ? 'text-amber-500' : 'text-amber-400'" />
            Butuh Validasi
          </button>

          <button @click="navigateToView('telah_disetujui')"
            :class="activeView === 'telah_disetujui' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <CheckCircle :size="20" :class="activeView === 'telah_disetujui' ? 'text-emerald-500' : 'text-emerald-400'" />
            Log Alat Disetujui
          </button>

          <button @click="navigateToView('telah_ditolak')"
            :class="activeView === 'telah_ditolak' ? 'bg-rose-50 text-rose-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <AlertCircle :size="20" :class="activeView === 'telah_ditolak' ? 'text-rose-500' : 'text-rose-400'" />
            Log Alat Ditolak
          </button>
        </nav>
      </div>

      <button @click="handleLogout" class="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-rose-600 hover:bg-rose-50 font-semibold transition border border-dashed border-rose-100 mt-6">
        <LogOut :size="20" /> Keluar Aplikasi
      </button>
    </aside>

    <main class="flex-1 p-4 sm:p-6 lg:p-10 overflow-y-auto w-full">
      <header class="flex items-center justify-between mb-8 gap-4">
        <div class="flex items-center gap-3">
          <button @click="toggleSidebar" class="lg:hidden p-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl shadow-xs">
            <Menu :size="22" />
          </button>
          <div>
            <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Ketua Jurusan</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-0.5 hidden sm:block">Panel Validasi Kompetensi Keahlian</p>
          </div>
        </div>

        <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-xs shrink-0">
          <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">KJ</div>
          <div class="text-left hidden md:block">
            <p class="text-sm font-bold text-slate-800 leading-tight">Ketua Jurusan</p>
            <p class="text-xs text-emerald-500 font-medium">● Online</p>
          </div>
        </div>
      </header>

      <section class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div @click="navigateToView('dashboard')"
          :class="activeView === 'dashboard' ? 'border-cyan-500 ring-2 ring-cyan-500/10 bg-indigo-50/10' : 'border-slate-200/80 bg-white'"
          class="p-4 sm:p-6 rounded-2xl border flex items-center justify-between group hover:border-cyan-300 cursor-pointer transition relative overflow-hidden">
          <div class="absolute inset-y-0 left-0 w-1.5 bg-cyan-500"></div>
          <div class="space-y-1 pl-2">
            <p class="text-xs sm:text-sm font-semibold text-slate-400">Total Ajuan</p>
            <h3 class="text-xl sm:text-3xl font-black text-slate-900">{{ statistik.totalPengajuan }}</h3>
          </div>
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white transition">
            <FileText :size="20" />
          </div>
        </div>

        <div @click="navigateToView('butuh_validasi')"
          :class="activeView === 'butuh_validasi' ? 'border-amber-400 ring-2 ring-amber-400/10 bg-amber-50/10' : 'border-slate-200/80 bg-white'"
          class="p-4 sm:p-6 rounded-2xl border flex items-center justify-between group hover:border-amber-400 cursor-pointer transition relative overflow-hidden">
          <div class="absolute inset-y-0 left-0 w-1.5 bg-amber-500"></div>
          <div class="space-y-1 pl-2">
            <p class="text-xs sm:text-sm font-semibold text-slate-400">Butuh Validasi</p>
            <h3 class="text-xl sm:text-3xl font-black text-slate-900">{{ statistik.perluPersetujuan }}</h3>
          </div>
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-600 group-hover:text-white transition">
            <Clock :size="20" />
          </div>
        </div>

        <div @click="navigateToView('telah_disetujui')"
          :class="activeView === 'telah_disetujui' ? 'border-emerald-400 ring-2 ring-emerald-400/10 bg-emerald-50/10' : 'border-slate-200/80 bg-white'"
          class="p-4 sm:p-6 rounded-2xl border flex items-center justify-between group hover:border-emerald-300 cursor-pointer transition relative overflow-hidden">
          <div class="absolute inset-y-0 left-0 w-1.5 bg-emerald-500"></div>
          <div class="space-y-1 pl-2">
            <p class="text-xs sm:text-sm font-semibold text-slate-400">Disetujui</p>
            <h3 class="text-xl sm:text-3xl font-black text-slate-900">{{ statistik.disetujui }}</h3>
          </div>
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
            <CheckCircle :size="20" />
          </div>
        </div>

        <div @click="navigateToView('telah_ditolak')"
          :class="activeView === 'telah_ditolak' ? 'border-rose-400 ring-2 ring-rose-400/10 bg-rose-50/10' : 'border-slate-200/80 bg-white'"
          class="p-4 sm:p-6 rounded-2xl border flex items-center justify-between group hover:border-rose-300 cursor-pointer transition relative overflow-hidden">
          <div class="absolute inset-y-0 left-0 w-1.5 bg-rose-500"></div>
          <div class="space-y-1 pl-2">
            <p class="text-xs sm:text-sm font-semibold text-slate-400">Ditolak</p>
            <h3 class="text-xl sm:text-3xl font-black text-slate-900">{{ statistik.ditolak }}</h3>
          </div>
          <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition">
            <AlertCircle :size="20" />
          </div>
        </div>
      </section>

      <div class="transition-all duration-300">
        <SemuaLogKajur v-if="activeView === 'dashboard'" :dataBarang="daftarPengajuan" :loading="loading" />
        <ButuhValidasiKajur v-else-if="activeView === 'butuh_validasi'" :dataBarang="daftarPengajuan" :loading="loading" @refreshData="fetchPengajuanJurusan" />
        <DisetujuiKajur v-else-if="activeView === 'telah_disetujui'" :dataBarang="daftarPengajuan" :loading="loading" />
        <DitolakKajur v-else-if="activeView === 'telah_ditolak'" :dataBarang="daftarPengajuan" :loading="loading" />
      </div>
    </main>
  </div>
</template>

<style>
.text-xxs { font-size: 0.65rem; }
</style>
