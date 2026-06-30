<template>
    <TooltipProvider :delay-duration="delayDuration">
        <Tooltip>
            <TooltipTrigger as-child>
                <slot />
            </TooltipTrigger>
            <TooltipContent :side="side"  v-if="!disabled">
                <p>{{ tooltip }}</p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>

<script setup lang="ts">
import Tooltip from './Tooltip.vue';
import TooltipContent from './TooltipContent.vue';
import TooltipProvider from './TooltipProvider.vue';
import TooltipTrigger from './TooltipTrigger.vue';

withDefaults(defineProps<{
    tooltip: string
    delayDuration?: number
    side?: 'top' | 'bottom' | 'left' | 'right';
    disabled?: boolean;
}>(), {
    delayDuration: 0,
    side: 'top',
    disabled: false,
});
</script>
