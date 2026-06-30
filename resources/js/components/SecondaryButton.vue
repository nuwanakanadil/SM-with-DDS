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
        class="inline-flex min-h-12 items-center justify-center rounded-2xl border border-[#E2E8F0] bg-white px-6 py-3 text-[15px] font-semibold text-[#111827] shadow-sm transition hover:bg-[#EFF6FF] focus:outline-none focus:ring-4 focus:ring-[#DBEAFE] disabled:cursor-not-allowed disabled:opacity-60"
    >
        <slot />
    </button>
</template>
