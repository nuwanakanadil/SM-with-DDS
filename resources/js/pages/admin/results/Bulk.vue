<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import PageHero from '@/components/PageHero.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import SearchableSelect from '@/components/ui/searchable-select/SearchableSelect.vue';
import { Table, TableBody, TableCell, TableEmpty, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import type { Assessment } from '@/types/assessment';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Save, Search, Sheet } from 'lucide-vue-next';
import { computed, ref } from 'vue';

type BulkRow = {
    student_id: number;
    admission_no: string;
    student_name: string;
    class_name?: string | null;
    class_group?: string | null;
    marks?: string | number | null;
    remarks?: string | null;
};

const props = defineProps<{
    assessments: Assessment[];
    selectedAssessment?: Assessment | null;
    rows: BulkRow[];
}>();

const assessmentId = ref(props.selectedAssessment ? String(props.selectedAssessment.id) : '');
const search = ref('');
const form = useForm({
    assessment_id: props.selectedAssessment?.id ?? null,
    results: props.rows.map((row) => ({
        student_id: row.student_id,
        marks: row.marks ?? '',
        remarks: row.remarks ?? '',
    })),
});

const assessmentOptions = computed(() => props.assessments.map((assessment) => ({
    key: String(assessment.id),
    label: `${assessment.title} · ${assessment.class_name ?? 'All grades'} · ${assessment.class_group ?? 'Both groups'}`,
})));

const visibleIndexes = computed(() => {
    const term = search.value.trim().toLowerCase();
    return props.rows
        .map((row, index) => ({ row, index }))
        .filter(({ row }) => !term || `${row.admission_no} ${row.student_name} ${row.class_group ?? ''}`.toLowerCase().includes(term));
});

const loadAssessment = (value: string) => {
    assessmentId.value = value;
    router.get('/admin/results/bulk', { assessment_id: value }, { preserveState: false, replace: true });
};

const submit = () => form.post('/admin/results/bulk', { preserveScroll: true });
</script>

<template>
    <Head title="Bulk Marks Entry" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Results', href: '/admin/results' }, { title: 'Bulk Entry' }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Fast Results Workflow"
                    title="Bulk Marks Entry"
                    description="Enter an entire exam in one grid. Only students eligible for the selected grade and Walala/Mathale scope are shown."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5"><Sheet class="size-3.5" />{{ rows.length }} eligible students</Badge>
                    </template>
                </PageHero>

                <Card class="section-card">
                    <CardHeader><CardTitle>Select Exam</CardTitle><CardDescription>The student grid updates to match the exam scope.</CardDescription></CardHeader>
                    <CardContent class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_20rem]">
                        <SearchableSelect :model-value="assessmentId" :options="assessmentOptions" placeholder="Choose an exam" class="justify-between" @update:model-value="(value) => loadAssessment(String(value))" />
                        <div class="relative">
                            <Search class="absolute left-3 top-3 size-4 text-muted-foreground" />
                            <Input v-model="search" class="pl-9" placeholder="Find student or admission no" />
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="selectedAssessment" class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <CardTitle>{{ selectedAssessment.title }}</CardTitle>
                        <CardDescription>
                            {{ selectedAssessment.class_name ?? 'All grades' }} · {{ selectedAssessment.class_group ?? 'Walala + Mathale' }} · Maximum {{ selectedAssessment.total_marks }}
                        </CardDescription>
                        <InputError :message="form.errors.results" />
                    </CardHeader>
                    <CardContent class="p-0">
                        <form @submit.prevent="submit">
                            <Table>
                                <TableHeader><TableRow class="bg-secondary/70"><TableHead>Admission No</TableHead><TableHead>Student</TableHead><TableHead>Grade / Group</TableHead><TableHead class="w-40">Marks</TableHead><TableHead>Remarks</TableHead></TableRow></TableHeader>
                                <TableBody>
                                    <TableRow v-for="item in visibleIndexes" :key="item.row.student_id">
                                        <TableCell class="font-semibold">{{ item.row.admission_no }}</TableCell>
                                        <TableCell>{{ item.row.student_name }}</TableCell>
                                        <TableCell>{{ item.row.class_name }} · {{ item.row.class_group }}</TableCell>
                                        <TableCell><Input v-model="form.results[item.index].marks" type="number" min="0" :max="selectedAssessment.total_marks" step="0.01" aria-label="Marks" /></TableCell>
                                        <TableCell><Input v-model="form.results[item.index].remarks" maxlength="1000" placeholder="Optional" aria-label="Remarks" /></TableCell>
                                    </TableRow>
                                    <TableEmpty v-if="!visibleIndexes.length" :colspan="5" class="py-12">No eligible students match this search.</TableEmpty>
                                </TableBody>
                            </Table>
                            <div class="sticky bottom-0 flex justify-end border-t bg-background/95 p-5 backdrop-blur">
                                <Button size="lg" :disabled="form.processing"><Save class="size-4" />{{ form.processing ? 'Saving…' : 'Save All Entered Marks' }}</Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <Card v-else class="empty-state-panel"><CardContent class="py-0 text-muted-foreground">Create or select an exam to start bulk entry.</CardContent></Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
