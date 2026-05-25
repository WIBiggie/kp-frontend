<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <div
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-cover bg-center px-4"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1920')"
    >
        <Head title="Forgot Password" />

        <div class="relative w-full max-w-[420px] overflow-hidden rounded-[24px] border border-white/15 bg-white/10 p-8 sm:p-10 shadow-2xl backdrop-blur-[20px] transition-all duration-300">

            <div class="mb-6 text-center">
                <h2 class="text-[28px] font-semibold tracking-tight text-white">Reset Password</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-200">
                    Masukkan alamat email Anda dan kami akan mengirimkan tautan pemulihan kata sandi.
                </p>
            </div>

            <div
                v-if="status"
                class="mb-6 rounded-xl bg-green-500/20 p-3 text-center text-sm font-medium text-green-300 border border-green-500/30"
            >
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-[22px]">

                <div>
                    <TextInput
                        id="email"
                        type="email"
                        class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-[14px] text-base text-white placeholder-white/50 outline-none transition-all duration-300 focus:border-[#6c5ce7] focus:bg-white/15 focus:ring-2 focus:ring-[#6c5ce7]/20"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Masukkan Alamat Email"
                    />
                    <InputError class="mt-2 text-red-300" :message="form.errors.email" />
                </div>

                <div class="pt-2">
                    <PrimaryButton
                        class="w-full justify-center rounded-xl bg-gradient-to-r from-[#6c5ce7] to-[#a29bfe] py-[14px] text-base font-semibold text-white shadow-md transition-all duration-200 hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(108,92,231,0.4)] active:translate-y-0 disabled:opacity-50"
                        :class="{ 'opacity-50': form.processing }"
                        :disabled="form.processing"
                    >
                        Kirim Link Reset
                    </PrimaryButton>
                </div>
            </form>

            <p class="mt-8 text-center text-sm text-gray-200">
                Kembali ke
                <Link
                    :href="route('login')"
                    class="font-medium text-[#a29bfe] hover:text-[#81ecec] hover:underline transition-colors duration-200"
                >
                    Halaman Masuk
                </Link>
            </p>
        </div>
    </div>
</template>
