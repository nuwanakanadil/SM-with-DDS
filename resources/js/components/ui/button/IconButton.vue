<script setup lang="ts">
import type { PrimitiveProps } from 'reka-ui';
import type { HTMLAttributes, FunctionalComponent } from 'vue';
import type { LucideProps } from 'lucide-vue-next';
import type { ButtonVariants } from '.';
import type { UrlMethodPair } from '@inertiajs/core';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

interface Props extends PrimitiveProps {
    variant?: ButtonVariants['variant'];
    size?: ButtonVariants['size'];
    class?: HTMLAttributes['class'];
    icon: FunctionalComponent<LucideProps, {}, any, {}>;
    link?: string | UrlMethodPair;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    as: 'button',
});
</script>

<template>
    <Button v-if="props.link && !props.disabled" v-bind="{ ...props, asChild: true }">
        <Link :href="props.link">
            <component :is="props.icon" class="h-4 w-4" />
            <slot />
        </Link>
    </Button>
    <Button v-else v-bind="{ ...props }">
        <component :is="props.icon" class="h-4 w-4" />
        <slot />
    </Button>
</template>
