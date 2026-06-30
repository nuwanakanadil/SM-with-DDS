<script setup lang="ts">
import { onMounted, ref } from 'vue';

const model = defineModel<string>({ required: true });

const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <input
        class="w-full rounded-2xl border border-[#E2E8F0] bg-white px-4 py-3.5 text-[15px] text-[#111827] shadow-sm transition focus:border-[#2563EB] focus:outline-none focus:ring-4 focus:ring-[#DBEAFE]"
        v-model="model"
        ref="input"
    />
</template>
