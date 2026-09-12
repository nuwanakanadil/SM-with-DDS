<script setup lang="ts">
import PageHero from '@/components/PageHero.vue';
import Pagination from '@/components/Pagination.vue';
import { Badge } from '@/components/ui/badge';
import { IconButton } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import SearchableSelect from '@/components/ui/searchable-select/SearchableSelect.vue';
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
import resultsRoutes from '@/routes/admin/results';
import type { Assessment } from '@/types/assessment';
import type { PaginatedResponse } from '@/types';
import { formatDateOnly } from '@/utils/dateTime';
import { Head, router } from '@inertiajs/vue3';
import { CircleCheckBig, ClipboardList, TriangleAlert } from 'lucide-vue-next';
import { computed, reactive, watch } from 'vue';

type PendingResult = {
    student_id: number;
    admission_no: string;
    first_name: string;
    last_name?: string | null;
    student_class_name?: string | null;
    assessment_id: number;
    assessment_title: string;
    assessment_class_name?: string | null;
    assessment_date?: string | null;
    total_marks: number;
};

const props = defineProps<{
    assessments: Assessment[];
    gradeOptions: string[];
    filters: {
        grade?: string | null;
        assessment_id?: number | null;
    };
    pendingResults: PaginatedResponse<PendingResult>;
}>();

const filters = reactive({
    grade: props.filters.grade ?? '',
    assessment_id: props.filters.assessment_id ? String(props.filters.assessment_id) : '',
});

const gradeOptions = computed(() => [
    {
        key: '',
        label: 'All grades',
    },
    ...props.gradeOptions.map((grade) => ({
        key: grade,
        label: grade,
    })),
]);

const assessmentOptions = computed(() => [
    {
        key: '',
        label: 'All exams',
    },
    ...props.assessments.map((assessment) => ({
        key: String(assessment.id),
        label: assessment.class_name ? `${assessment.title} (${assessment.class_name})` : `${assessment.title} (All grades)`,
    })),
]);

const applyFilters = () => {
    router.get(resultsRoutes.pending({
        query: {
            grade: filters.grade || undefined,
            assessment_id: filters.assessment_id || undefined,
        },
    }), undefined, {
        preserveState: true,
        replace: true,
    });
};

watch(() => [filters.grade, filters.assessment_id], () => {
    applyFilters();
});

const resultEntryHref = (row: PendingResult) => resultsRoutes.create({
    query: {
        assessment_id: row.assessment_id,
        student_id: row.student_id,
    },
}).url;

const openResultEntry = (row: PendingResult) => {
    router.visit(resultEntryHref(row));
};
</script>

<template>
    <Head title="Pending Results" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Admin' },
            { title: 'Results', href: resultsRoutes.index.url() },
            { title: 'Pending Results', href: resultsRoutes.pending.url() },
        ]"
    >
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Results Follow-up"
                    title="Pending Results"
                    description="Review the student and exam combinations that still do not have a saved mark."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <TriangleAlert class="size-3.5" />
                            {{ props.pendingResults.total }} missing entries
                        </Badge>
                    </template>
                    <template #actions>
                        <IconButton :icon="CircleCheckBig" :link="resultsRoutes.create()" size="lg">
                            Enter Marks
                        </IconButton>
                    </template>
                </PageHero>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Filters</CardTitle>
                        <CardDescription>
                            Narrow the list by grade or by a specific exam.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-4 md:grid-cols-2">
                            <SearchableSelect
                                v-model="filters.grade"
                                :options="gradeOptions"
                                placeholder="All grades"
                                class="justify-between"
                            />
                            <SearchableSelect
                                v-model="filters.assessment_id"
                                :options="assessmentOptions"
                                placeholder="All exams"
                                class="justify-between"
                            />
                        </div>
                    </CardContent>
                </Card>

                <Card class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <CardTitle>Missing Marks</CardTitle>
                                <CardDescription>
                                    Each row is one student missing one exam result.
                                </CardDescription>
                            </div>
                            <Badge variant="outline" class="rounded-full px-4 py-1.5">
                                <ClipboardList class="size-3.5" />
                                {{ props.pendingResults.total }} records
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-secondary/70">
                                    <TableHead>Student</TableHead>
                                    <TableHead>Admission No</TableHead>
                                    <TableHead>Grade</TableHead>
                                    <TableHead>Exam</TableHead>
                                    <TableHead>Exam Grade</TableHead>
                                    <TableHead>Date</TableHead>
                                    <TableHead>Total Marks</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="props.pendingResults.data.length">
                                    <TableRow
                                        v-for="row in props.pendingResults.data"
                                        :key="`${row.student_id}-${row.assessment_id}`"
                                        role="link"
                                        tabindex="0"
                                        class="cursor-pointer"
                                        :title="`Enter marks for ${row.first_name} ${row.last_name ?? ''} - ${row.assessment_title}`"
                                        @click="openResultEntry(row)"
                                        @keydown.enter.prevent="openResultEntry(row)"
                                        @keydown.space.prevent="openResultEntry(row)"
                                    >
                                        <TableCell class="font-semibold text-foreground">
                                            {{ row.first_name }} {{ row.last_name ?? '' }}
                                        </TableCell>
                                        <TableCell>{{ row.admission_no }}</TableCell>
                                        <TableCell>{{ row.student_class_name || '-' }}</TableCell>
                                        <TableCell>{{ row.assessment_title }}</TableCell>
                                        <TableCell>{{ row.assessment_class_name || 'All grades' }}</TableCell>
                                        <TableCell>{{ formatDateOnly(row.assessment_date) }}</TableCell>
                                        <TableCell>{{ row.total_marks }}</TableCell>
                                    </TableRow>
                                </template>

                                <TableEmpty v-else :colspan="7" class="py-14">
                                    No pending results found for the selected filters.
                                </TableEmpty>
                            </TableBody>
                        </Table>

                        <Pagination :response="props.pendingResults" />
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
