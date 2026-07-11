<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import PageHero from '@/components/PageHero.vue';
import StatCard from '@/components/StatCard.vue';
import SupportHotline from '@/components/SupportHotline.vue';
import WhatsAppFloatingButton from '@/components/WhatsAppFloatingButton.vue';
import MathMotionPanel from '@/components/student/MathMotionPanel.vue';
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
                <div
                    class="student-dashboard-hero student-dashboard-surface relaxed-panel relative overflow-hidden border-primary/10 bg-[linear-gradient(135deg,rgba(255,255,255,0.98),rgba(219,234,254,0.9))] dark:border-white/8 dark:bg-[linear-gradient(135deg,rgba(15,23,42,0.98),rgba(30,41,59,0.9))]"
                >
                    <MathMotionPanel
                        variant="hero"
                        :equations="['x^2 + y^2 = r^2', 'y = mx + c', 'sin a', 'pi = 3.14']"
                    />
                    <PageHero
                        :eyebrow="APP_SYSTEM_SUBTITLE"
                        :title="student ? `Welcome, ${student.first_name}` : 'Student Dashboard'"
                        :description="`Track your exams, marks, and placement from one clear dashboard inside ${APP_BRAND_NAME}.`"
                    >
                        <template #meta>
                            <Badge variant="outline" class="rounded-full px-4 py-1.5">
                                <GraduationCap class="size-3.5" />
                                {{ student?.class_name ?? 'Class not assigned' }}
                            </Badge>
                        </template>
                    </PageHero>
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <div class="student-dashboard-stat-card student-dashboard-surface">
                        <MathMotionPanel variant="compact" :equations="['sum n', '1/3', 'n + 1']" />
                        <StatCard
                            label="Published Exams"
                            :value="publishedAssessmentsCount"
                            hint="Exams currently available to your class."
                            :icon="BookOpenCheck"
                        />
                    </div>
                    <div class="student-dashboard-stat-card student-dashboard-surface">
                        <MathMotionPanel variant="compact" :equations="['x + 7', 'sqrt 81', 'P(A|B)']" />
                        <StatCard
                            label="My Results"
                            :value="resultsCount"
                            hint="Results already published to your account."
                            :icon="Sparkles"
                        />
                    </div>
                    <div class="student-dashboard-stat-card student-dashboard-surface">
                        <MathMotionPanel variant="compact" :equations="['avg', '90%', 'x bar']" />
                        <StatCard
                            label="Average Marks"
                            :value="averageMarks ?? '-'"
                            hint="Your average score across published results."
                            :icon="CircleGauge"
                        />
                    </div>
                </div>

                <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                    <Card
                        class="section-card student-dashboard-card student-dashboard-surface relative overflow-hidden border-white/60 bg-[linear-gradient(135deg,rgba(255,255,255,0.98),rgba(239,246,255,0.88))] dark:border-white/8 dark:bg-[linear-gradient(135deg,rgba(17,24,39,0.96),rgba(30,41,59,0.88))]"
                    >
                        <MathMotionPanel
                            variant="card"
                            :equations="['2x + 5', 'tan a', 'm/s^2']"
                        />
                        <CardHeader>
                            <CardDescription class="flex items-center gap-2 text-muted-foreground">
                                <BookOpenCheck class="size-4 text-primary" />
                                Latest Exam
                            </CardDescription>
                            <CardTitle>
                                {{ latestAssessment?.title ?? 'No published exam yet' }}
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
                                Once a published exam is available in {{ APP_BRAND_NAME }}, it will appear here.
                            </p>
                        </CardContent>
                    </Card>

                    <Card
                        class="section-card student-dashboard-card student-dashboard-surface relative overflow-hidden border-white/60 bg-[linear-gradient(135deg,rgba(37,99,235,0.09),rgba(255,255,255,0.98))] dark:border-white/8 dark:bg-[linear-gradient(135deg,rgba(15,23,42,0.98),rgba(51,65,85,0.88))]"
                    >
                        <MathMotionPanel
                            variant="card"
                            :equations="['rank 1', '95/100', '3 : 1']"
                        />
                        <CardHeader>
                            <CardDescription class="flex items-center gap-2 text-muted-foreground">
                                <Trophy class="size-4 text-amber-500" />
                                Current Placement
                            </CardDescription>
                            <CardTitle class="text-5xl font-extrabold text-foreground">
                                {{ latestPlacement?.rank ?? '-' }}
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3 text-[15px] leading-7 text-muted-foreground">
                            <p v-if="latestPlacement">
                                You scored {{ latestPlacement.marks }} in your latest ranked exam.
                            </p>
                            <p v-else>
                                Placement appears once published results are available for a ranked exam.
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <Card class="section-card student-dashboard-card student-dashboard-surface relative overflow-hidden">
                    <MathMotionPanel
                        variant="card"
                        :equations="['8/10', 'x = 14', 'AB^2 + BC^2']"
                    />
                    <CardHeader>
                        <CardDescription class="flex items-center gap-2 text-muted-foreground">
                            <Medal class="size-4 text-amber-500" />
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

                <Card
                    class="section-card student-dashboard-card student-dashboard-surface relative overflow-hidden border-white/60 bg-[linear-gradient(135deg,rgba(236,253,245,0.95),rgba(255,255,255,0.98))] dark:border-white/8 dark:bg-[linear-gradient(135deg,rgba(6,78,59,0.45),rgba(15,23,42,0.96))]"
                >
                    <MathMotionPanel
                        variant="card"
                        :equations="['help = progress', '24/7', 'ask now']"
                    />
                    <CardHeader>
                        <CardDescription class="flex items-center gap-2 text-muted-foreground">
                            <Sparkles class="size-4 text-emerald-500" />
                            Student Support
                        </CardDescription>
                        <CardTitle>
                            Need help?
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4 text-[15px] leading-7 text-muted-foreground">
                        <p>
                            Message student support on WhatsApp for help with login issues, published results, placement updates, or general account questions.
                        </p>
                        <WhatsAppFloatingButton
                            :floating="false"
                            label="Chat with student support"
                            description="Open WhatsApp with a ready-made support message and get help faster."
                        />
                    </CardContent>
                </Card>

                <SupportHotline />
            </div>
        </StudentLayout>
    </AppLayout>
</template>
