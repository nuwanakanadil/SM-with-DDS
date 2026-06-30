<template>
    <Popover v-model:open="selectOpen">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                type="button"
                aria-haspopup="listbox"
                :aria-expanded="selectOpen"
                :aria-controls="commandListId"
                :aria-disabled="props.disabled"
                :disabled="props.disabled"
                :class="['w-full flex items-center justify-between font-normal overflow-hidden', props.class]"
            >
                <div v-if="selectedLabels === props.placeholder" class="text-muted-foreground normal-case truncate flex-1 text-left">
                    {{  selectedLabels.charAt(0).toUpperCase() + selectedLabels.slice(1).toLowerCase() }}
                </div>
                <div v-else class="normal-case truncate flex-1 text-left">
                    {{ selectedLabels }}
                </div>
                <ChevronDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-full p-0" align="start">
            <Command>
                <CommandInput :placeholder="`Search...`" />
                <CommandList :id="commandListId" role="listbox">
                    <CommandEmpty>No results found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem
                            v-for="option in props.options"
                            :key="option.key"
                            :value="option.key"
                            @select="() => onSelect(option.key)"
                        >
                            <Check
                                :class="
                                    cn(
                                        'mr-2 h-4 w-4',
                                        isSelected(option.key) ? 'opacity-100' : 'opacity-0',
                                    )
                                "
                            />
                            {{ option.label }}
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>

<script setup lang="ts">
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { Check, ChevronDown } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';

type Option = { key: string; label: string };

type SingleSelectProps = {
    placeholder?: string;
    allowMultiple?: false;
    options: Option[];
    class?: string;
    disabled?: boolean;
};

type MultiSelectProps = {
    placeholder?: string;
    allowMultiple: true;
    options: Option[];
    class?: string;
    disabled?: boolean;
};

type Props = SingleSelectProps | MultiSelectProps;

const model = defineModel<string | string[]>({ required: true });

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Search and select',
    allowMultiple: false,
    disabled: false,
});

const selectOpen = ref(false);

const commandListId = `searchable-select-list-${Math.random().toString(36).slice(2, 11)}`;

function isSelected(key: string): boolean {
    if (props.allowMultiple) {
        return Array.isArray(model.value) && model.value.includes(key);
    }
    return model.value === key;
}

function onSelect(key: string): void {
    if (props.disabled) {
        return;
    }

    if (props.allowMultiple) {
        const current = Array.isArray(model.value) ? model.value : [];
        model.value = current.includes(key)
            ? current.filter((k) => k !== key)
            : [...current, key];
    } else {
        model.value = key;
        selectOpen.value = false;
    }
}

const selectedLabels = computed<string>(() => {
    if (props.allowMultiple) {
        const selected = Array.isArray(model.value) ? model.value : [];
        if (selected.length === 0) return props.placeholder ?? 'Search and select';
        return props.options
            .filter((o) => selected.includes(o.key))
            .map((o) => o.label)
            .join(', ');
    }

    const found = props.options.find((o) => o.key === model.value);
    return found ? found.label : (props.placeholder ?? 'Search and select');
});
</script>