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
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableEmpty,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import admin from '@/routes/admin';
import { Head, router } from '@inertiajs/vue3';
import {
    Activity,
    BookMarked,
    ClipboardList,
    Medal,
    Sparkles,
    Trophy,
    Users,
} from 'lucide-vue-next';

const props = defineProps<{
    analysis: {
        selected_grade: string | null;
        selected_assessment_id: number | null;
        grade_options: string[];
        assessment_options: Array<{
            id: number;
            title: string;
            class_name?: string | null;
            assessment_date?: string | null;
            total_marks: number;
        }>;
        selected_assessment?: {
            id: number;
            title: string;
            class_name?: string | null;
            assessment_date?: string | null;
            total_marks: number;
        } | null;
        summary: {
            highest_mark: number | null;
            lowest_mark: number | null;
            average_mark: number | null;
            results_count: number;
            students_ranked: number;
        };
        ranking: Array<{
            rank: number;
            student_id: number;
            student_name: string;
            marks: string | number;
        }>;
        grade_distribution: Array<{
            grade: string;
            count: number;
        }>;
    };
}>();

const applyFilters = (grade?: string, assessmentId?: string) => {
    router.get(admin.analysis({
        query: {
            grade: grade || undefined,
            assessment_id: assessmentId || undefined,
        },
    }), undefined, {
        preserveState: true,
        replace: true,
    });
};

const updateGrade = (value: unknown) => {
    const grade = value == null || value === '__all__' ? '' : String(value);
    applyFilters(grade, '');
};

const updateAssessment = (value: unknown) => {
    const assessmentId = value == null || value === '__all__' ? '' : String(value);
    applyFilters(props.analysis.selected_grade ?? '', assessmentId);
};
</script>

<template>
    <Head title="Analysis" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Analysis', href: admin.analysis.url() }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    :eyebrow="APP_SYSTEM_SUBTITLE"
                    title="Performance Overview"
                    :description="`Review normalized performance, fair ranking order, and score distribution across ${APP_BRAND_NAME}.`"
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <Sparkles class="size-3.5" />
                            Live academic snapshot
                        </Badge>
                    </template>
                </PageHero>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Analysis Filters</CardTitle>
                        <CardDescription>
                            Use grade and exam filters together so every performance comparison stays fair.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4 lg:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="analysis-grade-filter">Grade</Label>
                            <Select
                                :model-value="props.analysis.selected_grade ?? '__all__'"
                                @update:model-value="updateGrade"
                            >
                                <SelectTrigger id="analysis-grade-filter" class="w-full">
                                    <SelectValue placeholder="All grades" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">All grades</SelectItem>
                                    <SelectItem v-for="grade in props.analysis.grade_options" :key="grade" :value="grade">
                                        {{ grade }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label for="analysis-exam-filter">Exam</Label>
                            <Select
                                :model-value="props.analysis.selected_assessment_id ? String(props.analysis.selected_assessment_id) : '__all__'"
                                @update:model-value="updateAssessment"
                            >
                                <SelectTrigger id="analysis-exam-filter" class="w-full">
                                    <SelectValue placeholder="All exams" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">All exams</SelectItem>
                                    <SelectItem
                                        v-for="assessment in props.analysis.assessment_options"
                                        :key="assessment.id"
                                        :value="String(assessment.id)"
                                    >
                                        {{ assessment.title }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="props.analysis.selected_assessment" class="section-card bg-secondary/45">
                    <CardHeader>
                        <CardTitle>Selected Exam</CardTitle>
                        <CardDescription>
                            Performance cards below are normalized to percentages for fair comparison.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="text-[15px] leading-7 text-muted-foreground">
                        {{ props.analysis.selected_assessment.title }} / {{ props.analysis.selected_assessment.class_name || 'All classes' }} /
                        Total marks: {{ props.analysis.selected_assessment.total_marks }}
                    </CardContent>
                </Card>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    <StatCard
                        label="Highest Mark"
                        :value="props.analysis.summary.highest_mark !== null ? `${props.analysis.summary.highest_mark}%` : '-'"
                        hint="Top normalized percentage in the current analysis scope."
                        :icon="Trophy"
                        tone="warning"
                    />
                    <StatCard
                        label="Lowest Mark"
                        :value="props.analysis.summary.lowest_mark !== null ? `${props.analysis.summary.lowest_mark}%` : '-'"
                        hint="Lowest normalized percentage in the current analysis scope."
                        :icon="Activity"
                    />
                    <StatCard
                        label="Average Mark"
                        :value="props.analysis.summary.average_mark !== null ? `${props.analysis.summary.average_mark}%` : '-'"
                        hint="Average normalized percentage across the filtered results."
                        :icon="Sparkles"
                    />
                    <StatCard
                        label="Students Ranked"
                        :value="props.analysis.summary.students_ranked"
                        hint="Students included in the current ranking calculation."
                        :icon="Users"
                    />
                    <StatCard
                        label="Results Analysed"
                        :value="props.analysis.summary.results_count"
                        hint="Result records contributing to this analysis view."
                        :icon="BookMarked"
                    />
                </div>

                <div class="grid gap-6 xl:grid-cols-2">
                    <Card class="section-card overflow-hidden py-0">
                        <CardHeader class="border-b border-border/80 py-5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <CardTitle>Ranking Overview</CardTitle>
                                    <CardDescription>
                                        Ranked by selected exam marks or by average percentage across the filtered scope.
                                    </CardDescription>
                                </div>
                                <Badge variant="outline" class="rounded-full px-4 py-1.5">
                                    <Medal class="size-3.5 text-amber-500" />
                                    Ranked order
                                </Badge>
                            </div>
                        </CardHeader>
                        <CardContent class="p-0">
                            <Table>
                                <TableHeader>
                                    <TableRow class="bg-secondary/70">
                                        <TableHead>Rank</TableHead>
                                        <TableHead>Student</TableHead>
                                        <TableHead>{{ props.analysis.selected_assessment_id ? 'Marks' : 'Average %' }}</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <template v-if="props.analysis.ranking.length">
                                        <TableRow v-for="rank in props.analysis.ranking" :key="`${rank.student_id}-${rank.rank}`">
                                            <TableCell class="font-semibold text-amber-700">
                                                #{{ rank.rank }}
                                            </TableCell>
                                            <TableCell class="font-medium text-foreground">
                                                {{ rank.student_name }}
                                            </TableCell>
                                            <TableCell>
                                                {{ props.analysis.selected_assessment_id ? rank.marks : `${rank.marks}%` }}
                                            </TableCell>
                                        </TableRow>
                                    </template>
                                    <TableEmpty v-else :colspan="3" class="py-14">
                                        No ranking data is available for the current filters.
                                    </TableEmpty>
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>

                    <Card class="section-card overflow-hidden py-0">
                        <CardHeader class="border-b border-border/80 py-5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <CardTitle>Grade Distribution</CardTitle>
                                    <CardDescription>
                                        Score distribution grouped into normalized percentage bands.
                                    </CardDescription>
                                </div>
                                <Badge variant="outline" class="rounded-full px-4 py-1.5">
                                    <ClipboardList class="size-3.5" />
                                    {{ props.analysis.summary.results_count }} result(s)
                                </Badge>
                            </div>
                        </CardHeader>
                        <CardContent class="p-0">
                            <Table>
                                <TableHeader>
                                    <TableRow class="bg-secondary/70">
                                        <TableHead>Band</TableHead>
                                        <TableHead>Results</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <template v-if="props.analysis.grade_distribution.length">
                                        <TableRow v-for="band in props.analysis.grade_distribution" :key="band.grade">
                                            <TableCell class="font-medium text-foreground">
                                                {{ band.grade }}
                                            </TableCell>
                                            <TableCell>{{ band.count }}</TableCell>
                                        </TableRow>
                                    </template>
                                    <TableEmpty v-else :colspan="2" class="py-14">
                                        No distribution data is available for the current filters.
                                    </TableEmpty>
                                </TableBody>
                            </Table>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
