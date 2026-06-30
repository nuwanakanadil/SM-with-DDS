<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import PageHero from '@/components/PageHero.vue';
import { Badge } from '@/components/ui/badge';
import { Button, IconButton } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import resultsRoutes from '@/routes/admin/results';
import SearchableSelect from '@/components/ui/searchable-select/SearchableSelect.vue';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import type { Assessment } from '@/types/assessment';
import type { AssessmentResult } from '@/types/result';
import type { Student } from '@/types/student';
import { Head, useForm } from '@inertiajs/vue3';
import { BookOpenCheck, ClipboardPen, Save, Users, X } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    result?: AssessmentResult;
    assessments: Assessment[];
    students: Student[];
}>();

const isEdit = Boolean(props.result);

const form = useForm({
    assessment_id: props.result?.assessment_id ? String(props.result.assessment_id) : '',
    student_id: props.result?.student_id ? String(props.result.student_id) : '',
    marks: props.result?.marks ?? '',
    remarks: props.result?.remarks ?? '',
});

const assessmentOptions = computed(() =>
    props.assessments.map((assessment) => ({
        key: String(assessment.id),
        label: assessment.title,
    })),
);

const studentOptions = computed(() =>
    props.students.map((student) => ({
        key: String(student.id),
        label: `${student.admission_no} - ${student.first_name} ${student.last_name ?? ''}`.trim(),
    })),
);

const submit = () =>
    props.result
        ? form.submit(resultsRoutes.update(props.result))
        : form.submit(resultsRoutes.store());
</script>

<template>
    <Head :title="isEdit ? 'Edit Result' : 'Enter Marks'" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Admin' },
            { title: 'Results', href: resultsRoutes.index.url() },
            { title: isEdit ? 'Edit' : 'Create' },
        ]"
    >
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Results Entry"
                    :title="isEdit ? 'Edit Result Record' : 'Create Result Record'"
                    description="Map the right student to the right assessment, then record marks cleanly so rankings remain dependable."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <ClipboardPen class="size-3.5" />
                            Duplicate pairs are blocked
                        </Badge>
                    </template>
                    <template #actions>
                        <IconButton :icon="ClipboardPen" :link="resultsRoutes.index()" variant="outline">
                            Back to list
                        </IconButton>
                    </template>
                </PageHero>

                <Card class="section-card max-w-6xl">
                    <CardHeader>
                        <CardTitle>Result Entry</CardTitle>
                        <CardDescription>
                            Use structured selection and remarks to maintain a professional and auditable marks workflow.
                        </CardDescription>
                    </CardHeader>

                    <CardContent>
                        <form class="space-y-8" @submit.prevent="submit">
                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <BookOpenCheck class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Assessment & Student
                                    </h3>
                                </div>
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="assessment_id">Assessment</Label>
                                        <SearchableSelect
                                            id="assessment_id"
                                            v-model="form.assessment_id"
                                            :options="assessmentOptions"
                                            placeholder="Select assessment"
                                        />
                                        <InputError :message="form.errors.assessment_id" />
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="student_id">Student</Label>
                                        <SearchableSelect
                                            id="student_id"
                                            v-model="form.student_id"
                                            :options="studentOptions"
                                            placeholder="Select student"
                                        />
                                        <InputError :message="form.errors.student_id" />
                                    </div>
                                </div>
                            </section>

                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <Users class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Mark Details
                                    </h3>
                                </div>
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="marks">Marks</Label>
                                        <Input id="marks" v-model="form.marks" type="number" step="0.01" placeholder="Enter marks" />
                                        <InputError :message="form.errors.marks" />
                                    </div>
                                    <div class="space-y-2 md:col-span-2">
                                        <Label for="remarks">Remarks</Label>
                                        <Textarea id="remarks" v-model="form.remarks" placeholder="Add optional remarks or context" />
                                        <InputError :message="form.errors.remarks" />
                                    </div>
                                </div>
                            </section>

                            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-border/80 pt-6">
                                <Button :disabled="form.processing" size="lg">
                                    <Save class="size-4" />
                                    {{ form.processing ? 'Saving...' : 'Save Result' }}
                                </Button>
                                <IconButton :icon="X" :link="resultsRoutes.index()" variant="outline" size="lg">
                                    Cancel
                                </IconButton>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
