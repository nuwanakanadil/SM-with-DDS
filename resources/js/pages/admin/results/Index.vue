<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import PageHero from '@/components/PageHero.vue';
import Pagination from '@/components/Pagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button, IconButton } from '@/components/ui/button';
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
import type { PaginatedResponse } from '@/types';
import type { Assessment } from '@/types/assessment';
import type { AssessmentResult } from '@/types/result';
import { Head, Link, router } from '@inertiajs/vue3';
import { CircleCheckBig, ClipboardList, Pencil, Trash2 } from 'lucide-vue-next';
import { computed, reactive, watch } from 'vue';

const props = defineProps<{
    assessments: Assessment[];
    selectedAssessment?: Assessment | null;
    results?: PaginatedResponse<AssessmentResult> | null;
}>();

const filters = reactive({
    assessment_id: props.selectedAssessment?.id ? String(props.selectedAssessment.id) : '',
});

const assessmentOptions = computed(() => [
    {
        key: '',
        label: 'All assessments',
    },
    ...props.assessments.map((assessment) => ({
        key: String(assessment.id),
        label: assessment.title,
    })),
]);

const applyFilter = () => {
    router.get(resultsRoutes.index({
        query: {
            assessment_id: filters.assessment_id || undefined,
        },
    }), undefined, {
        preserveState: true,
        replace: true,
    });
};

watch(() => filters.assessment_id, () => {
    applyFilter();
});

const remove = (result: AssessmentResult) => {
    router.delete(resultsRoutes.destroy(result));
};
</script>

<template>
    <Head title="Results" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Results', href: resultsRoutes.index.url() }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    :eyebrow="APP_SYSTEM_SUBTITLE"
                    title="Manage Exam Results"
                    :description="`Load an exam, review saved marks, and keep the published academic picture accurate across ${APP_BRAND_NAME}.`"
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <ClipboardList class="size-3.5" />
                            {{ props.results?.total ?? 0 }} visible records
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
                        <CardTitle>Exam Filter</CardTitle>
                        <CardDescription>
                            Choose the exam you want to inspect before editing marks or validating entries.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="max-w-xl">
                            <SearchableSelect
                                v-model="filters.assessment_id"
                                :options="assessmentOptions"
                                placeholder="Choose an exam"
                                class="justify-between"
                            />
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="props.selectedAssessment" class="section-card bg-secondary/45">
                    <CardHeader>
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <CardDescription>Selected Exam</CardDescription>
                                <CardTitle class="mt-1">
                                    {{ props.selectedAssessment.title }}
                                </CardTitle>
                            </div>
                            <Badge :variant="props.selectedAssessment.is_published ? 'default' : 'secondary'">
                                {{ props.selectedAssessment.is_published ? 'Published' : 'Draft' }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="text-[15px] leading-7 text-muted-foreground">
                        {{ props.selectedAssessment.class_name || 'All classes' }} / Total marks: {{ props.selectedAssessment.total_marks }}
                    </CardContent>
                </Card>

                <Card v-else class="empty-state-panel">
                    <CardContent class="py-0 text-[15px] leading-7 text-muted-foreground">
                        Select an assessment to review saved marks. Ranking and performance analysis now live on the Analysis page.
                    </CardContent>
                </Card>

                <Card class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <CardTitle>Recorded Results</CardTitle>
                                <CardDescription>
                                    Saved marks and remarks for the selected assessment.
                                </CardDescription>
                            </div>
                            <Badge variant="outline" class="rounded-full px-4 py-1.5">
                                {{ props.results?.total ?? 0 }} records
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-secondary/70">
                                    <TableHead>Student</TableHead>
                                    <TableHead>Marks</TableHead>
                                    <TableHead>Remarks</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="props.results?.data?.length">
                                    <TableRow
                                        v-for="result in props.results.data"
                                        :key="result.id"
                                    >
                                        <TableCell class="font-semibold text-foreground">
                                            {{ result.student?.first_name }} {{ result.student?.last_name }}
                                        </TableCell>
                                        <TableCell>{{ result.marks }}</TableCell>
                                        <TableCell>{{ result.remarks || '-' }}</TableCell>
                                        <TableCell class="text-right">
                                            <div class="flex justify-end gap-2">
                                                <Button as-child variant="outline" size="icon-sm">
                                                    <Link
                                                        :href="resultsRoutes.edit(result)"
                                                        aria-label="Edit result"
                                                        title="Edit result"
                                                    >
                                                        <Pencil />
                                                    </Link>
                                                </Button>
                                                <ConfirmDialog
                                                    title="Delete result?"
                                                    description="This permanently removes the recorded mark for this student."
                                                    confirm-label="Delete Result"
                                                    @confirm="remove(result)"
                                                >
                                                    <template #trigger>
                                                        <Button
                                                            variant="destructive"
                                                            size="icon-sm"
                                                            aria-label="Delete result"
                                                            title="Delete result"
                                                        >
                                                            <Trash2 />
                                                        </Button>
                                                    </template>
                                                </ConfirmDialog>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </template>

                                <TableEmpty v-else :colspan="4" class="py-14">
                                    No results have been recorded for the selected assessment in {{ APP_BRAND_NAME }} yet.
                                </TableEmpty>
                            </TableBody>
                        </Table>

                        <Pagination v-if="props.results" :response="props.results" />
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
