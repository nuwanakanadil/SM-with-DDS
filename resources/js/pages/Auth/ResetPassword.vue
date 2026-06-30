<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import password from '@/routes/password';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowRight, LockKeyhole, Mail, ShieldCheck } from 'lucide-vue-next';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.submit(password.store(), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="space-y-8">
            <div class="space-y-3 text-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary text-primary shadow-sm">
                    <ShieldCheck class="size-6" />
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl font-extrabold text-foreground">
                        Choose a new password
                    </h1>
                    <p class="text-sm leading-6 text-muted-foreground">
                        Secure your account with a fresh password and get back into the portal.
                    </p>
                </div>
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

                <div class="space-y-2">
                    <InputLabel for="password" value="New Password" />
                    <div class="relative">
                        <LockKeyhole class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="pl-11"
                            required
                            autocomplete="new-password"
                            placeholder="Create a new password"
                        />
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <div class="space-y-2">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm Password"
                    />
                    <div class="relative">
                        <LockKeyhole class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="pl-11"
                            required
                            autocomplete="new-password"
                            placeholder="Confirm your password"
                        />
                    </div>
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-70': form.processing }"
                    :disabled="form.processing"
                >
                    <span>Reset password</span>
                    <ArrowRight class="size-4" />
                </PrimaryButton>
            </form>
        </div>
    </GuestLayout>
</template>
