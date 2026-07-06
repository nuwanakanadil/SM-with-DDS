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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import assessments from '@/routes/admin/assessments';
import type { Assessment } from '@/types/assessment';
import { Head, useForm } from '@inertiajs/vue3';
import { CalendarDays, ClipboardCheck, GraduationCap, NotebookPen, Save, X } from 'lucide-vue-next';

const props = defineProps<{
    assessment?: Assessment;
    gradeOptions: string[];
}>();

const isEdit = Boolean(props.assessment);

const form = useForm({
    title: props.assessment?.title ?? '',
    class_name: props.assessment?.class_name ?? '',
    assessment_date: props.assessment?.assessment_date ?? '',
    total_marks: props.assessment?.total_marks ?? 100,
    is_published: props.assessment?.is_published ?? false,
});

const submit = () =>
    props.assessment
        ? form.submit(assessments.update(props.assessment))
        : form.submit(assessments.store());

const updateClassName = (value: unknown) => {
    const normalizedValue = value == null ? '' : String(value);
    form.class_name = !normalizedValue || normalizedValue === '__none__' ? '' : normalizedValue;
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Assessment' : 'Add Assessment'" />
    <AppLayout
        :breadcrumbs="[
            { title: 'Admin' },
            { title: 'Assessments', href: assessments.index.url() },
            { title: isEdit ? 'Edit' : 'Create' },
        ]"
    >
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Assessment Setup"
                    :title="isEdit ? 'Edit Assessment' : 'Create Assessment'"
                    description="Define how an assessment appears, who it belongs to, when it happens, and whether students can already see it."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <NotebookPen class="size-3.5" />
                            {{ isEdit ? 'Update assessment' : 'New assessment' }}
                        </Badge>
                    </template>
                    <template #actions>
                        <IconButton :icon="NotebookPen" :link="assessments.index()" variant="outline">
                            Back to list
                        </IconButton>
                    </template>
                </PageHero>

                <Card class="section-card max-w-6xl">
                    <CardHeader>
                        <CardTitle>Assessment Details</CardTitle>
                        <CardDescription>
                            Keep this information accurate so student dashboards and ranking views remain trustworthy.
                        </CardDescription>
                    </CardHeader>

                    <CardContent>
                        <form class="space-y-8" @submit.prevent="submit">
                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <NotebookPen class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Core Details
                                    </h3>
                                </div>
                                <div class="grid gap-5 md:grid-cols-2">
                                    <div class="space-y-2 md:col-span-2">
                                        <Label for="title">Title</Label>
                                        <Input id="title" v-model="form.title" placeholder="Enter assessment title" />
                                        <InputError :message="form.errors.title" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="class_name">Class</Label>
                                        <Select
                                            :model-value="form.class_name || '__none__'"
                                            @update:model-value="updateClassName"
                                        >
                                            <SelectTrigger id="class_name" class="w-full">
                                                <SelectValue placeholder="Select grade" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="__none__">All classes</SelectItem>
                                                <SelectItem v-for="grade in props.gradeOptions" :key="grade" :value="grade">
                                                    {{ grade }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError :message="form.errors.class_name" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="assessment_date">Assessment Date</Label>
                                        <Input id="assessment_date" v-model="form.assessment_date" type="date" />
                                        <InputError :message="form.errors.assessment_date" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="total_marks">Total Marks</Label>
                                        <Input id="total_marks" v-model="form.total_marks" type="number" min="1" placeholder="Enter total marks" />
                                        <InputError :message="form.errors.total_marks" />
                                    </div>
                                </div>
                            </section>

                            <section class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <ClipboardCheck class="size-4 text-primary" />
                                    <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                        Publication
                                    </h3>
                                </div>
                                <div class="rounded-[1.5rem] border border-dashed border-border bg-secondary/50 p-5">
                                    <label class="flex items-start gap-3">
                                        <input
                                            v-model="form.is_published"
                                            type="checkbox"
                                            class="mt-1 h-4 w-4 rounded border-input text-primary focus:ring-ring"
                                        >
                                        <span>
                                            <span class="block font-semibold text-foreground">Published to students</span>
                                            <span class="mt-1 block text-sm text-muted-foreground">
                                                Only published assessments should appear in student dashboards and placement views.
                                            </span>
                                        </span>
                                    </label>
                                    <InputError class="mt-3" :message="form.errors.is_published" />
                                </div>
                            </section>

                            <div class="grid gap-4 md:grid-cols-3">
                                <div class="rounded-[1.5rem] border border-border bg-card p-4">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-foreground">
                                        <GraduationCap class="size-4 text-primary" />
                                        Class visibility
                                    </div>
                                    <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                        Students only see assessments that match their class assignment.
                                    </p>
                                </div>
                                <div class="rounded-[1.5rem] border border-border bg-card p-4">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-foreground">
                                        <CalendarDays class="size-4 text-primary" />
                                        Timeline clarity
                                    </div>
                                    <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                        Dates help teams review what has already run and what still needs entry.
                                    </p>
                                </div>
                                <div class="rounded-[1.5rem] border border-border bg-card p-4">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-foreground">
                                        <ClipboardCheck class="size-4 text-primary" />
                                        Ranking readiness
                                    </div>
                                    <p class="mt-2 text-sm leading-6 text-muted-foreground">
                                        Published assessments are ready to feed downstream ranking and result workflows.
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-border/80 pt-6">
                                <Button :disabled="form.processing" size="lg">
                                    <Save class="size-4" />
                                    {{ form.processing ? 'Saving...' : 'Save Assessment' }}
                                </Button>
                                <IconButton :icon="X" :link="assessments.index()" variant="outline" size="lg">
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
