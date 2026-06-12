<script setup>
import { ref, onMounted, watch } from 'vue';
import { QrCode, Camera, CornerDownRight, CornerDownLeft, FileText, Upload, CheckCircle, Loader2, Keyboard } from 'lucide-vue-next';
// PERBAIKAN 1: Import Axios agar tidak memicu error "axios is not defined"
import axios from 'axios';

// PERBAIKAN 2: Daftarkan 'defaultKode' dan 'userId' ke dalam props agar sinkron dari Dashboard
const props = defineProps({
  userId: Number,
  namaUser: String,
  defaultKode: String
});

const emit = defineEmits(['changeView', 'refreshData']);

// State Alur Halaman
const currentStep = ref('scan'); // 'scan' | 'pilih_aksi' | 'form_pinjam' | 'form_kembali' | 'sukses'
const isLoading = ref(false);
const isManualInput = ref(false);

// Bukti Foto Pengembalian
const fotoPengembalian = ref(null);
const previewFoto = ref(null);

// Data barang reaktif
const barangDiscan = ref({
  id: null,
  nama_barang: '',
  kode_barang: props.defaultKode || '', // Langsung isi otomatis jika diklik dari Daftar Alat
  stok: 0
});

// Form input siswa
const formPinjam = ref({
  keperluan: '',
  jumlah_pinjam: 1
});

// =========================================================================
// FUNGSI 1: VERIFIKASI KODE KE DATABASE BACKEND
// =========================================================================
const handleVerifikasiKode = async () => {
  if (!barangDiscan.value.kode_barang) {
    alert('Silakan masukkan kode barang terlebih dahulu!');
    return;
  }

  try {
    isLoading.value = true;
    const token = localStorage.getItem('auth_token');

    // PERBAIKAN: Ubah URL endpoint sesuai dengan rute backend yang baru saja didaftarkan
    const response = await axios.get(`http://127.0.0.1:8000/api/barang/cari/${barangDiscan.value.kode_barang.toUpperCase()}`, {
        headers: { Authorization: `Bearer ${token}` }
    });

    const dataBarangAsli = response.data.data || response.data;

    if (dataBarangAsli) {
      barangDiscan.value = {
        id: dataBarangAsli.id,
        nama_barang: dataBarangAsli.nama_barang,
        kode_barang: dataBarangAsli.kode_barang,
        stok: dataBarangAsli.jumlah // Sinkronisasi field 'jumlah' dari DB ke variabel 'stok' Vue
      };

      alert(`Barang ditemukan: ${barangDiscan.value.nama_barang}`);
      currentStep.value = 'pilih_aksi';
    } else {
      alert('Barang tidak ditemukan di sistem database!');
    }

  } catch (error) {
    console.error("Gagal memverifikasi kode barang:", error);
    alert('Kode barang tidak terdaftar atau server backend mengalami kendala.');
  } finally {
    isLoading.value = false;
  }
};

// =========================================================================
// FUNGSI 2: KIRIM DATA TRANSAKSI ASLI KE API LUMEN (PINJAM / KEMBALI)
// =========================================================================
const handleSubmitAksi = async (jenis) => {
  isLoading.value = true;
  const token = localStorage.getItem('auth_token');

  // Mendapatkan tanggal hari ini dengan format YYYY-MM-DD sesuai tipe data 'date' di Lumen
  const tanggalHariIni = new Date().toISOString().split('T')[0];

  try {
    if (jenis === 'pinjam') {
      const API_URL = 'http://127.0.0.1:8000/api/peminjaman';

      // PASTIKAN MENGGUNAKAN METHOD: 'POST'
      const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({
          user_id: props.userId || 1,
          barang_id: barangDiscan.value.id,
          tanggal_pinjam: tanggalHariIni,
          jumlah_pinjam: formPinjam.value.jumlah_pinjam,
          keperluan: formPinjam.value.keperluan
        })
      });

      const result = await response.json();

      if (response.ok) {
        // Pemicu agar Dashboard.vue menarik data terbaru dari backend
        emit('refreshData');
        currentStep.value = 'sukses';
      } else {
        alert(result.message || 'Gagal mengirim pengajuan peminjaman!');
      }

    } else if (jenis === 'kembali') {
      isLoading.value = true;

      try {
        // 1. Ambil data semua peminjaman untuk mencari ID Peminjaman yang masih aktif ('dipinjam')
        const riwayatResponse = await axios.get('http://127.0.0.1:8000/api/peminjaman', {
          headers: { Authorization: `Bearer ${token}` }
        });

        const semuaRiwayat = riwayatResponse.data.data || riwayatResponse.data;

        // 2. Cari data peminjaman yang cocok dengan USER ini, BARANG ini, dan statusnya masih 'dipinjam'
        const peminjamanAktif = semuaRiwayat.find(item =>
          item.user_id === (props.userId || 1) &&
          item.barang_id === barangDiscan.value.id &&
          item.status === 'dipinjam'
        );

        if (!peminjamanAktif) {
          alert('Gagal! Anda tidak terdeteksi sedang meminjam alat ini, atau alat sudah dikembalikan sebelumnya.');
          isLoading.value = false;
          return;
        }

        // 3. Jika ketemu, gunakan ID asli dari database tersebut
        const peminjamanId = peminjamanAktif.id;
        const API_URL = `http://127.0.0.1:8000/api/pengembalian`;
        const formData = new FormData();

        // SINKRONISASI 100% DENGAN MIGRATION PENGEMBALIAN
        formData.append('peminjaman_id', peminjamanId);
        formData.append('tanggal_kembali', tanggalHariIni);
        formData.append('image_bukti_kembali', fotoPengembalian.value);
        formData.append('deskripsi_kembali', 'Alat dikembalikan dalam kondisi baik melalui Scan QR');

        const response = await fetch(API_URL, {
          method: 'POST',
          headers: {
            'Authorization': `Bearer ${token}`
          },
          body: formData
        });

        const result = await response.json();

        if (response.ok) {
          emit('refreshData'); // Tarik data terbaru setelah berhasil mengembalikan
          currentStep.value = 'sukses';
        } else {
          alert(result.message || 'Gagal memproses pengembalian alat!');
        }
      } catch (err) {
        console.error("Gagal mendeteksi status peminjaman aktif:", err);
        alert("Terjadi kesalahan sistem saat memverifikasi riwayat peminjaman Anda.");
      }
    }
  } catch (error) {
    console.error('Error transaksi:', error);
    alert('Koneksi terputus saat menghubungi server backend.');
  } finally {
    isLoading.value = false;
  }
};

// =========================================================================
// ACTION: REDIRECT KE DAFTAR ALAT DIPINJAM SAAT KLIK OKE SUKSES
// =========================================================================
const handleSuksesOke = () => {
  // 1. Alihkan tampilan utama dashboard langsung ke halaman daftar alat dipinjam
  emit('changeView', 'alat_dipinjam');

  // 2. Kembalikan state form ke awal (di background) agar bersih saat nanti buka scan QR lagi
  resetHalamanQR();
};

const periksaDanJalankanScan = () => {
  if (props.defaultKode) {
    barangDiscan.value.kode_barang = props.defaultKode;
    handleVerifikasiKode();
  }
};

onMounted(() => {
  periksaDanJalankanScan();
});

// Watch jika property defaultKode berubah saat component sedang aktif
watch(() => props.defaultKode, (newVal) => {
  if (newVal) {
    periksaDanJalankanScan();
  }
});

const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    fotoPengembalian.value = file;
    previewFoto.value = URL.createObjectURL(file);
  }
};

const resetHalamanQR = () => {
  currentStep.value = 'scan';
  isManualInput.value = false;
  barangDiscan.value = { id: null, nama_barang: '', kode_barang: '', stok: 0 };
  formPinjam.value = { keperluan: '', jumlah_pinjam: 1 };
  fotoPengembalian.value = null;
  previewFoto.value = null;
};
</script>

<template>
  <div>
    <div class="mb-6">
      <button @click="emit('changeView', 'dashboard')" class="text-sm font-semibold text-slate-500 hover:text-indigo-600 transition">
        ← Kembali ke Dashboard Utama
      </button>
    </div>

    <div class="max-w-xl mx-auto bg-white rounded-3xl border border-slate-100 shadow-xs p-6 sm:p-8">

      <div v-if="currentStep === 'scan'" class="text-center space-y-6">
        <div v-if="!isManualInput" class="space-y-6">
          <div>
            <h2 class="text-lg font-bold text-slate-900">Arahkan Kamera ke QR Code Alat</h2>
            <p class="text-xs text-slate-400 mt-1">Scan QR yang menempel pada mesin atau alat praktek.</p>
          </div>
          <div class="relative w-full aspect-square max-w-[260px] mx-auto bg-slate-900 rounded-2xl flex flex-col items-center justify-center text-slate-500 overflow-hidden border-4 border-indigo-50">
            <Camera :size="36" class="animate-pulse text-indigo-400 mb-2" />
            <span class="text-xxs font-mono tracking-widest">Mencari QR Code...</span>
            <div class="absolute inset-x-0 h-0.5 bg-indigo-500 shadow-md top-1/4 animate-[bounce_2s_infinite]"></div>
          </div>

          <div class="pt-2">
            <button @click="isManualInput = true" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-xl transition inline-flex items-center gap-1.5">
              <Keyboard :size="14" /> Kamera Error? Input Kode Manual
            </button>
          </div>
        </div>

        <div v-else class="space-y-5 py-4">
          <div class="text-center">
            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 mx-auto mb-3">
              <Keyboard :size="24" />
            </div>
            <h2 class="text-lg font-bold text-slate-900">Input Kode Alat Manual</h2>
            <p class="text-xs text-slate-400 mt-1">Masukkan kombinasi kode unik yang tertera di bawah stiker QR.</p>
          </div>

          <div class="max-w-xs mx-auto space-y-2">
            <input
              v-model="barangDiscan.kode_barang"
              type="text"
              class="w-full text-center px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-base font-mono font-bold tracking-wider uppercase focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition"
              placeholder="CONTOH: PRJ-001"
              @keyup.enter="handleVerifikasiKode"
            />
          </div>

          <div>
            <button @click="isManualInput = false" class="text-xs font-medium text-slate-400 hover:text-slate-600 transition">
              ← Kembali Menggunakan Kamera
            </button>
          </div>
        </div>

        <button
          :disabled="isLoading || (isManualInput && !barangDiscan.kode_barang)"
          @click="handleVerifikasiKode"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2 shadow-xs disabled:opacity-50"
        >
          <Loader2 v-if="isLoading" class="animate-spin" :size="16" />
          <QrCode v-else :size="16" />
          {{ isLoading ? 'Memverifikasi ke Database...' : (isManualInput ? 'Verifikasi Kode Alat' : '[Simulasi] Scan QR Otomatis') }}
        </button>
      </div>

      <div v-if="currentStep === 'pilih_aksi'" class="space-y-6">
        <div class="text-center">
          <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 font-mono text-xxs rounded border border-indigo-100">{{ barangDiscan.kode_barang }}</span>
          <h2 class="text-xl font-extrabold text-slate-900 mt-2">{{ barangDiscan.nama_barang }}</h2>
          <p class="text-xs text-slate-400 mt-0.5">Tentukan jenis keperluan aktivitas logistik Anda.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <button @click="currentStep = 'form_pinjam'" class="p-5 rounded-2xl border border-slate-100 hover:border-indigo-500 text-left space-y-3 group transition bg-slate-50/50 hover:bg-white">
            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition"><CornerDownRight :size="18" /></div>
            <div>
              <h4 class="font-bold text-slate-800 text-sm">Peminjaman Alat</h4>
              <p class="text-xxs text-slate-400 mt-0.5">Isi detail pengajuan pakai alat praktek.</p>
            </div>
          </button>
          <button @click="currentStep = 'form_kembali'" class="p-5 rounded-2xl border border-slate-100 hover:border-emerald-500 text-left space-y-3 group transition bg-slate-50/50 hover:bg-white">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition"><CornerDownLeft :size="18" /></div>
            <div>
              <h4 class="font-bold text-slate-800 text-sm">Pengembalian Alat</h4>
              <p class="text-xxs text-slate-400 mt-0.5">Kembalikan alat disertai foto bukti tempat.</p>
            </div>
          </button>
        </div>
      </div>

      <div v-if="currentStep === 'form_pinjam'" class="space-y-5">
        <div>
          <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2"><FileText class="text-indigo-600" :size="18" /> Form Peminjaman Alat</h2>
          <p class="text-xs text-slate-400">Verifikasi akun peminjaman inventaris sekolah.</p>
        </div>
        <div class="p-3.5 bg-indigo-50/60 rounded-xl border border-indigo-100 flex items-center gap-3">
          <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">{{ namaUser ? namaUser.charAt(0) : 'S' }}</div>
          <div>
            <p class="text-xxs font-bold text-indigo-600 uppercase tracking-wider">Penanggung Jawab</p>
            <p class="text-sm font-bold text-slate-800 leading-none mt-0.5">{{ namaUser }}</p>
          </div>
        </div>
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Jumlah Unit</label>
            <div class="flex items-center gap-3">
              <input v-model="formPinjam.jumlah_pinjam" type="number" min="1" :max="barangDiscan.stok" class="w-24 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-center focus:outline-none focus:ring-2 focus:ring-indigo-500" />
              <span class="text-xs text-slate-400">Maks tersedia: <b class="text-slate-600">{{ barangDiscan.stok }} unit</b></span>
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Keperluan Praktek / Judul Tugas</label>
            <textarea v-model="formPinjam.keperluan" rows="3" placeholder="Contoh: Pengujian sistem transmisi..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
          </div>
        </div>
        <div class="flex gap-3 pt-2">
          <button :disabled="isLoading" @click="currentStep = 'pilih_aksi'" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 py-2 rounded-xl text-sm font-semibold disabled:opacity-50">Kembali</button>
          <button :disabled="isLoading || !formPinjam.keperluan || formPinjam.jumlah_pinjam <= 0" @click="handleSubmitAksi('pinjam')" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl text-sm font-bold shadow-sm transition disabled:opacity-50 flex items-center justify-center gap-1.5">
            <Loader2 v-if="isLoading" class="animate-spin" :size="15" />
            {{ isLoading ? 'Memproses...' : 'Kirim Pengajuan' }}
          </button>
        </div>
      </div>

      <div v-if="currentStep === 'form_kembali'" class="space-y-5">
        <div>
          <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2"><Upload class="text-emerald-600" :size="18" /> Bukti Pengembalian Alat</h2>
          <p class="text-xs text-slate-400">Foto alat wajib dikembalikan rapi pada loker/rak semestinya.</p>
        </div>
        <div class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-6 bg-slate-50/50 hover:bg-slate-50 relative min-h-[180px]">
          <input type="file" accept="image/*" capture="environment" @change="handleFileChange" class="absolute inset-0 opacity-0 cursor-pointer z-10" />
          <div v-if="!previewFoto" class="text-center space-y-2">
            <Camera :size="28" class="mx-auto text-emerald-600" />
            <p class="text-sm font-bold text-slate-700">Ambil Foto Posisi Alat</p>
            <p class="text-xxs text-slate-400">Ketuk di sini untuk mengakses kamera smartphone</p>
          </div>
          <div v-else class="w-full space-y-2">
            <img :src="previewFoto" class="w-full aspect-video object-cover rounded-xl border" />
            <p class="text-xxs text-center text-emerald-600 font-semibold">✓ Gambar terekam dengan sukses.</p>
          </div>
        </div>
        <div class="flex gap-3 pt-2">
          <button :disabled="isLoading" @click="currentStep = 'pilih_aksi'" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 py-2 rounded-xl text-sm font-semibold disabled:opacity-50">Kembali</button>
          <button :disabled="isLoading || !fotoPengembalian" @click="handleSubmitAksi('kembali')" :class="fotoPengembalian ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-slate-200 text-slate-400 cursor-not-allowed'" class="flex-1 py-2 rounded-xl text-sm font-bold shadow-sm transition disabled:opacity-50 flex items-center justify-center gap-1.5">
            <Loader2 v-if="isLoading" class="animate-spin" :size="15" />
            {{ isLoading ? 'Memproses...' : 'Selesaikan Pengembalian' }}
          </button>
        </div>
      </div>

      <div v-if="currentStep === 'sukses'" class="text-center py-6 space-y-4">
        <CheckCircle :size="40" class="text-emerald-500 mx-auto animate-bounce" />
        <div>
          <h2 class="text-xl font-extrabold text-slate-900">Aksi Berhasil Direkam!</h2>
          <p class="text-xs text-slate-400 mt-1">Data log transaksi berhasil dikirim ke server Lumen API.</p>
        </div>
        <button @click="handleSuksesOke" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-bold text-sm shadow-md transition">
          Oke
        </button>
      </div>

    </div>
  </div>
</template>
