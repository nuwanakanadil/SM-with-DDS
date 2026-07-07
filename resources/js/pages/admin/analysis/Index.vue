<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import PageHero from '@/components/PageHero.vue';
import StatCard from '@/components/StatCard.vue';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import { Head } from '@inertiajs/vue3';
import {
    Activity,
    BookMarked,
    ClipboardList,
    Sparkles,
    Trophy,
    Users,
} from 'lucide-vue-next';

defineProps<{
    analysis: {
        highest_mark: number | null;
        lowest_mark: number | null;
        average_mark: number | null;
        total_students: number;
        total_assessments: number;
        total_results: number;
    };
}>();
</script>

<template>
    <Head title="Analysis" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Analysis' }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    :eyebrow="APP_SYSTEM_SUBTITLE"
                    title="Performance Overview"
                    :description="`A high-level summary of marks, participation, and ranking data across ${APP_BRAND_NAME}.`"
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <Sparkles class="size-3.5" />
                            Live academic snapshot
                        </Badge>
                    </template>
                </PageHero>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <StatCard
                        label="Highest Mark"
                        :value="analysis.highest_mark ?? '-'"
                        hint="Top score recorded in the current dataset."
                        :icon="Trophy"
                        tone="warning"
                    />
                    <StatCard
                        label="Lowest Mark"
                        :value="analysis.lowest_mark ?? '-'"
                        hint="Lowest mark captured across saved results."
                        :icon="Activity"
                    />
                    <StatCard
                        label="Average Mark"
                        :value="analysis.average_mark ?? '-'"
                        hint="Average performance across all recorded marks."
                        :icon="Sparkles"
                    />
                    <StatCard
                        label="Students"
                        :value="analysis.total_students"
                        hint="Student records available for ranking and reporting."
                        :icon="Users"
                    />
                    <StatCard
                        label="Exams"
                        :value="analysis.total_assessments"
                        hint="Exams configured inside the system."
                        :icon="BookMarked"
                    />
                    <StatCard
                        label="Results"
                        :value="analysis.total_results"
                        hint="Saved marks currently powering the dashboards."
                        :icon="ClipboardList"
                        tone="success"
                    />
                </div>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
