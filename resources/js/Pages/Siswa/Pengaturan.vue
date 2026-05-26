<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// State untuk toggle status notifikasi via checkbox/switch ringkas
const notifyEmail = ref(true);
const notifySystem = ref(true);

// Form handling untuk ganti password menggunakan Inertia useForm
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// Eksekusi simpan/update password
const updatePassword = () => {
    passwordForm.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            alert('Kata sandi berhasil diperbarui!');
        },
        onError: (errors) => {
            console.error('Gagal memperbarui kata sandi:', errors);
        }
    });
};
</script>

<template>
    <Head title="Pengaturan Sistem" />

    <AuthenticatedLayout>
        <div class="p-6 sm:p-8 max-w-[1000px] mx-auto space-y-8 text-gray-900">

            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Pengaturan Akun</h1>
                <p class="text-sm text-slate-500 mt-1">Gaya tren 2026 • Sesuaikan keamanan dan preferensi sistem Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <div class="lg:col-span-1 bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Navigasi Pengaturan</h3>
                    <div class="space-y-1">
                        <a href="#keamanan" class="flex items-center gap-3 px-4 py-3 bg-violet-50 text-indigo-600 rounded-2xl text-sm font-semibold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                            Keamanan Akun
                        </a>
                        <a href="#notifikasi" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-2xl text-sm font-semibold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                            Notifikasi Aplikasi
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">

                    <div id="keamanan" class="bg-white border border-slate-200/60 rounded-3xl p-6 sm:p-8 shadow-sm">
                        <form @submit.prevent="updatePassword" class="space-y-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Perbarui Kata Sandi</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Pastikan akun Anda menggunakan kata sandi yang panjang dan aman.</p>
                            </div>
                            <div class="border-b border-slate-100 pb-1"></div>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase">Kata Sandi Saat Ini</label>
                                    <input v-model="passwordForm.current_password" type="password" class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition bg-slate-50/50" autocomplete="current-password" />
                                    <span v-if="passwordForm.errors.current_password" class="text-xs text-red-500">{{ passwordForm.errors.current_password }}</span>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase">Kata Sandi Baru</label>
                                    <input v-model="passwordForm.password" type="password" class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition bg-slate-50/50" autocomplete="new-password" />
                                    <span v-if="passwordForm.errors.password" class="text-xs text-red-500">{{ passwordForm.errors.password }}</span>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-500 uppercase">Konfirmasi Kata Sandi Baru</label>
                                    <input v-model="passwordForm.password_confirmation" type="password" class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition bg-slate-50/50" autocomplete="new-password" />
                                    <span v-if="passwordForm.errors.password_confirmation" class="text-xs text-red-500">{{ passwordForm.errors.password_confirmation }}</span>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-100">
                                <button type="submit" :disabled="passwordForm.processing" class="bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-semibold text-sm px-6 py-3 rounded-2xl shadow-md active:scale-95 transition-all disabled:opacity-50">
                                    {{ passwordForm.processing ? 'Menyimpan...' : 'Perbarui Sandi' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="notifikasi" class="bg-white border border-slate-200/60 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Preferensi Notifikasi</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Atur bagaimana sistem mengabari Anda terkait peminjaman alat.</p>
                        </div>
                        <div class="border-b border-slate-100 pb-1"></div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-slate-50/60 rounded-2xl border border-slate-100">
                                <div class="space-y-0.5">
                                    <h4 class="text-sm font-bold text-slate-700">Notifikasi Email</h4>
                                    <p class="text-xs text-slate-400">Terima bukti transaksi peminjaman langsung ke email sekolah.</p>
                                </div>
                                <input type="checkbox" v-model="notifyEmail" class="w-5 h-5 accent-indigo-600 rounded cursor-pointer" />
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50/60 rounded-2xl border border-slate-100">
                                <div class="space-y-0.5">
                                    <h4 class="text-sm font-bold text-slate-700">Pemberitahuan Sistem</h4>
                                    <p class="text-xs text-slate-400">Aktifkan lonceng peringatan batas waktu pengembalian di web.</p>
                                </div>
                                <input type="checkbox" v-model="notifySystem" class="w-5 h-5 accent-indigo-600 rounded cursor-pointer" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
