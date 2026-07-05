<script setup lang="ts">
import { store as loginStore } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import GuestLayout from '@/layouts/GuestLayout.vue';
import password from '@/routes/password';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
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

        <div class="w-full">
            <div class="mb-8 flex justify-center">
                <div class="rounded-2xl border border-border bg-card px-5 py-4 shadow-sm">
                    <ApplicationLogo class="h-16 w-auto object-contain" />
                </div>
            </div>

            <Card class="border-border/80 shadow-[0_24px_80px_-48px_rgba(15,23,42,0.45)]">
                <CardHeader class="space-y-3 text-center">
                    <p class="eyebrow-label text-primary">{{ APP_SYSTEM_SUBTITLE }}</p>
                    <CardTitle class="text-3xl font-bold tracking-[-0.04em]">
                        Log in to {{ APP_BRAND_NAME }}
                    </CardTitle>
                    <CardDescription class="text-[15px] leading-7">
                        Enter your email and password to access marks, rankings, and admin tools.
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <div
                        v-if="props.status"
                        class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700"
                    >
                        {{ props.status }}
                    </div>

                    <form class="space-y-6" @submit.prevent="submit">
                        <div class="space-y-2.5">
                            <Label for="email" required>Email address</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="email@example.com"
                            />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between gap-3">
                                <Label for="password" required>Password</Label>
                                <Link
                                    v-if="props.canResetPassword"
                                    :href="password.request()"
                                    class="text-sm font-semibold text-primary underline-offset-4 transition hover:underline"
                                >
                                    Forgot password?
                                </Link>
                            </div>
                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="password"
                            />
                            <InputError :message="form.errors.password" />
                        </div>

                        <label class="flex items-center gap-3 text-sm font-medium text-foreground">
                            <Checkbox v-model="form.remember" />
                            <span>Remember me</span>
                        </label>

                        <Button class="min-h-12 w-full rounded-2xl text-[15px]" :disabled="form.processing">
                            Log in
                        </Button>
                    </form>
                </CardContent>
            </Card>
        </div>
    </GuestLayout>
</template>
