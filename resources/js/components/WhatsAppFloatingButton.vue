<script setup lang="ts">
import { MessageCircleMore } from 'lucide-vue-next';
import { computed } from 'vue';

const props = withDefaults(defineProps<{
    floating?: boolean;
    label?: string;
    description?: string;
}>(), {
    floating: true,
    label: 'Chat on WhatsApp',
    description: 'Reach student support quickly for login, marks, or account help.',
});

const whatsappNumber = import.meta.env.VITE_WHATSAPP_NUMBER;
const whatsappMessage = import.meta.env.VITE_WHATSAPP_MESSAGE;

const whatsappHref = computed(() => {
    if (!whatsappNumber) {
        return null;
    }

    const normalizedNumber = String(whatsappNumber).replace(/[^\d]/g, '');
    const baseUrl = `https://wa.me/${normalizedNumber}`;

    if (!normalizedNumber) {
        return null;
    }

    return whatsappMessage
        ? `${baseUrl}?text=${encodeURIComponent(String(whatsappMessage))}`
        : baseUrl;
});
</script>

<template>
    <a
        v-if="whatsappHref"
        :href="whatsappHref"
        target="_blank"
        rel="noopener noreferrer"
        :class="floating
            ? 'fixed bottom-5 right-5 z-50 inline-flex h-14 w-14 items-center justify-center rounded-full border border-emerald-300/70 bg-emerald-500 text-white shadow-[0_24px_60px_-24px_rgba(16,185,129,0.9)] transition duration-200 hover:scale-[1.03] hover:bg-emerald-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 focus-visible:ring-offset-background dark:border-emerald-400/20 dark:bg-emerald-500 dark:hover:bg-emerald-400'
            : 'group inline-flex w-full items-start gap-3 rounded-[1.35rem] border border-emerald-200/80 bg-emerald-50/80 px-4 py-4 text-left text-emerald-900 shadow-[0_18px_45px_-35px_rgba(16,185,129,0.8)] transition hover:border-emerald-300 hover:bg-emerald-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 focus-visible:ring-offset-2 focus-visible:ring-offset-background dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-100 dark:hover:border-emerald-400/35 dark:hover:bg-emerald-500/15'"
        :aria-label="label"
    >
        <span
            :class="floating
                ? 'flex items-center justify-center'
                : 'mt-0.5 flex size-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-500 text-white shadow-sm transition group-hover:bg-emerald-600 dark:bg-emerald-400 dark:text-emerald-950 dark:group-hover:bg-emerald-300'"
        >
            <MessageCircleMore :class="floating ? 'size-6' : 'size-5'" />
        </span>

        <span v-if="!floating" class="min-w-0 space-y-1">
            <span class="block text-sm font-semibold">
                {{ label }}
            </span>
            <span class="block text-sm leading-6 text-emerald-800/80 dark:text-emerald-100/80">
                {{ description }}
            </span>
        </span>
    </a>
</template>
