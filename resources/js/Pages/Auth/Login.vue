<script setup>
import { ref } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios';

const username = ref('');
const password = ref('');
const remember = ref(false);
const loading = ref(false);

// State untuk menampung pesan error dari backend
const errorMessage = ref('');
const errors = ref({}); // Menampung error validasi spesifik kolom

const submit = async () => {
    loading.value = true;
    errorMessage.value = '';
    errors.value = {};

    try {
        // Mengarah ke server backend Lumen port 8000 dengan prefix /api/login
        const response = await axios.post('http://127.0.0.1:8000/api/login', {
            email: username.value, // Backend divalidasi pakai key 'email'
            password: password.value,
            remember: remember.value
        });

        const tokenJWT = response.data.token;
        const roleUser = response.data.user.role;

        // Simpan ke localStorage kredensial user
        localStorage.setItem('auth_token', tokenJWT);
        localStorage.setItem('user_role', roleUser);

        alert('Selamat, login berhasil!');

        // PERBAIKAN: Pengalihan halaman disesuaikan ke rute internal Web Frontend kamu
        if (roleUser === 'jurusan') {
            window.location.href = '/jurusan/dashboardjurusan';
        } else if (roleUser === 'siswa') {
            window.location.href = '/siswa/dashboard';
        } else if (roleUser === 'sarpras' || roleUser === 'sapras') {
            window.location.href = '/sarpras/dashboardsarpras';
        } else {
            window.location.href = '/login';
        }
    } catch (error) {
        if (error.response) {
            if (error.response.status === 422) {
                // Menyimpan data error validasi objek
                errors.value = error.response.data.errors || error.response.data;
            } else {
                errorMessage.value = error.response.data.message || 'Login gagal, periksa kembali data Anda.';
            }
        } else {
            errorMessage.value = 'Tidak dapat terhubung ke server Backend. Pastikan kp-backend menyala di port 8000!';
        }
        password.value = '';
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-cover bg-center px-4"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1920')"
    >
        <div class="relative w-full max-w-[420px] overflow-hidden rounded-[24px] border border-white/15 bg-white/10 p-8 sm:p-10 shadow-2xl backdrop-blur-[20px] transition-all duration-300">

            <div class="mb-8 text-center">
                <h2 class="text-[28px] font-semibold tracking-tight text-white">Selamat Datang</h2>
                <p class="mt-2 text-sm text-gray-200">Silakan masuk ke akun Anda</p>
            </div>

            <div v-if="errorMessage" class="mb-4 rounded-xl bg-red-500/20 p-3 text-center text-sm font-medium text-red-300 border border-red-500/30">
                {{ errorMessage }}
            </div>

            <form @submit.prevent="submit" class="space-y-[22px]">
                <div>
                    <TextInput
                        id="username"
                        type="text"
                        class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-[14px] text-base text-white placeholder-white/50 outline-none transition-all duration-300 focus:border-[#6c5ce7] focus:bg-white/15 focus:ring-2 focus:ring-[#6c5ce7]/20"
                        v-model="username"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Email / Username Anda"
                    />
                    <InputError class="mt-2 text-red-300" :message="errors.email?.[0] || errors.email || errors.username?.[0] || errors.username" />
                </div>

                <div>
                    <TextInput
                        id="password"
                        type="password"
                        class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-[14px] text-base text-white placeholder-white/50 outline-none transition-all duration-300 focus:border-[#6c5ce7] focus:bg-white/15 focus:ring-2 focus:ring-[#6c5ce7]/20"
                        v-model="password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputError class="mt-2 text-red-300" :message="errors.password?.[0] || errors.password" />
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-gray-200 cursor-pointer select-none">
                        <Checkbox
                            name="remember"
                            v-model:checked="remember"
                            class="rounded border-white/10 bg-white/10 text-[#6c5ce7] focus:ring-[#6c5ce7]/30"
                        />
                        <span class="ms-2 text-sm">Ingat saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <PrimaryButton
                        class="w-full justify-center rounded-xl bg-gradient-to-r from-[#6c5ce7] to-[#a29bfe] py-[14px] text-base font-semibold text-white shadow-md transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(108,92,231,0.4)] active:translate-y-0 disabled:opacity-50"
                        :disabled="loading"
                    >
                        {{ loading ? 'Mengautentikasi...' : 'Masuk' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
