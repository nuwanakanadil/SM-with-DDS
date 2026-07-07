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
import assessmentsRoutes from '@/routes/admin/assessments';
import type { PaginatedResponse } from '@/types';
import type { Assessment } from '@/types/assessment';
import { formatDateOnly } from '@/utils/dateTime';
import { Head, Link, router } from '@inertiajs/vue3';
import { BookCheck, BookMarked, Pencil, ScrollText, ShieldCheck, Trash2 } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

const props = defineProps<{
    assessments: PaginatedResponse<Assessment>;
    gradeOptions: string[];
    filters?: {
        search?: string;
        class_name?: string;
        status?: string;
        sort?: string;
    };
}>();

const filters = reactive({
    search: props.filters?.search ?? '',
    class_name: props.filters?.class_name ?? '',
    status: props.filters?.status ?? '',
    sort: props.filters?.sort ?? '',
});
const isSearching = ref(false);

const publishedCount = computed(
    () => props.assessments.data.filter((assessment) => assessment.is_published).length,
);

const applyFilters = () => {
    router.get(assessmentsRoutes.index({
        query: {
            search: filters.search || undefined,
            class_name: filters.class_name || undefined,
            status: filters.status || undefined,
            sort: filters.sort || undefined,
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
    filters.class_name = '';
    filters.status = '';
    filters.sort = '';
    applyFilters();
};

const processSearch = (value: string) => {
    filters.search = value;
    applyFilters();
};

const updateFilter = (key: 'class_name' | 'status' | 'sort', value: unknown) => {
    filters[key] = value == null || value === '__all__' ? '' : String(value);
    applyFilters();
};

const remove = (assessment: Assessment) => {
    router.delete(assessmentsRoutes.destroy(assessment));
};
</script>

<template>
    <Head title="Exams" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Exams', href: assessmentsRoutes.index.url() }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Exam Library"
                    title="Manage Exams"
                    description="Organise papers, class scope, dates, total marks, and publication status with a clear professional workflow."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <ScrollText class="size-3.5" />
                            {{ assessments.total }} exam(s)
                        </Badge>
                    </template>
                    <template #actions>
                        <IconButton :icon="BookCheck" :link="assessmentsRoutes.create()" size="lg">
                            Add Exam
                        </IconButton>
                    </template>
                </PageHero>

                <div class="grid gap-5 md:grid-cols-2">
                    <StatCard
                        label="Total Exams"
                        :value="assessments.total"
                        hint="All exam records inside the academic workspace."
                        :icon="BookMarked"
                    />
                    <StatCard
                        label="Published Exams On Page"
                        :value="publishedCount"
                        hint="Exams visible to students from the current result set."
                        :icon="ShieldCheck"
                        tone="success"
                    />
                </div>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Search and Filter</CardTitle>
                        <CardDescription>
                            Refine the exam list by title, grade, publishing status, or sort order.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4 lg:grid-cols-4">
                        <div class="lg:col-span-4">
                            <SearchBar
                                v-model="isSearching"
                                :search="filters.search"
                                placeholder="Search exams"
                                @process-search="processSearch"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="assessment-grade-filter">Grade</Label>
                            <Select
                                :model-value="filters.class_name || '__all__'"
                                @update:model-value="(value) => updateFilter('class_name', value)"
                            >
                                <SelectTrigger id="assessment-grade-filter" class="w-full">
                                    <SelectValue placeholder="All grades" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">All grades</SelectItem>
                                    <SelectItem v-for="grade in props.gradeOptions" :key="grade" :value="grade">
                                        {{ grade }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label for="assessment-status-filter">Status</Label>
                            <Select
                                :model-value="filters.status || '__all__'"
                                @update:model-value="(value) => updateFilter('status', value)"
                            >
                                <SelectTrigger id="assessment-status-filter" class="w-full">
                                    <SelectValue placeholder="All statuses" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">All statuses</SelectItem>
                                    <SelectItem value="published">Published only</SelectItem>
                                    <SelectItem value="draft">Draft only</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label for="assessment-sort-filter">Sort</Label>
                            <Select
                                :model-value="filters.sort || '__all__'"
                                @update:model-value="(value) => updateFilter('sort', value)"
                            >
                                <SelectTrigger id="assessment-sort-filter" class="w-full">
                                    <SelectValue placeholder="Newest first" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">Newest first</SelectItem>
                                    <SelectItem value="date_asc">Oldest date first</SelectItem>
                                    <SelectItem value="date_desc">Newest date first</SelectItem>
                                    <SelectItem value="title_asc">Title A-Z</SelectItem>
                                    <SelectItem value="title_desc">Title Z-A</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="flex items-end gap-3">
                            <Button variant="ghost" @click="clearFilters">Reset</Button>
                        </div>
                    </CardContent>
                </Card>

                <Card class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <CardTitle>Exam Directory</CardTitle>
                                <CardDescription>
                                    A clean operational view of created and published exams.
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
                                        <TableCell>{{ formatDateOnly(assessment.assessment_date) }}</TableCell>
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
                                                        aria-label="Edit exam"
                                                        title="Edit exam"
                                                    >
                                                        <Pencil />
                                                    </Link>
                                                </Button>
                                                <ConfirmDialog
                                                    :title="`Delete ${assessment.title}?`"
                                                    description="This removes the exam and any dependent records."
                                                    confirm-label="Delete Exam"
                                                    @confirm="remove(assessment)"
                                                >
                                                    <template #trigger>
                                                        <Button
                                                            variant="destructive"
                                                            size="icon-sm"
                                                            aria-label="Delete exam"
                                                            title="Delete exam"
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
                                    No exams have been added to {{ APP_BRAND_NAME }} yet. Add one to start building the academic cycle.
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
