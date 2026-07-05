<script setup lang="ts">
defineProps<{
    value?: string;
}>();
</script>

<template>
    <label class="block text-sm font-semibold tracking-[0.01em] text-foreground">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>