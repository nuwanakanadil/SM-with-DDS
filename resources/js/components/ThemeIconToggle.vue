<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';

const { resolvedAppearance, updateAppearance } = useAppearance();

const nextAppearance = computed(() => (resolvedAppearance.value === 'dark' ? 'light' : 'dark'));
const themeLabel = computed(() => `Switch to ${nextAppearance.value} mode`);

const toggleAppearance = () => {
    updateAppearance(nextAppearance.value);
};
</script>

<template>
    <Button
        type="button"
        variant="ghost"
        size="icon-sm"
        class="rounded-lg"
        :aria-label="themeLabel"
        :title="themeLabel"
        @click="toggleAppearance"
    >
        <Sun v-if="resolvedAppearance === 'dark'" class="size-4" />
        <Moon v-else class="size-4" />
    </Button>
</template>
