<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import password from '@/routes/password';
import { Head, useForm } from '@inertiajs/vue3';
import { KeyRound, Mail, Send } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.submit(password.email());
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="space-y-8">
            <div class="space-y-3 text-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary text-primary shadow-sm">
                    <KeyRound class="size-6" />
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl font-extrabold text-foreground">
                        Reset your password
                    </h1>
                    <p class="text-sm leading-6 text-muted-foreground">
                        Enter your email and we’ll send you a password reset link.
                    </p>
                </div>
            </div>

            <div
                v-if="status"
                class="rounded-2xl border border-[#bbf7d0] bg-[#f0fdf4] px-4 py-3 text-sm font-medium text-[#16A34A]"
            >
                {{ status }}
            </div>

            <form class="space-y-5" @submit.prevent="submit">
                <div class="space-y-2">
                    <InputLabel for="email" value="Email" />
                    <div class="relative">
                        <Mail class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="pl-11"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email"
                        />
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-70': form.processing }"
                    :disabled="form.processing"
                >
                    <span>Send reset link</span>
                    <Send class="size-4" />
                </PrimaryButton>
            </form>
        </div>
    </GuestLayout>
</template>
