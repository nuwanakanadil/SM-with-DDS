<script setup lang="ts">
import { APP_BRAND_NAME } from '@/constants/branding';
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import PageHero from '@/components/PageHero.vue';
import Pagination from '@/components/Pagination.vue';
import SearchBar from '@/components/SearchBar.vue';
import StatCard from '@/components/StatCard.vue';
import { Badge } from '@/components/ui/badge';
import { Button, IconButton } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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
import assessmentsRoutes from '@/routes/admin/assessments';
import type { PaginatedResponse } from '@/types';
import type { Assessment } from '@/types/assessment';
import { Head, Link, router } from '@inertiajs/vue3';
import { BookCheck, BookMarked, Pencil, ScrollText, ShieldCheck, Trash2 } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

const props = defineProps<{
    assessments: PaginatedResponse<Assessment>;
    filters?: {
        search?: string;
    };
}>();

const filters = reactive({
    search: props.filters?.search ?? '',
});
const isSearching = ref(false);

const publishedCount = computed(
    () => props.assessments.data.filter((assessment) => assessment.is_published).length,
);

const applyFilters = () => {
    router.get(assessmentsRoutes.index({
        query: {
            search: filters.search || undefined,
        },
    }), undefined, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            isSearching.value = false;
        },
    });
};

const clearFilters = () => {
    filters.search = '';
    applyFilters();
};

const processSearch = (value: string) => {
    filters.search = value;
    applyFilters();
};

const remove = (assessment: Assessment) => {
    router.delete(assessmentsRoutes.destroy(assessment));
};
</script>

<template>
    <Head title="Assessments" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Assessments', href: assessmentsRoutes.index.url() }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Assessment Library"
                    title="Manage Assessments"
                    description="Organise papers, class scope, dates, total marks, and publication status with a clear professional workflow."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <ScrollText class="size-3.5" />
                            {{ assessments.total }} assessment(s)
                        </Badge>
                    </template>
                    <template #actions>
                        <IconButton :icon="BookCheck" :link="assessmentsRoutes.create()" size="lg">
                            Add Assessment
                        </IconButton>
                    </template>
                </PageHero>

                <div class="grid gap-5 md:grid-cols-2">
                    <StatCard
                        label="Total Assessments"
                        :value="assessments.total"
                        hint="All assessment records inside the academic workspace."
                        :icon="BookMarked"
                    />
                    <StatCard
                        label="Published On Page"
                        :value="publishedCount"
                        hint="Assessments visible to students from the current result set."
                        :icon="ShieldCheck"
                        tone="success"
                    />
                </div>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Search Assessments</CardTitle>
                        <CardDescription>
                            Look up an assessment by title.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-3 lg:flex-row">
                        <div class="flex-1">
                            <SearchBar
                                v-model="isSearching"
                                :search="filters.search"
                                placeholder="Search assessments"
                                @process-search="processSearch"
                            />
                        </div>
                        <div class="flex gap-3">
                            <Button variant="ghost" @click="clearFilters">Reset</Button>
                        </div>
                    </CardContent>
                </Card>

                <Card class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <CardTitle>Assessment Directory</CardTitle>
                                <CardDescription>
                                    A clean operational view of created and published assessments.
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>

                    <CardContent class="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-secondary/70">
                                    <TableHead>Title</TableHead>
                                    <TableHead>Class</TableHead>
                                    <TableHead>Date</TableHead>
                                    <TableHead>Total</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="assessments.data.length">
                                    <TableRow
                                        v-for="assessment in assessments.data"
                                        :key="assessment.id"
                                    >
                                        <TableCell class="font-semibold text-foreground">
                                            {{ assessment.title }}
                                        </TableCell>
                                        <TableCell>{{ assessment.class_name ?? '-' }}</TableCell>
                                        <TableCell>{{ assessment.assessment_date ?? '-' }}</TableCell>
                                        <TableCell>{{ assessment.total_marks }}</TableCell>
                                        <TableCell>
                                            <Badge :variant="assessment.is_published ? 'default' : 'secondary'">
                                                {{ assessment.is_published ? 'Published' : 'Draft' }}
                                            </Badge>
                                        </TableCell>
                                        <TableCell class="text-right">
                                            <div class="flex justify-end gap-2">
                                                <Button as-child variant="outline" size="icon-sm">
                                                    <Link
                                                        :href="assessmentsRoutes.edit(assessment)"
                                                        aria-label="Edit assessment"
                                                        title="Edit assessment"
                                                    >
                                                        <Pencil />
                                                    </Link>
                                                </Button>
                                                <ConfirmDialog
                                                    :title="`Delete ${assessment.title}?`"
                                                    description="This removes the assessment and any dependent records."
                                                    confirm-label="Delete Assessment"
                                                    @confirm="remove(assessment)"
                                                >
                                                    <template #trigger>
                                                        <Button
                                                            variant="destructive"
                                                            size="icon-sm"
                                                            aria-label="Delete assessment"
                                                            title="Delete assessment"
                                                        >
                                                            <Trash2 />
                                                        </Button>
                                                    </template>
                                                </ConfirmDialog>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </template>

                                <TableEmpty v-else :colspan="6" class="py-14">
                                    No assessments have been added to {{ APP_BRAND_NAME }} yet. Add one to start building the academic cycle.
                                </TableEmpty>
                            </TableBody>
                        </Table>

                        <Pagination :response="assessments" />
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
