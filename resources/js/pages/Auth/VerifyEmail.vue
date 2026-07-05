<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import { computed } from 'vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import { logout } from '@/routes';
import verification from '@/routes/verification';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { LogOut, MailCheck, RefreshCw } from 'lucide-vue-next';

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.submit(verification.send());
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="space-y-8">
            <div class="space-y-3 text-center">
                <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary text-primary shadow-sm">
                    <MailCheck class="size-6" />
                </div>
                <div class="space-y-2">
                    <h1 class="text-3xl font-extrabold text-foreground">
                        Verify your email
                    </h1>
                    <p class="text-sm leading-6 text-muted-foreground">
                        Check your inbox and click the verification link to activate your access to {{ APP_BRAND_NAME }}.
                    </p>
                </div>
            </div>

            <div
                v-if="verificationLinkSent"
                class="rounded-2xl border border-[#bbf7d0] bg-[#f0fdf4] px-4 py-3 text-sm font-medium text-[#16A34A]"
            >
                A fresh verification link for {{ APP_SYSTEM_SUBTITLE.toLowerCase() }} has been sent to your email address.
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-70': form.processing }"
                    :disabled="form.processing"
                >
                    <RefreshCw class="size-4" />
                    <span>Resend verification email</span>
                </PrimaryButton>

                <Link
                    :href="logout()"
                    as="button"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-border bg-card px-5 py-2.5 text-sm font-semibold text-foreground shadow-sm transition hover:bg-accent focus:outline-none focus:ring-4 focus:ring-primary/20"
                >
                    <LogOut class="size-4" />
                    <span>Log out</span>
                </Link>
            </form>
        </div>
    </GuestLayout>
</template>
