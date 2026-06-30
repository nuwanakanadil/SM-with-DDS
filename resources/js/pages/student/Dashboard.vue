<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import PageHero from '@/components/PageHero.vue';
import StatCard from '@/components/StatCard.vue';
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
import type { Assessment } from '@/types/assessment';
import type { AssessmentResult, StudentRank } from '@/types/result';
import type { Student } from '@/types/student';
import { Head } from '@inertiajs/vue3';
import {
    BookOpenCheck,
    CircleGauge,
    GraduationCap,
    Medal,
    Sparkles,
    Trophy,
} from 'lucide-vue-next';

defineProps<{
    student?: Student | null;
    latestAssessment?: Assessment | null;
    latestResult?: AssessmentResult | null;
    latestPlacement?: StudentRank | null;
    publishedAssessmentsCount: number;
    resultsCount: number;
    averageMarks?: number | null;
}>();
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="[{ title: 'Student' }, { title: 'Dashboard' }]">
        <StudentLayout>
            <div class="space-y-6">
                <PageHero
                    :eyebrow="APP_SYSTEM_SUBTITLE"
                    :title="student ? `Welcome, ${student.first_name}` : 'Student Dashboard'"
                    :description="`Track your assessments, marks, and placement from one clear dashboard inside ${APP_BRAND_NAME}.`"
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <GraduationCap class="size-3.5" />
                            {{ student?.class_name ?? 'Class not assigned' }}
                        </Badge>
                    </template>
                </PageHero>

                <div class="grid gap-5 md:grid-cols-3">
                    <StatCard
                        label="Published Assessments"
                        :value="publishedAssessmentsCount"
                        hint="Assessments currently available to your class."
                        :icon="BookOpenCheck"
                    />
                    <StatCard
                        label="My Results"
                        :value="resultsCount"
                        hint="Results already published to your account."
                        :icon="Sparkles"
                    />
                    <StatCard
                        label="Average Marks"
                        :value="averageMarks ?? '-'"
                        hint="Your average score across published results."
                        :icon="CircleGauge"
                    />
                </div>

                <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                    <Card class="section-card bg-[linear-gradient(135deg,rgba(255,255,255,0.98),rgba(239,246,255,0.88))]">
                        <CardHeader>
                            <CardDescription class="flex items-center gap-2 text-muted-foreground">
                                <BookOpenCheck class="size-4 text-primary" />
                                Latest Assessment
                            </CardDescription>
                            <CardTitle>
                                {{ latestAssessment?.title ?? 'No published assessment yet' }}
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3 text-[15px] leading-7 text-muted-foreground">
                            <template v-if="latestAssessment">
                                <div class="info-tile">
                                    Class: {{ latestAssessment.class_name || 'N/A' }}
                                </div>
                                <div class="info-tile">
                                    Total marks: {{ latestAssessment.total_marks }}
                                </div>
                            </template>
                            <p v-else>
                                Once a published assessment is available in {{ APP_BRAND_NAME }}, it will appear here.
                            </p>
                        </CardContent>
                    </Card>

                    <Card class="section-card bg-[linear-gradient(135deg,rgba(37,99,235,0.09),rgba(255,255,255,0.98))]">
                        <CardHeader>
                            <CardDescription class="flex items-center gap-2 text-muted-foreground">
                                <Trophy class="size-4 text-[#F59E0B]" />
                                Current Placement
                            </CardDescription>
                            <CardTitle class="text-5xl font-extrabold text-foreground">
                                {{ latestPlacement?.rank ?? '-' }}
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3 text-[15px] leading-7 text-muted-foreground">
                            <p v-if="latestPlacement">
                                You scored {{ latestPlacement.marks }} in your latest ranked assessment.
                            </p>
                            <p v-else>
                                Placement appears once published results are available for a ranked assessment.
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <Card class="section-card">
                    <CardHeader>
                        <CardDescription class="flex items-center gap-2 text-muted-foreground">
                            <Medal class="size-4 text-[#F59E0B]" />
                            Latest Result
                        </CardDescription>
                        <CardTitle>
                            {{ latestResult?.assessment?.title ?? 'No result available yet' }}
                        </CardTitle>
                    </CardHeader>
                    <CardContent
                        v-if="latestResult?.assessment"
                        class="grid gap-4 text-[15px] leading-7 text-muted-foreground md:grid-cols-2"
                    >
                        <div class="info-tile">
                            Marks: {{ latestResult.marks }} / {{ latestResult.assessment.total_marks }}
                        </div>
                        <div v-if="latestResult.remarks" class="info-tile">
                            Remarks: {{ latestResult.remarks }}
                        </div>
                    </CardContent>
                    <CardContent v-else class="text-[15px] leading-7 text-muted-foreground">
                        No published result has been recorded for your account in {{ APP_BRAND_NAME }} yet.
                    </CardContent>
                </Card>
            </div>
        </StudentLayout>
    </AppLayout>
</template>
