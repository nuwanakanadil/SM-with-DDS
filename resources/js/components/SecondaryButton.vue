<script setup lang="ts">
withDefaults(
    defineProps<{
        type?: 'button' | 'submit' | 'reset';
    }>(),
    {
        type: 'button',
    },
);
</script>

<template>
    <button
        :type="type"
        class="inline-flex min-h-12 items-center justify-center rounded-2xl border border-border bg-card px-6 py-3 text-[15px] font-semibold text-foreground shadow-sm transition hover:bg-muted focus:outline-none focus:ring-4 focus:ring-primary/20 disabled:cursor-not-allowed disabled:opacity-60"
    >
        <slot />
    </button>
</template>