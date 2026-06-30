<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { store as confirmPasswordStore } from '@/actions/App/Http/Controllers/Auth/ConfirmablePasswordController';
import { Head, useForm } from '@inertiajs/vue3';
import { LockKeyhole, ShieldCheck } from 'lucide-vue-next';

const form = useForm({
    password: '',
});

const submit = () => {
    form.submit(confirmPasswordStore(), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="space-y-8">
            <div class="space-y-3 text-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary text-primary shadow-sm">
                    <ShieldCheck class="size-6" />
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl font-extrabold text-foreground">
                        Confirm your password
                    </h1>
                    <p class="text-sm leading-6 text-muted-foreground">
                        This action is protected. Enter your password to continue.
                    </p>
                </div>
            </div>

            <form class="space-y-5" @submit.prevent="submit">
                <div class="space-y-2">
                    <InputLabel for="password" value="Password" />
                    <div class="relative">
                        <LockKeyhole class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="pl-11"
                            required
                            autocomplete="current-password"
                            autofocus
                            placeholder="Enter your password"
                        />
                    </div>
                    <InputError :message="form.errors.password" />
                </div>

                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-70': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm
                </PrimaryButton>
            </form>
        </div>
    </GuestLayout>
</template>
