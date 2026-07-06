<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import profile from '@/routes/profile';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();

type SettingsTab = 'profile' | 'password' | 'appearance';

const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Profile settings', href: profile.edit.url() },
];

const activeTab = ref<SettingsTab>('profile');

const tabs = [
    { key: 'profile', label: 'Profile' },
    { key: 'password', label: 'Password' },
    { key: 'appearance', label: 'Appearance' },
] as const;

const titles: Record<SettingsTab, { title: string; description: string }> = {
    profile: {
        title: 'Profile information',
        description: 'Update your name, photo and email address',
    },
    password: {
        title: 'Update password',
        description: 'Ensure your account is using a long, random password to stay secure',
    },
    appearance: {
        title: 'Appearance settings',
        description: "Update your account's appearance settings",
    },
};

const activeCopy = computed(() => titles[activeTab.value]);

const setActiveTab = (tab: SettingsTab) => {
    activeTab.value = tab;

    if (typeof window !== 'undefined') {
        window.location.hash = tab;
    }
};

onMounted(() => {
    if (typeof window === 'undefined') {
        return;
    }

    const hash = window.location.hash.replace('#', '');

    if (hash === 'profile' || hash === 'password' || hash === 'appearance') {
        activeTab.value = hash;
    }
});
</script>

<template>
    <Head title="Profile" />

    <AppLayout :breadcrumbs="breadcrumbItems">
        <h1 class="sr-only">Profile Settings</h1>

        <SettingsLayout>
            <template #header>
                <Heading title="Account Settings" description="Manage your account settings" />
            </template>

            <template #sidebar>
                <Button
                    v-for="tab in tabs"
                    :key="tab.key"
                    variant="ghost"
                    :class="['w-full justify-start', { 'bg-muted text-foreground hover:bg-muted': activeTab === tab.key }]"
                    as-child
                >
                    <Link :href="`#${tab.key}`" @click="setActiveTab(tab.key)">
                        {{ tab.label }}
                    </Link>
                </Button>
            </template>

            <div v-show="activeTab === 'profile'" class="flex flex-col space-y-6">
                <Heading
                    variant="small"
                    :title="activeCopy.title"
                    :description="activeCopy.description"
                />

                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </div>

            <div v-show="activeTab === 'password'" class="space-y-10">
                <Heading
                    variant="small"
                    :title="activeCopy.title"
                    :description="activeCopy.description"
                />

                <UpdatePasswordForm />
            </div>

            <div v-show="activeTab === 'appearance'" class="space-y-6">
                <Heading
                    variant="small"
                    :title="activeCopy.title"
                    :description="activeCopy.description"
                />

                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
