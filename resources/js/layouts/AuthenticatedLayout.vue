<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <div class="mx-auto w-full max-w-[88rem] px-5 py-8 md:px-8 md:py-10">
            <div
                v-if="$slots.header"
                class="mb-8 rounded-[2rem] border border-border/80 bg-card/90 px-7 py-6 shadow-[0_24px_60px_-42px_rgba(15,23,42,0.16)] backdrop-blur md:px-9 md:py-7"
            >
                <slot name="header" />
            </div>

            <slot />
        </div>
    </AppLayout>
</template>
