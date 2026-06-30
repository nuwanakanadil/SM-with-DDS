<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import Checkbox from '@/components/Checkbox.vue';
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import { store as loginStore } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import password from '@/routes/password';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.submit(loginStore(), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mx-auto w-full">
            <div class="mb-10 flex justify-center">
                <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-white">
                    <ApplicationLogo class="h-12 w-12 object-contain" />
                </div>
            </div>

            <div class="space-y-2 text-center">
                <h1 class="text-[2rem] font-semibold tracking-[-0.03em] text-[#111827]">
                    Log in to your {{ APP_BRAND_NAME }} account
                </h1>
                <p class="text-[15px] leading-7 text-[#64748B]">
                    Enter your email and password below to log in to the {{ APP_SYSTEM_SUBTITLE.toLowerCase() }}.
                </p>
            </div>

            <div
                v-if="status"
                class="mt-8 rounded-xl border border-[#BBF7D0] bg-[#F0FDF4] px-4 py-3 text-sm font-medium text-[#16A34A]"
            >
                {{ status }}
            </div>

            <form class="mt-10 space-y-6" @submit.prevent="submit">
                <div class="space-y-3">
                    <InputLabel for="email" value="Email Address" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Email@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <InputLabel for="password" value="Password" />
                        <Link
                            v-if="canResetPassword"
                            :href="password.request()"
                            class="text-sm font-medium text-[#111827] underline underline-offset-2 transition hover:text-[#2563EB]"
                        >
                            Forgot password?
                        </Link>
                    </div>
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <label class="flex items-center gap-3 text-sm font-medium text-[#111827]">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span>Remember Me</span>
                </label>

                <button
                    type="submit"
                    class="inline-flex min-h-12 w-full items-center justify-center rounded-lg border border-[#2563EB] bg-[#2563EB] px-6 py-3 text-[15px] font-semibold text-white shadow-[0_16px_32px_-18px_rgba(37,99,235,0.5)] transition hover:bg-[#1D4ED8] focus:outline-none focus:ring-4 focus:ring-[#DBEAFE] disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Log In
                </button>
            </form>
        </div>
    </GuestLayout>
</template>
