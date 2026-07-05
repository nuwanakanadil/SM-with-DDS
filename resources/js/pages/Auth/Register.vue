<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import { login, register } from '@/routes';
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowRight, LockKeyhole, Mail, User } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.submit(register.post(), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="mx-auto flex w-full max-w-md flex-col items-center space-y-9">
            <div class="w-full space-y-3 text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-primary/80">
                    Create account
                </p>
                <div class="space-y-2">
                    <h1 class="text-4xl font-extrabold tracking-[-0.04em] text-foreground">
                        Join {{ APP_BRAND_NAME }}
                    </h1>
                    <p class="text-[15px] leading-7 text-muted-foreground">
                        Create access for the {{ APP_SYSTEM_SUBTITLE.toLowerCase() }}.
                    </p>
                </div>
            </div>

            <form class="w-full space-y-6" @submit.prevent="submit">
                <div class="space-y-2">
                    <InputLabel for="name" value="Full Name" />
                    <div class="relative">
                        <User class="pointer-events-none absolute left-4 top-1/2 size-[1.125rem] -translate-y-1/2 text-muted-foreground" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="pl-12"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Enter your full name"
                        />
                    </div>
                    <InputError :message="form.errors.name" />
                </div>

                <div class="space-y-2">
                    <InputLabel for="email" value="Email" />
                    <div class="relative">
                        <Mail class="pointer-events-none absolute left-4 top-1/2 size-[1.125rem] -translate-y-1/2 text-muted-foreground" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="pl-12"
                            required
                            autocomplete="username"
                            placeholder="Enter your email"
                        />
                    </div>
                    <InputError :message="form.errors.email" />
                </div>

                <div class="space-y-2">
                    <InputLabel for="password" value="Password" />
                    <div class="relative">
                        <LockKeyhole class="pointer-events-none absolute left-4 top-1/2 size-[1.125rem] -translate-y-1/2 text-muted-foreground" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="pl-12"
                            required
                            autocomplete="new-password"
                            placeholder="Create a password"
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
                        <LockKeyhole class="pointer-events-none absolute left-4 top-1/2 size-[1.125rem] -translate-y-1/2 text-muted-foreground" />
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="pl-12"
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
                    <span>Create account</span>
                    <ArrowRight class="size-4" />
                </PrimaryButton>
            </form>

            <p class="text-center text-sm text-muted-foreground">
                Already have an account?
                <Link :href="login()" class="font-semibold text-primary transition hover:text-primary/80">
                    Log in
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
