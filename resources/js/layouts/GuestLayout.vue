<script setup lang="ts">
import ThemeIconToggle from '@/components/ThemeIconToggle.vue';
</script>

<template>
    <div
        class="relative flex min-h-screen items-center justify-center bg-[radial-gradient(circle_at_top_left,rgba(37,99,235,0.16),transparent_34%),linear-gradient(135deg,#F8FAFC_0%,#EFF6FF_100%)] px-6 py-12 dark:bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.2),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.14),transparent_26%),linear-gradient(135deg,#020617_0%,#0F172A_52%,#111827_100%)]"
    >
        <div class="fixed right-3 top-3 z-50 rounded-xl border border-border bg-card/88 p-1 shadow-sm backdrop-blur-md sm:right-5 sm:top-5 dark:bg-card/78">
            <ThemeIconToggle />
        </div>
        <div class="w-full max-w-md">
            <slot />
        </div>
    </div>
</template>
