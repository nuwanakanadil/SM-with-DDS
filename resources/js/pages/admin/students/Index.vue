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
import studentsRoutes from '@/routes/admin/students';
import type { PaginatedResponse } from '@/types';
import type { Student } from '@/types/student';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, ShieldCheck, Trash2, Users } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

const props = defineProps<{
    students: PaginatedResponse<Student>;
    filters?: {
        search?: string;
    };
}>();

const filters = reactive({
    search: props.filters?.search ?? '',
});
const isSearching = ref(false);

const activeStudents = computed(
    () => props.students.data.filter((student) => student.is_active).length,
);

const applyFilters = () => {
    router.get(studentsRoutes.index({
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

const remove = (student: Student) => {
    router.delete(studentsRoutes.destroy(student));
};
</script>

<template>
    <Head title="Students" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Students', href: studentsRoutes.index.url() }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Student Registry"
                    title="Manage Student Records"
                    description="Create, review, and maintain the student directory used for login access, marks entry, and rank generation."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <Users class="size-3.5" />
                            {{ students.total }} total record(s)
                        </Badge>
                    </template>
                    <template #actions>
                        <IconButton :icon="Plus" :link="studentsRoutes.create()" size="lg">
                            Add Student
                        </IconButton>
                    </template>
                </PageHero>

                <div class="grid gap-5 md:grid-cols-2">
                    <StatCard
                        label="Total Students"
                        :value="students.total"
                        :hint="`All student profiles currently stored in ${APP_BRAND_NAME}.`"
                        :icon="Users"
                    />
                    <StatCard
                        label="Active On Page"
                        :value="activeStudents"
                        hint="Students currently marked active in this result set."
                        :icon="ShieldCheck"
                        tone="success"
                    />
                </div>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Search Directory</CardTitle>
                        <CardDescription>
                            Find a student by name or admission number.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-3 lg:flex-row">
                        <div class="flex-1">
                            <SearchBar
                                v-model="isSearching"
                                :search="filters.search"
                                placeholder="Search students"
                                @process-search="processSearch"
                            />
                        </div>
                        <div class="flex gap-3">
                            <Button variant="ghost" @click="clearFilters">
                                Reset
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <Card class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <CardTitle>Student Directory</CardTitle>
                                <CardDescription>
                                    Professional overview of the student registry.
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>

                    <CardContent class="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-secondary/70">
                                    <TableHead>Admission No</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Class</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="students.data.length">
                                    <TableRow
                                        v-for="student in students.data"
                                        :key="student.id"
                                    >
                                        <TableCell class="font-semibold text-foreground">
                                            {{ student.admission_no }}
                                        </TableCell>
                                        <TableCell>
                                            {{ student.first_name }} {{ student.last_name }}
                                        </TableCell>
                                        <TableCell>{{ student.class_name ?? '-' }}</TableCell>
                                        <TableCell>{{ student.email ?? '-' }}</TableCell>
                                        <TableCell>
                                            <Badge :variant="student.is_active ? 'default' : 'secondary'">
                                                {{ student.is_active ? 'Active' : 'Inactive' }}
                                            </Badge>
                                        </TableCell>
                                        <TableCell class="text-right">
                                            <div class="flex justify-end gap-2">
                                                <Button as-child variant="outline" size="icon-sm">
                                                    <Link
                                                        :href="studentsRoutes.edit(student)"
                                                        aria-label="Edit student"
                                                        title="Edit student"
                                                    >
                                                        <Pencil />
                                                    </Link>
                                                </Button>
                                                <ConfirmDialog
                                                    :title="`Delete ${student.first_name}?`"
                                                    :description="`This removes the student record from ${APP_BRAND_NAME}.`"
                                                    confirm-label="Delete Student"
                                                    @confirm="remove(student)"
                                                >
                                                    <template #trigger>
                                                        <Button
                                                            variant="destructive"
                                                            size="icon-sm"
                                                            aria-label="Delete student"
                                                            title="Delete student"
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
                                    No students have been added to {{ APP_BRAND_NAME }} yet. Add your first student to begin managing academic records.
                                </TableEmpty>
                            </TableBody>
                        </Table>

                        <Pagination :response="students" />
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
