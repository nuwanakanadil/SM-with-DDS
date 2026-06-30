<script setup lang="ts" generic="T">
import { Button } from '@/components/ui/button';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import type { PaginatedResponse } from '@/types';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    response: PaginatedResponse<T>;
}>();

const pageLinks = computed(() =>
    props.response.links.filter(
        (link) =>
            link.label !== '&laquo; Previous' && link.label !== 'Next &raquo;',
    ),
);

const previousLink = computed(() =>
    props.response.links.find((link) => link.label === '&laquo; Previous'),
);

const nextLink = computed(() =>
    props.response.links.find((link) => link.label === 'Next &raquo;'),
);

const cleanLabel = (label: string) =>
    label
        .replace(/&laquo;|&raquo;/g, '')
        .replace(/&amp;/g, '&')
        .trim();
</script>

<template>
    <div class="flex flex-col gap-3 border-t px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="shrink-0 whitespace-nowrap text-sm text-muted-foreground">
            Showing {{ response.from ?? 0 }} to {{ response.to ?? 0 }} of
            {{ response.total }} result(s)
        </p>

        <Pagination
            :items-per-page="response.per_page"
            :total="response.total"
            :default-page="response.current_page"
        >
            <PaginationContent>
                <PaginationPrevious as-child>
                    <Link
                        :href="previousLink?.url ?? '#'"
                        :class="{
                            'pointer-events-none opacity-50': !previousLink?.url,
                        }"
                        preserve-scroll
                    />
                </PaginationPrevious>

                <template
                    v-for="link in pageLinks"
                    :key="`${link.label}-${link.url}`"
                >
                    <PaginationEllipsis
                        v-if="cleanLabel(link.label) === '...'"
                    />

                    <Button
                        v-else
                        as-child
                        :variant="link.active ? 'default' : 'outline'"
                        size="icon-sm"
                    >
                        <Link :href="link.url ?? '#'" preserve-scroll>
                            {{ cleanLabel(link.label) }}
                        </Link>
                    </Button>
                </template>

                <PaginationNext as-child>
                    <Link
                        :href="nextLink?.url ?? '#'"
                        :class="{
                            'pointer-events-none opacity-50': !nextLink?.url,
                        }"
                        preserve-scroll
                    />
                </PaginationNext>
            </PaginationContent>
        </Pagination>
    </div>
</template>
