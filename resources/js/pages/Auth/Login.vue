<script setup lang="ts">
import { store as loginStore } from '@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController';
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import InputError from '@/components/InputError.vue';
import SupportHotline from '@/components/SupportHotline.vue';
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
                <div
                    class="rounded-[1.75rem] border border-white/70 bg-white/90 px-5 py-4 shadow-[0_18px_50px_-30px_rgba(15,23,42,0.35)] backdrop-blur dark:border-white/12 dark:bg-white/6 dark:shadow-[0_24px_70px_-34px_rgba(0,0,0,0.9)]"
                >
                    <ApplicationLogo class="h-16 w-auto object-contain" />
                </div>
            </div>

            <Card
                class="overflow-hidden rounded-[2rem] border-white/70 bg-white/88 shadow-[0_30px_90px_-50px_rgba(15,23,42,0.4)] backdrop-blur dark:border-white/10 dark:bg-slate-950/78 dark:shadow-[0_36px_120px_-56px_rgba(0,0,0,0.92)]"
            >
                <CardHeader class="space-y-3 text-center">
                    <p class="eyebrow-label text-primary dark:text-sky-300">{{ APP_SYSTEM_SUBTITLE }}</p>
                    <CardTitle class="text-3xl font-bold tracking-[-0.04em] text-slate-950 dark:text-white">
                        Log in to {{ APP_BRAND_NAME }}
                    </CardTitle>
                    <CardDescription class="text-[15px] leading-7 text-slate-600 dark:text-slate-300">
                        Enter your email and password to access marks, rankings, and admin tools.
                    </CardDescription>
                </CardHeader>

                <CardContent>
                    <div
                        v-if="props.status"
                        class="mb-6 rounded-2xl border border-emerald-200/80 bg-emerald-50/90 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/12 dark:text-emerald-200"
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
                                    class="text-sm font-semibold text-primary underline-offset-4 transition hover:underline dark:text-sky-300"
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

                        <Button
                            class="min-h-12 w-full rounded-2xl bg-slate-950 text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100 text-[15px]"
                            :disabled="form.processing"
                        >
                            Log in
                        </Button>
                    </form>

                    <div class="mt-6">
                        <SupportHotline
                            compact
                            title="Need login help?"
                            description="If you are having trouble signing in or accessing your student account, call the hotline for quick support."
                        />
                    </div>
                </CardContent>
            </Card>
        </div>
    </GuestLayout>
</template>
