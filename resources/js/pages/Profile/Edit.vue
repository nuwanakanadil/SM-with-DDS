<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import PageHero from '@/components/PageHero.vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { BadgeCheck, Palette } from 'lucide-vue-next';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold tracking-[-0.03em] text-foreground">Profile</h2>
        </template>

        <div class="space-y-8">
            <PageHero
                eyebrow="Account"
                title="Manage your profile and security"
                description="Update your account details, strengthen password settings, and control account access from one clean personal settings area."
            >
                <template #meta>
                    <div class="inline-flex items-center gap-2 rounded-full border border-border bg-card/85 px-4 py-2 text-sm font-semibold text-foreground">
                        <BadgeCheck class="size-4 text-primary" />
                        Personal settings
                    </div>
                </template>
            </PageHero>

            <div class="section-card p-7 sm:p-9">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-2xl"
                />
            </div>

            <div class="section-card p-7 sm:p-9">
                <UpdatePasswordForm class="max-w-2xl" />
            </div>

            <div class="section-card p-7 sm:p-9">
                <div class="max-w-2xl">
                    <div class="flex items-start gap-3">
                        <div class="rounded-xl bg-primary/10 p-2 text-primary">
                            <Palette class="size-5" />
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-foreground">
                                Appearance
                            </h3>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Choose light, dark, or system theme.
                            </p>
                        </div>
                    </div>

                    <div class="mt-5">
                        <AppearanceTabs />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>