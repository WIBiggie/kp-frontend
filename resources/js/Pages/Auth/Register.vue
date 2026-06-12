<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-cover bg-center px-4 py-12"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1920')"
    >
        <Head title="Register" />

        <div class="relative w-full max-w-[440px] overflow-hidden rounded-[24px] border border-white/15 bg-white/10 p-8 sm:p-10 shadow-2xl backdrop-blur-[20px] transition-all duration-300">

            <div class="mb-8 text-center">
                <h2 class="text-[28px] font-semibold tracking-tight text-white">Buat Akun</h2>
                <p class="mt-2 text-sm text-gray-200">Mulai perjalanan Anda bersama kami</p>
            </div>

            <form @submit.prevent="submit" class="space-y-[20px]">

                <div>
                    <TextInput
                        id="name"
                        type="text"
                        class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-[14px] text-base text-white placeholder-white/50 outline-none transition-all duration-300 focus:border-[#6c5ce7] focus:bg-white/15 focus:ring-2 focus:ring-[#6c5ce7]/20"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Nama Lengkap"
                    />
                    <InputError class="mt-2 text-red-300" :message="form.errors.name" />
                </div>

                <div>
                    <TextInput
                        id="email"
                        type="email"
                        class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-[14px] text-base text-white placeholder-white/50 outline-none transition-all duration-300 focus:border-[#6c5ce7] focus:bg-white/15 focus:ring-2 focus:ring-[#6c5ce7]/20"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        placeholder="Alamat Email"
                    />
                    <InputError class="mt-2 text-red-300" :message="form.errors.email" />
                </div>

                <div>
                    <TextInput
                        id="password"
                        type="password"
                        class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-[14px] text-base text-white placeholder-white/50 outline-none transition-all duration-300 focus:border-[#6c5ce7] focus:bg-white/15 focus:ring-2 focus:ring-[#6c5ce7]/20"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="Password Baru"
                    />
                    <InputError class="mt-2 text-red-300" :message="form.errors.password" />
                </div>

                <div>
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-[14px] text-base text-white placeholder-white/50 outline-none transition-all duration-300 focus:border-[#6c5ce7] focus:bg-white/15 focus:ring-2 focus:ring-[#6c5ce7]/20"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Konfirmasi Password"
                    />
                    <InputError class="mt-2 text-red-300" :message="form.errors.password_confirmation" />
                </div>

                <div class="pt-2">
                    <PrimaryButton
                        class="w-full justify-center rounded-xl bg-gradient-to-r from-[#6c5ce7] to-[#a29bfe] py-[14px] text-base font-semibold text-white shadow-md transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(108,92,231,0.4)] active:translate-y-0 disabled:opacity-50"
                        :class="{ 'opacity-50': form.processing }"
                        :disabled="form.processing"
                    >
                        Daftar Akun
                    </PrimaryButton>
                </div>
            </form>

            <p class="mt-8 text-center text-sm text-gray-200">
                Sudah punya akun?
                <Link
                    :href="route('login')"
                    class="font-medium text-[#a29bfe] hover:text-[#81ecec] hover:underline transition-colors duration-200"
                >
                    Masuk di sini
                </Link>
            </p>
        </div>
    </div>
</template>
