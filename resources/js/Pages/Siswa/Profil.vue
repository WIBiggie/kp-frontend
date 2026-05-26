<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// Mengambil data user dengan aman (jika auth kosong, pakai objek kosong {})
const pageProps = usePage().props;
const loggedInUser = pageProps.auth?.user || {};

// Data Dummy Otomatis jika database belum tersambung sempurna
const defaultStudent = {
    name: loggedInUser.name || 'Siswa SMKN 2',
    email: loggedInUser.email || 'siswa@smkn2-tsm.sch.id',
    nisn: loggedInUser.nisn || '0023411922',
    kelas: loggedInUser.kelas || 'XII Rekayasa Perangkat Lunak 2',
    avatar: null
};

// State untuk preview foto profil
const imagePreview = ref(null);
const fileInput = ref(null);

// Form handling menggunakan Inertia useForm
const form = useForm({
    name: defaultStudent.name,
    email: defaultStudent.email,
    kelas: defaultStudent.kelas,
    photo: null,
});

// Trigger klik input file hidden
const selectNewPhoto = () => {
    fileInput.value.click();
};

// Handle ganti foto langsung di browser
const updatePhotoPreview = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    form.photo = file;

    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
};

// Eksekusi simpan data
const submitProfile = () => {
    // Menggunakan post bawaan form inertia
    form.post(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            alert('Profil berhasil diperbarui!');
        },
        onError: (errors) => {
            console.error('Gagal menyimpan:', errors);
        }
    });
};
</script>

<template>
    <Head title="Profil Siswa" />

    <AuthenticatedLayout>
        <div class="p-6 sm:p-8 max-w-[1000px] mx-auto space-y-8 text-gray-900">

            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Profil Saya</h1>
                    <p class="text-sm text-slate-500 mt-1">Gaya tren 2026 • Kelola identitas dan foto akun Anda.</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center border-2 border-slate-300 shadow-sm cursor-pointer hover:bg-slate-300 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-slate-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <div class="lg:col-span-1 bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm flex flex-col items-center text-center space-y-4 relative overflow-hidden">
                    <div class="absolute top-0 inset-x-0 h-24 bg-gradient-to-r from-violet-500 to-indigo-600"></div>

                    <div class="relative mt-8 group cursor-pointer" @click="selectNewPhoto">
                        <div class="w-28 h-28 rounded-3xl overflow-hidden border-4 border-white shadow-md relative z-10 bg-slate-100 flex items-center justify-center">
                            <img v-if="imagePreview" :src="imagePreview" alt="Profile" class="w-full h-full object-cover" />
                            <div v-else class="text-3xl font-bold text-slate-400 uppercase">
                                {{ form.name ? form.name.charAt(0) : 'S' }}
                            </div>
                        </div>

                        <div class="absolute inset-0 rounded-3xl bg-black/40 z-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" /></svg>
                        </div>
                    </div>

                    <input type="file" ref="fileInput" class="hidden" accept="image/*" @change="updatePhotoPreview" />

                    <div class="z-10 pt-2">
                        <h2 class="text-xl font-bold text-slate-800">{{ form.name }}</h2>
                        <p class="text-xs font-semibold px-3 py-1 bg-violet-50 text-indigo-600 rounded-full inline-block mt-1">{{ form.kelas }}</p>
                    </div>

                    <div class="w-full border-t border-slate-100 pt-4 text-left space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">NISN</span>
                            <span class="font-semibold text-slate-700">{{ defaultStudent.nisn }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Akses</span>
                            <span class="font-semibold text-green-600 flex items-center gap-1">Siswa Aktif</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white border border-slate-200/60 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <form @submit.prevent="submitProfile" class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Detail Identitas</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase">Nama Lengkap</label>
                                <input v-model="form.name" type="text" class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition bg-slate-50/50" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase">Email</label>
                                <input v-model="form.email" type="email" class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition bg-slate-50/50" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase">NISN</label>
                                <input :value="defaultStudent.nisn" type="text" disabled class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl bg-slate-100 text-slate-400 cursor-not-allowed" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase">Kelas</label>
                                <input v-model="form.kelas" type="text" class="w-full px-4 py-3 text-sm border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none transition bg-slate-50/50" />
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-semibold text-sm px-6 py-3 rounded-2xl shadow-md active:scale-95 transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
