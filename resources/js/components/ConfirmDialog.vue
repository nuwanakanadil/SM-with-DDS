<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { ref } from 'vue';

const props = withDefaults(
    defineProps<{
        title: string;
        description: string;
        confirmLabel?: string;
        busy?: boolean;
    }>(),
    {
        confirmLabel: 'Confirm',
        busy: false,
    },
);

const emit = defineEmits<{
    confirm: [];
}>();

const open = ref(false);

const handleConfirm = () => {
    emit('confirm');
    open.value = false;
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <slot name="trigger" />
        </DialogTrigger>

        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>{{ props.title }}</DialogTitle>
                <DialogDescription>{{ props.description }}</DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <Button variant="outline" @click="open = false">Cancel</Button>
                <Button
                    variant="destructive"
                    :disabled="props.busy"
                    @click="handleConfirm"
                >
                    {{ props.busy ? 'Please wait...' : props.confirmLabel }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
