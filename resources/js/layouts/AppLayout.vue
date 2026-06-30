<script setup lang="ts">
import TooltipProvider from '@/components/ui/tooltip/TooltipProvider.vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';
import NotificationWrapper from './NotificationWrapper.vue';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <NotificationWrapper>
        <TooltipProvider>
            <AppLayout :breadcrumbs="breadcrumbs">
                <slot />
            </AppLayout>
        </TooltipProvider>
    </NotificationWrapper>
</template>
