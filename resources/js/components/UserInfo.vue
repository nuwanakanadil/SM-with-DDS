<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';
import { computed } from 'vue';

type Props = {
    user?: User | null;
    showEmail?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const { getInitials } = useInitials();

const displayName = computed(() => {
    const first = props.user?.first_name;
    const last = props.user?.last_name;

    return [first, last].filter(Boolean).join(' ') || props.user?.name || 'Signed in user';
});

const avatarSrc = computed(() => props.user?.avatar ?? '');
const showAvatar = computed(() => Boolean(props.user?.avatar));
const firstLetter = computed(() => displayName.value.trim().charAt(0).toUpperCase());
</script>

<template>
    <Avatar class="h-10 w-10 overflow-hidden rounded-xl border border-sidebar-border bg-blue-50 dark:bg-muted">
        <AvatarImage v-if="showAvatar" :src="avatarSrc" :alt="displayName" />
        <AvatarFallback class="rounded-xl bg-blue-50 text-sm font-semibold text-blue-700 dark:bg-muted dark:text-foreground">
            {{ showAvatar ? getInitials(displayName) : firstLetter }}
        </AvatarFallback>
    </Avatar>

    <div class="grid min-w-0 flex-1 text-left text-sm leading-tight">
        <span class="truncate font-semibold text-sidebar-foreground">{{ displayName }}</span>
        <span v-if="showEmail" class="truncate text-xs text-sidebar-foreground/65">
            {{ user?.email }}
        </span>
    </div>
</template>
