<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import PageHero from '@/components/PageHero.vue';
import StatCard from '@/components/StatCard.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
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
    CircleAlert,
    ClipboardList,
    Medal,
    RotateCcw,
    Sparkles,
    Trophy,
    Users,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{
    analysis: {
        selected_grade: string | null;
        selected_class_group: string | null;
        selected_assessment_id: number | null;
        grade_options: string[];
        class_group_options: string[];
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
            unassigned_students: number;
        };
        ranking: Array<{
            rank: number;
            student_id: number;
            student_name: string;
            class_name: string | null;
            class_group: string | null;
            marks: string | number;
        }>;
        group_summaries: Array<{
            class_group: string;
            highest_mark: number | null;
            lowest_mark: number | null;
            average_mark: number | null;
            students_ranked: number;
            results_count: number;
        }>;
        grade_distribution: Array<{
            grade: string;
            count: number;
        }>;
    };
}>();

const filterVisitInProgress = ref(false);
const hasActiveFilters = computed(() => Boolean(
    props.analysis.selected_grade
    || props.analysis.selected_class_group
    || props.analysis.selected_assessment_id,
));

const applyFilters = (grade = '', classGroup = '', assessmentId = '') => {
    if (filterVisitInProgress.value) {
        return;
    }

    const filters: Record<string, string> = {};

    if (grade) filters.grade = grade;
    if (classGroup) filters.class_group = classGroup;
    if (assessmentId) filters.assessment_id = assessmentId;

    router.get(admin.analysis.url(), filters, {
        preserveState: false,
        preserveScroll: true,
        replace: true,
        onStart: () => {
            filterVisitInProgress.value = true;
        },
        onFinish: () => {
            filterVisitInProgress.value = false;
        },
    });
};

const updateGrade = (value: unknown) => {
    const grade = value == null || value === '__all__' ? '' : String(value);
    applyFilters(grade, props.analysis.selected_class_group ?? '', '');
};

const updateClassGroup = (value: unknown) => {
    const classGroup = value == null || value === '__all__' ? '' : String(value);
    applyFilters(props.analysis.selected_grade ?? '', classGroup, props.analysis.selected_assessment_id?.toString() ?? '');
};

const updateAssessment = (value: unknown) => {
    const assessmentId = value == null || value === '__all__' ? '' : String(value);
    applyFilters(props.analysis.selected_grade ?? '', props.analysis.selected_class_group ?? '', assessmentId);
};

const resetFilters = () => applyFilters();
</script>

<template>
    <Head title="Analysis" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Analysis', href: admin.analysis.url() }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    :eyebrow="APP_SYSTEM_SUBTITLE"
                    title="Performance Overview"
                    :description="`Compare Walala and Mathale performance, fair group-based rankings, and score distribution across ${APP_BRAND_NAME}.`"
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
                            Filter by grade, class group, and exam. Rankings are always calculated separately for each grade and group.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4 lg:grid-cols-3">
                        <div class="space-y-2">
                            <Label for="analysis-grade-filter">Grade</Label>
                            <Select
                                :model-value="props.analysis.selected_grade ?? '__all__'"
                                :disabled="filterVisitInProgress"
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
                            <Label for="analysis-group-filter">Class Group</Label>
                            <Select
                                :model-value="props.analysis.selected_class_group ?? '__all__'"
                                :disabled="filterVisitInProgress"
                                @update:model-value="updateClassGroup"
                            >
                                <SelectTrigger id="analysis-group-filter" class="w-full">
                                    <SelectValue placeholder="All groups" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">All groups</SelectItem>
                                    <SelectItem
                                        v-for="classGroup in props.analysis.class_group_options"
                                        :key="classGroup"
                                        :value="classGroup"
                                    >
                                        {{ classGroup }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label for="analysis-exam-filter">Exam</Label>
                            <Select
                                :model-value="props.analysis.selected_assessment_id ? String(props.analysis.selected_assessment_id) : '__all__'"
                                :disabled="filterVisitInProgress"
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
                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-border/70 pt-4 lg:col-span-3">
                            <p class="text-sm text-muted-foreground">
                                Changing the grade clears the selected exam so only matching exams are shown.
                            </p>
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                :disabled="!hasActiveFilters || filterVisitInProgress"
                                @click="resetFilters"
                            >
                                <RotateCcw class="size-4" />
                                Reset filters
                            </Button>
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
                        hint="Students included across the selected group scope."
                        :icon="Users"
                    />
                    <StatCard
                        label="Results Analysed"
                        :value="props.analysis.summary.results_count"
                        hint="Result records contributing to this analysis view."
                        :icon="BookMarked"
                    />
                    <StatCard
                        label="Group Not Assigned"
                        :value="props.analysis.summary.unassigned_students"
                        hint="Active students excluded until Walala or Mathale is assigned."
                        :icon="CircleAlert"
                        tone="warning"
                        :href="admin.students.index.url()"
                    />
                </div>

                <Card class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <CardTitle>Class Group Comparison</CardTitle>
                                <CardDescription>
                                    Walala and Mathale performance within the selected grade and exam scope.
                                </CardDescription>
                            </div>
                            <Badge variant="outline" class="rounded-full px-4 py-1.5">
                                <Users class="size-3.5" />
                                Walala vs Mathale
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-secondary/70">
                                    <TableHead>Group</TableHead>
                                    <TableHead>Students</TableHead>
                                    <TableHead>Results</TableHead>
                                    <TableHead>Highest</TableHead>
                                    <TableHead>Lowest</TableHead>
                                    <TableHead>Average</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="group in props.analysis.group_summaries" :key="group.class_group">
                                    <TableCell class="font-semibold text-foreground">{{ group.class_group }}</TableCell>
                                    <TableCell>{{ group.students_ranked }}</TableCell>
                                    <TableCell>{{ group.results_count }}</TableCell>
                                    <TableCell>{{ group.highest_mark !== null ? `${group.highest_mark}%` : '-' }}</TableCell>
                                    <TableCell>{{ group.lowest_mark !== null ? `${group.lowest_mark}%` : '-' }}</TableCell>
                                    <TableCell>{{ group.average_mark !== null ? `${group.average_mark}%` : '-' }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>

                <div class="grid gap-6 xl:grid-cols-2">
                    <Card class="section-card overflow-hidden py-0">
                        <CardHeader class="border-b border-border/80 py-5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <CardTitle>Ranking Overview</CardTitle>
                                    <CardDescription>
                                        Rank numbers restart for every grade and class group.
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
                                        <TableHead>Grade</TableHead>
                                        <TableHead>Group</TableHead>
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
                                            <TableCell>{{ rank.class_name || '-' }}</TableCell>
                                            <TableCell>{{ rank.class_group || 'Unassigned' }}</TableCell>
                                            <TableCell>
                                                {{ props.analysis.selected_assessment_id ? rank.marks : `${rank.marks}%` }}
                                            </TableCell>
                                        </TableRow>
                                    </template>
                                    <TableEmpty v-else :colspan="5" class="py-14">
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
