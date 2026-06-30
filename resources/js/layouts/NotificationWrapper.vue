<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { toast, Toaster } from 'vue-sonner';
import 'vue-sonner/style.css';

const page = usePage();

const flash = computed(() => page.props.flash);

watch(
    () => flash.value,
    (messages) => {
        if (!messages) {
            return;
        }

        toast.dismiss();

        if (messages.success) {
            toast.success(messages.success);
            return;
        }

        if (messages.error) {
            toast.error(messages.error);
            return;
        }

        if (messages.warning) {
            toast.warning(messages.warning);
            return;
        }

        if (messages.info) {
            toast.info(messages.info);
        }
    },
    { immediate: true, deep: true },
);
</script>

<template>
    <div>
        <Toaster position="top-right" :duration="5000" />
        <slot />
    </div>
</template>
