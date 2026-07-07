<script setup lang="ts">
import { PhoneCall } from 'lucide-vue-next';

defineProps<{
    title?: string;
    description?: string;
    compact?: boolean;
}>();

const hotlineNumber = '0750623988';
</script>

<template>
    <div
        :class="compact
            ? 'rounded-xl border border-border/60 bg-secondary/35 px-3.5 py-2.5'
            : 'rounded-xl border border-border/60 bg-secondary/30 px-4 py-3'"
    >
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm">
            <div class="flex items-center gap-2 text-muted-foreground">
                <PhoneCall class="size-3.5" />
                <span class="font-medium text-foreground">
                    {{ title || 'Need help?' }}
                </span>
            </div>
            <span class="text-muted-foreground">
                {{ description || 'Call student support:' }}
            </span>
            <a
                :href="`tel:${hotlineNumber}`"
                class="font-semibold text-primary underline-offset-4 transition hover:underline dark:text-sky-300"
            >
                {{ hotlineNumber }}
            </a>
        </div>
    </div>
</template>
