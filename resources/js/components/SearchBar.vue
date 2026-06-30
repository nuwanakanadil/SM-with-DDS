<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';

let debounceTimeout: ReturnType<typeof setTimeout> | null = null;

const isSearching = defineModel<boolean>({ required: true });

const props = defineProps<{
    search?: string;
    placeholder?: string;
}>();

const emit = defineEmits<{
    processSearch: [value: string];
}>();

const searchString = ref(props.search || '');

watch(
    () => props.search,
    (value) => {
        searchString.value = value || '';
    },
);

watch(searchString, (value) => {
    if (debounceTimeout) {
        clearTimeout(debounceTimeout);
    }

    isSearching.value = true;

    if (value.length <= 1) {
        emit('processSearch', value);
        return;
    }

    debounceTimeout = setTimeout(() => {
        emit('processSearch', value);
    }, 500);
});
</script>

<template>
    <div class="flex items-center gap-3">
        <div class="relative flex-1">
            <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
            <Input
                v-model="searchString"
                type="search"
                :placeholder="placeholder ?? 'Type something to search...'"
                class="pl-10 pr-10"
            />
        </div>
        <div v-if="isSearching" class="text-sm text-muted-foreground">
            <Spinner />
        </div>
    </div>
</template>
