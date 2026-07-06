<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { Cropper } from 'vue-advanced-cropper/dist/index.esm-bundler.js';

import InputError from '@/components/InputError.vue';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Camera, Info, Minus, Plus, RotateCcw, RotateCw, X } from 'lucide-vue-next';

type Props = {
    title?: string;
    doneText?: string;
    uploadUrl: string;
    currentUrl?: string | null;
    fallbackText?: string;
    aspectRatio?: number;
    canvasSize?: number;
    accept?: string;
    formField?: string;
    reloadOnly?: string[];
    maxSizeMb?: number;
    clearPreviewOnSuccess?: boolean;
    canEdit?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    title: 'Adjust profile photo',
    doneText: 'Crop & Upload',
    currentUrl: null,
    fallbackText: '',
    aspectRatio: 1,
    canvasSize: 512,
    accept: 'image/*',
    formField: 'avatar',
    maxSizeMb: 1,
    reloadOnly: () => ['auth', 'flash'],
    clearPreviewOnSuccess: false,
    canEdit: true,
});

const fileInputRef = ref<HTMLInputElement | null>(null);
const showCrop = ref(false);
const rawImageUrl = ref<string | null>(null);
const cropperRef = ref<any>(null);
const previewUrl = ref<string | null>(null);
const displayedImageUrl = computed(() => previewUrl.value || props.currentUrl || null);

const form = useForm<Record<string, File | null>>({
    [props.formField]: null,
});

watch(
    () => props.currentUrl,
    (nextUrl) => {
        if (nextUrl) {
            previewUrl.value = null;
        }
    },
);

function openPicker() {
    fileInputRef.value?.click();
}

function onSelect(e: Event) {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0] ?? null;
    if (!file) return;
    if (!file.type.startsWith('image/')) return;

    if (rawImageUrl.value) URL.revokeObjectURL(rawImageUrl.value);
    rawImageUrl.value = URL.createObjectURL(file);
    showCrop.value = true;

    input.value = '';
}

function zoomIn() {
    cropperRef.value?.zoom?.(1.1);
}

function zoomOut() {
    cropperRef.value?.zoom?.(0.9);
}

function rotateLeft() {
    cropperRef.value?.rotate?.(-90);
}

function rotateRight() {
    cropperRef.value?.rotate?.(90);
}

async function done() {
    const result = cropperRef.value?.getResult?.();
    const canvas: HTMLCanvasElement | undefined = result?.canvas;
    if (!canvas) return;

    previewUrl.value = canvas.toDataURL('image/jpeg', 0.9);

    const blob: Blob | null = await new Promise((resolve) =>
        canvas.toBlob((b) => resolve(b), 'image/jpeg', 0.9),
    );
    if (!blob) return;

    const file = new File([blob], 'upload.jpg', { type: 'image/jpeg' });
    form[props.formField] = file;

    showCrop.value = false;

    if (rawImageUrl.value) URL.revokeObjectURL(rawImageUrl.value);
    rawImageUrl.value = null;

    form.post(props.uploadUrl, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}

function cancel() {
    showCrop.value = false;
    if (rawImageUrl.value) URL.revokeObjectURL(rawImageUrl.value);
    rawImageUrl.value = null;
}
</script>

<template>
    <div>
        <div class="relative inline-block">
            <Avatar class="h-[150px] w-[150px] overflow-hidden rounded-full">
                <AvatarImage v-if="displayedImageUrl" :src="displayedImageUrl" alt="Uploaded image" />
                <AvatarFallback class="rounded-full text-black dark:text-white">
                    {{ fallbackText }}
                </AvatarFallback>
            </Avatar>

            <input
                ref="fileInputRef"
                type="file"
                class="hidden"
                :accept="accept"
                @change="onSelect"
            >

            <button
                v-if="canEdit"
                type="button"
                aria-label="Change photo"
                class="absolute right-[10px] bottom-[0px] flex h-10 w-10 items-center justify-center rounded-full border border-4 border-white bg-gray-200 dark:border-black dark:bg-gray-600"
                @click="openPicker"
            >
                <Camera class="h-4 w-4 text-black dark:text-white" />
            </button>

            <InputError class="mt-2" :message="(form.errors as any)[formField]" />
        </div>

        <div
            v-if="showCrop"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        >
            <div class="w-full max-w-lg rounded-xl bg-white p-4 shadow-lg dark:bg-neutral-900">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                        {{ title }}
                    </h3>

                    <Button type="button" variant="ghost" class="h-8 w-8 p-0" @click="cancel">
                        <X class="h-4 w-4" />
                    </Button>
                </div>

                <Alert variant="default" class="mb-4">
                    <Info />
                    <AlertTitle>
                        Image size should be less than {{ props.maxSizeMb }}MB.
                    </AlertTitle>
                </Alert>

                <div class="mb-6 overflow-hidden rounded-lg bg-neutral-100 dark:bg-neutral-800">
                    <Cropper
                        ref="cropperRef"
                        :src="rawImageUrl"
                        :stencil-props="{ aspectRatio }"
                        :canvas="{ width: canvasSize, height: canvasSize }"
                        class="h-80 w-full"
                    />
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <Button type="button" variant="ghost" class="h-9 w-9 p-0" @click="rotateLeft">
                            <RotateCcw class="h-4 w-4" />
                        </Button>
                        <Button type="button" variant="ghost" class="h-9 w-9 p-0" @click="rotateRight">
                            <RotateCw class="h-4 w-4" />
                        </Button>
                        <Button type="button" variant="ghost" class="h-9 w-9 p-0" @click="zoomOut">
                            <Minus class="h-4 w-4" />
                        </Button>
                        <Button type="button" variant="ghost" class="h-9 w-9 p-0" @click="zoomIn">
                            <Plus class="h-4 w-4" />
                        </Button>
                    </div>

                    <div class="flex items-center gap-3">
                        <Button type="button" variant="secondary" @click="cancel">Cancel</Button>
                        <Button type="button" :disabled="form.processing" @click="done">
                            {{ doneText }}
                        </Button>
                    </div>
                </div>

                <InputError class="mt-2" :message="(form.errors as any)[formField]" />
            </div>
        </div>
    </div>
</template>
