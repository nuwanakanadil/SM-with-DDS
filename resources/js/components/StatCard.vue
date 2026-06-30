<script setup lang="ts">
import {
    Card,
} from '@/components/ui/card';
import { cn } from '@/lib/utils';
import type { Component } from 'vue';

type Tone = 'default' | 'warning' | 'success';

type Props = {
    label: string;
    value: string | number;
    hint?: string;
    icon?: Component;
    tone?: Tone;
};

const props = withDefaults(defineProps<Props>(), {
    tone: 'default',
});

const toneClasses: Record<Tone, string> = {
    default: 'bg-[#EFF6FF] text-primary',
    warning: 'bg-[#FEF3C7] text-[#B45309]',
    success: 'bg-[#DCFCE7] text-[#15803D]',
};
</script>

<template>
    <Card class="gap-0 border-border/70 py-0">
        <div class="flex flex-row items-start justify-between gap-3 px-3.5 py-3.5 md:px-4 md:py-3.5">
            <div class="space-y-1.5">
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                    {{ label }}
                </p>
                <p class="text-[1.45rem] font-extrabold tracking-[-0.04em] text-foreground md:text-[1.7rem]">
                    {{ value }}
                </p>
            </div>

            <div
                v-if="icon"
                :class="
                    cn(
                        'flex h-8 w-8 shrink-0 items-center justify-center rounded-lg',
                        toneClasses[props.tone],
                    )
                "
            >
                <component :is="icon" class="size-3.5" />
            </div>
        </div>

        <div v-if="hint" class="px-3.5 pb-3.5 text-[12px] leading-4.5 text-muted-foreground md:px-4">
            {{ hint }}
        </div>
    </Card>
</template>
