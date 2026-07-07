<script setup lang="ts">
import PageHero from '@/components/PageHero.vue';
import SupportHotline from '@/components/SupportHotline.vue';
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import StudentLayout from '@/layouts/student/Layout.vue';
import student from '@/routes/student';
import type { Assessment } from '@/types/assessment';
import type { StudentRank } from '@/types/result';
import { Head, router } from '@inertiajs/vue3';
import { ChevronDown, ListFilter, Medal, Trophy } from 'lucide-vue-next';
import { reactive } from 'vue';

const props = defineProps<{
    assessments: Assessment[];
    selectedAssessment?: Assessment | null;
    placement?: StudentRank | null;
}>();

const filters = reactive({
    assessment_id: props.selectedAssessment?.id ?? '',
});

const applyFilter = () => {
    router.get(student.placement({
        query: {
            assessment_id: filters.assessment_id || undefined,
        },
    }), undefined, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <Head title="My Placement" />
    <AppLayout :breadcrumbs="[{ title: 'Student' }, { title: 'My Placement', href: student.placement.url() }]">
        <StudentLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Placement"
                    title="Track My Ranking"
                    description="Review your current position within a published assessment and see how your latest marks affect placement."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <ListFilter class="size-3.5" />
                            {{ assessments.length }} exam option(s)
                        </Badge>
                    </template>
                </PageHero>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Choose Exam</CardTitle>
                        <CardDescription>
                            Switch between published exams available to your class.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="relative max-w-md">
                            <ListFilter class="pointer-events-none absolute left-4 top-1/2 z-10 size-4 -translate-y-1/2 text-muted-foreground" />
                            <ChevronDown class="pointer-events-none absolute right-4 top-1/2 z-10 size-4 -translate-y-1/2 text-muted-foreground" />
                            <select
                                v-model="filters.assessment_id"
                                class="app-select pl-11 pr-10"
                                @change="applyFilter"
                            >
                                <option value="">Latest published exam</option>
                                <option
                                    v-for="assessment in assessments"
                                    :key="assessment.id"
                                    :value="assessment.id"
                                >
                                    {{ assessment.title }}
                                </option>
                            </select>
                        </div>
                    </CardContent>
                </Card>

                <Card
                    class="section-card border-white/60 bg-[linear-gradient(135deg,rgba(37,99,235,0.1),rgba(255,255,255,0.98))] dark:border-white/8 dark:bg-[linear-gradient(135deg,rgba(15,23,42,0.98),rgba(51,65,85,0.88))]"
                >
                    <CardHeader>
                        <CardDescription class="flex items-center gap-2">
                            <Trophy class="size-4 text-amber-500" />
                            Your Placement
                        </CardDescription>
                        <CardTitle class="text-5xl font-extrabold text-foreground">
                            {{ placement?.rank ?? '-' }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4 text-[15px] leading-7 text-muted-foreground">
                        <div class="info-tile">
                            {{ selectedAssessment ? selectedAssessment.title : 'No exam selected' }}
                        </div>
                        <div v-if="placement" class="info-tile">
                            <Medal class="mr-1 inline size-4 text-amber-500" />
                            Marks scored: {{ placement.marks }}
                        </div>
                        <p v-else>
                            Placement will appear when the selected exam has a published result for you.
                        </p>
                    </CardContent>
                </Card>

                <SupportHotline
                    title="Need help with placement details?"
                    description="Call the hotline if you need support understanding ranking updates or published exam placement."
                />
            </div>
        </StudentLayout>
    </AppLayout>
</template>
