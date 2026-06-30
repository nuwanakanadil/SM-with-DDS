<script setup lang="ts">
type Props = {
    eyebrow?: string;
    title: string;
    description?: string;
};

defineProps<Props>();
</script>

<template>
    <section class="relaxed-panel flex flex-col gap-4 px-4 py-4 md:flex-row md:items-end md:justify-between md:px-5 md:py-5">
        <div class="max-w-4xl space-y-2">
            <p
                v-if="eyebrow"
                class="text-[10px] font-semibold uppercase tracking-[0.16em] text-muted-foreground"
            >
                {{ eyebrow }}
            </p>
            <div class="space-y-1.5">
                <h1 class="text-[1.7rem] font-bold tracking-[-0.03em] text-foreground md:text-[1.9rem]">
                    {{ title }}
                </h1>
                <p
                    v-if="description"
                    class="max-w-3xl text-[13px] leading-5.5 text-muted-foreground"
                >
                    {{ description }}
                </p>
            </div>
        </div>

        <div
            v-if="$slots.actions || $slots.meta"
            class="flex flex-col items-start gap-2.5 md:items-end"
        >
            <div v-if="$slots.meta">
                <slot name="meta" />
            </div>
            <div v-if="$slots.actions" class="flex flex-wrap gap-2">
                <slot name="actions" />
            </div>
        </div>
    </section>
</template>
