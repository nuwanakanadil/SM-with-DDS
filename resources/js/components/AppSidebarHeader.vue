<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useInitials } from '@/composables/useInitials';
import type { BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { ChevronsUpDown } from 'lucide-vue-next';
import { computed } from 'vue';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

const page = usePage();
const user = computed(() => page.props.auth.user);
const { getInitials } = useInitials();
const headerUserName = computed(() => user.value?.first_name || user.value?.name || 'User');
</script>

<template>
    <header
        class="sticky top-0 z-20 flex min-h-12 shrink-0 items-center gap-2.5 border-b border-border bg-background px-3.5 py-2.5 transition-[width,height] ease-linear md:px-4"
    >
        <div class="flex min-w-0 items-center gap-2.5">
            <SidebarTrigger class="-ml-1" />
            <div class="min-w-0">
                <template v-if="breadcrumbs && breadcrumbs.length > 0">
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                </template>
            </div>
        </div>

        <div v-if="user" class="ml-auto">
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full border border-border bg-white px-2 py-1.5 text-sm text-foreground transition hover:bg-accent focus:outline-none focus:ring-4 focus:ring-[#DBEAFE]"
                    >
                        <Avatar class="h-8 w-8 overflow-hidden rounded-full border border-border bg-[#F8FAFC]">
                            <AvatarImage v-if="user.avatar" :src="user.avatar" :alt="headerUserName" />
                            <AvatarFallback class="bg-[#EFF6FF] text-xs font-semibold text-primary">
                                {{ getInitials(headerUserName) }}
                            </AvatarFallback>
                        </Avatar>
                        <span class="hidden max-w-[8rem] truncate font-medium sm:block">
                            {{ headerUserName }}
                        </span>
                        <ChevronsUpDown class="size-3.5 text-muted-foreground" />
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-56 rounded-lg">
                    <UserMenuContent :user="user" />
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </header>
</template>
