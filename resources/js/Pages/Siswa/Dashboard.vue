<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { LayoutDashboard, Wrench, QrCode, History, LogOut, Menu, X, Clock, AlertTriangle } from 'lucide-vue-next';

// Import komponen-komponen yang sudah dipisah
import HalamanUtama from './HalamanUtama.vue';
import AlatTersedia from './AlatTersedia.vue';
import RiwayatPinjaman from './RiwayatPinjaman.vue';
import AlatDipinjam from './AlatDipinjam.vue';
import BelumKembali from './BelumKembali.vue';
import ScanQR from './ScanQR.vue';

// State data Utama
const daftarBarang = ref([]);
const daftarRiwayat = ref([]); // Menyimpan riwayat transaksi peminjaman asli
const loading = ref(true);

// Data user login
const userId = ref(null);
const namaUser = ref('Siswa SMKN 2 Tasikmalaya');

// State Navigasi Multi-Halaman
const activeView = ref('dashboard');
const isSidebarOpen = ref(false);

// State untuk menampung kode barang yang dipilih secara otomatis
const selectedKodeBarang = ref('');

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

// =========================================================================
// SINKRONISASI 1: KALKULASI STATISTIK BERDASARKAN STATUS DATABASE LUMEN
// =========================================================================
const statistik = computed(() => {
  const riwayatArr = daftarRiwayat.value || [];
  const barangArr = daftarBarang.value || [];

  const alatTersedia = barangArr.filter(item => (item.jumlah || 0) > 0).length;

  // Lumen menggunakan status huruf kecil 'dipinjam' dan 'terlambat'
  const alatDipinjam = riwayatArr.filter(item => {
    const status = item.status ? item.status.toLowerCase() : '';
    return status === 'dipinjam';
  }).length;

  const belumKembali = riwayatArr.filter(item => {
    const status = item.status ? item.status.toLowerCase() : '';
    return status === 'terlambat';
  }).length;

  const totalRiwayat = riwayatArr.length;

  return {
    alatTersedia,
    alatDipinjam,
    belumKembali,
    totalRiwayat
  };
});

// =========================================================================
// SINKRONISASI 2: SATU FUNGSI UTAMA FETCH DATA (DENGAN BEARER TOKEN AMAN)
// =========================================================================
const fetchDataDashboard = async () => {
    try {
        loading.value = true;
        const token = localStorage.getItem('auth_token');
        if (!token) {
            alert('Sesi Anda habis atau Anda belum login!');
            window.location.href = '/login';
            return;
        }

        const configHeaders = {
            headers: { Authorization: `Bearer ${token}` }
        };

        // 1. Ambil data profil user login
        const userResponse = await axios.get('http://127.0.0.1:8000/api/profile', configHeaders);
        if (userResponse.data) {
          userId.value = userResponse.data.id;
          namaUser.value = userResponse.data.nama_user || userResponse.data.nama || 'Siswa';
        }

        // 2. Ambil data barang
        const barangResponse = await axios.get('http://127.0.0.1:8000/api/barang', configHeaders);
        daftarBarang.value = barangResponse.data.data || barangResponse.data || [];

        // 3. Ambil data riwayat transaksi peminjaman asli dari backend
        // Menggunakan endpoint /api/peminjaman yang diarahkan ke RiwayatPeminjamanController
        try {
          const riwayatResponse = await axios.get('http://127.0.0.1:8000/api/peminjaman', configHeaders);
          if (riwayatResponse.data) {
            const semuaRiwayat = riwayatResponse.data.data || riwayatResponse.data;

            if (Array.isArray(semuaRiwayat)) {
              // PERBAIKAN UTAMA: Konversi kedua ID ke tipe Number agar tidak gagal karena perbedaan tipe string vs int
              daftarRiwayat.value = semuaRiwayat.filter(item => Number(item.user_id) === Number(userId.value));
              console.log("Data riwayat berhasil dimuat & disinkronkan:", daftarRiwayat.value);
            } else {
              daftarRiwayat.value = [];
            }
          }
        } catch (e) {
          console.warn("API Peminjaman terkendala, mencoba endpoint fallback /api/peminjaman/riwayat...");
          try {
            const fallbackResponse = await axios.get('http://127.0.0.1:8000/api/peminjaman/riwayat', configHeaders);
            const fallbackData = fallbackResponse.data.data || fallbackResponse.data;
            if (Array.isArray(fallbackData)) {
              daftarRiwayat.value = fallbackData.filter(item => Number(item.user_id) === Number(userId.value));
            }
          } catch (err) {
            console.error("Seluruh endpoint riwayat gagal dimuat.", err);
            daftarRiwayat.value = [];
          }
        }

    } catch (error) {
        console.error("Gagal mengambil data dari API Utama:", error);
    } finally {
        loading.value = false;
    }
};

// Fungsi pembantu jika dipanggil dari komponen anak (seperti setelah Scan QR Sukses)
const muatDataUlang = async () => {
  await fetchDataDashboard();
};

// Jalankan fecth data secara terpusat saat halaman pertama kali dibuka
onMounted(async () => {
  await fetchDataDashboard();
});

// Fungsi untuk menangani klik tombol pinjam dari halaman Daftar Alat
const handleSelectBarang = (kodeBarang) => {
  selectedKodeBarang.value = kodeBarang ? kodeBarang.toUpperCase() : '';
  activeView.value = 'scan_qr';
};

const navigateToView = (viewName, kode = '') => {
  activeView.value = viewName;
  isSidebarOpen.value = false;

  if (viewName === 'scan_qr') {
    selectedKodeBarang.value = kode ? kode.toUpperCase() : '';
  } else {
    selectedKodeBarang.value = '';
  }

  // Ambil data terbaru secara berkala setiap kali berpindah menu halaman
  if (['dashboard', 'alat_tersedia', 'alat_dipinjam', 'belum_kembali', 'riwayat_pinjaman'].includes(viewName)) {
    fetchDataDashboard();
  }
};

const handleLogout = () => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user_role');
    alert('Berhasil keluar!');
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
            <h2 class="text-xl font-bold tracking-wider text-slate-900">SISWA</h2>
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

          <button @click="navigateToView('alat_tersedia')"
            :class="activeView === 'alat_tersedia' ? 'bg-indigo-50 text-indigo-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <Wrench :size="20" :class="activeView === 'alat_tersedia' ? 'text-indigo-600' : 'text-indigo-500'" />
            Alat Tersedia
          </button>

          <button @click="navigateToView('alat_dipinjam')"
            :class="activeView === 'alat_dipinjam' ? 'bg-amber-50 text-amber-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <Clock :size="20" :class="activeView === 'alat_dipinjam' ? 'text-amber-600' : 'text-amber-500'" />
            Alat Dipinjam
          </button>

          <button @click="navigateToView('belum_kembali')"
            :class="activeView === 'belum_kembali' ? 'bg-rose-50 text-rose-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <AlertTriangle :size="20" :class="activeView === 'belum_kembali' ? 'text-rose-600' : 'text-rose-500'" />
            Belum Kembali
          </button>

          <button @click="navigateToView('scan_qr')"
            :class="activeView === 'scan_qr' ? 'bg-violet-50 text-violet-600 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <QrCode :size="20" :class="activeView === 'scan_qr' ? 'text-violet-600' : 'text-violet-500'" />
            Scan QR
          </button>

          <button @click="navigateToView('riwayat_pinjaman')"
            :class="activeView === 'riwayat_pinjaman' ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition text-left">
            <History :size="20" :class="activeView === 'riwayat_pinjaman' ? 'text-emerald-600' : 'text-emerald-500'" />
            Riwayat Pinjaman
          </button>
        </nav>
      </div>

      <button @click="handleLogout" class="flex items-center gap-3 px-4 py-3 rounded-xl text-rose-600 hover:bg-rose-50 font-semibold transition border border-dashed border-rose-100 mt-6">
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
            <h1 class="text-xl sm:text-3xl font-bold text-slate-900 tracking-tight">
              <span v-if="activeView === 'dashboard'">Dashboard Siswa</span>
              <span v-else-if="activeView === 'alat_tersedia'">Daftar Alat Tersedia</span>
              <span v-else-if="activeView === 'alat_dipinjam'">Alat Sedang Dipinjam</span>
              <span v-else-if="activeView === 'belum_kembali'">Alat Terlambat Dikembalikan</span>
              <span v-else-if="activeView === 'scan_qr'">Layanan Scan QR</span>
              <span v-else-if="activeView === 'riwayat_pinjaman'">Riwayat Log Peminjaman</span>
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-0.5 hidden sm:block">
              Selamat datang kembali, <span class="font-semibold text-indigo-600">{{ namaUser }}</span> 👋
            </p>
          </div>
        </div>

        <div class="flex items-center gap-3 bg-white px-3 py-1.5 sm:px-4 sm:py-2 rounded-2xl border border-slate-100 shadow-sm shrink-0">
          <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm sm:text-base">
            {{ namaUser ? namaUser.charAt(0) : 'S' }}
          </div>
          <div class="text-left hidden md:block">
            <p class="text-sm font-bold text-slate-800 leading-tight">Siswa Aktif</p>
            <p class="text-xs text-emerald-500 font-medium flex items-center gap-1">● Online</p>
          </div>
        </div>
      </header>

      <div class="transition-all duration-300">
        <HalamanUtama
          v-if="activeView === 'dashboard'"
          :statistik="statistik"
          :daftarBarang="daftarBarang"
          :loading="loading"
          @changeView="navigateToView"
        />

        <AlatTersedia
          v-else-if="activeView === 'alat_tersedia'"
          :daftarBarang="daftarBarang"
          @changeView="navigateToView"
          @selectBarangToScan="handleSelectBarang"
        />

        <AlatDipinjam
          v-else-if="activeView === 'alat_dipinjam'"
          :daftarRiwayat="daftarRiwayat"
          @changeView="navigateToView"
        />

        <BelumKembali
          v-else-if="activeView === 'belum_kembali'"
          :daftarRiwayat="daftarRiwayat"
          @changeView="navigateToView"
        />

        <ScanQR
            v-else-if="activeView === 'scan_qr'"
            :userId="Number(userId)"
            :namaUser="namaUser"
            :defaultKode="selectedKodeBarang"
            @changeView="navigateToView"
            @refreshData="muatDataUlang"
        />

        <RiwayatPinjaman
          v-else-if="activeView === 'riwayat_pinjaman'"
          :daftarRiwayat="daftarRiwayat"
          @changeView="navigateToView"
        />
      </div>
    </main>
  </div>
</template>

<style>
.text-xxs { font-size: 0.65rem; }
</style>
