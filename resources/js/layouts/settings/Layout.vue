<script setup lang="ts">
import Separator from '@/components/ui/separator/Separator.vue';
</script>

<template>
    <div>
        <div class="mx-auto max-w-[1200px] px-4 py-6">
            <div class="mb-10 flex items-center">
                <slot name="header" />
                <slot name="header-actions" />
            </div>

            <div class="flex flex-col lg:flex-row lg:space-x-12">
                <aside class="w-full max-w-xl lg:w-48">
                    <nav class="flex flex-col space-y-1 space-x-0">
                        <slot name="sidebar" />
                    </nav>
                </aside>

                <Separator class="my-6 lg:hidden" />

                <div class="w-full flex-1">
                    <section class="space-y-12">
                        <slot />
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
