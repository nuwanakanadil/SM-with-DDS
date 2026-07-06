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
</script>

<template>
    <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
        <AvatarImage v-if="showAvatar" :src="avatarSrc" :alt="displayName" />
        <AvatarFallback class="rounded-lg text-black dark:text-white">
            {{ getInitials(displayName) }}
        </AvatarFallback>
    </Avatar>

    <div class="grid min-w-0 flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium text-sidebar-foreground">{{ displayName }}</span>
        <span v-if="showEmail" class="truncate text-xs text-muted-foreground">
            {{ user?.email }}
        </span>
    </div>
</template>
