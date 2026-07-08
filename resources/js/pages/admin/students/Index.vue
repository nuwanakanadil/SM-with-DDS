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
import studentsRoutes from '@/routes/admin/students';
import type { PaginatedResponse } from '@/types';
import type { Student } from '@/types/student';
import { Head, Link, router } from '@inertiajs/vue3';
import { KeyRound, Pencil, ShieldCheck, Trash2, UserPlus, Users } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

const props = defineProps<{
    students: PaginatedResponse<Student>;
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

const activeStudents = computed(
    () => props.students.data.filter((student) => student.is_active).length,
);

const applyFilters = () => {
    router.get(studentsRoutes.index({
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

const remove = (student: Student) => {
    router.delete(studentsRoutes.destroy(student));
};

const resendLogin = (student: Student) => {
    router.post(`/admin/students/${student.id}/resend-login`);
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
                        <IconButton :icon="UserPlus" :link="studentsRoutes.create()" size="lg">
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
                        <CardTitle>Search and Filter</CardTitle>
                        <CardDescription>
                            Narrow the student directory by name, grade, status, or sort order.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4 lg:grid-cols-4">
                        <div class="lg:col-span-4">
                            <SearchBar
                                v-model="isSearching"
                                :search="filters.search"
                                placeholder="Search students"
                                @process-search="processSearch"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="student-grade-filter">Grade</Label>
                            <Select
                                :model-value="filters.class_name || '__all__'"
                                @update:model-value="(value) => updateFilter('class_name', value)"
                            >
                                <SelectTrigger id="student-grade-filter" class="w-full">
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
                            <Label for="student-status-filter">Status</Label>
                            <Select
                                :model-value="filters.status || '__all__'"
                                @update:model-value="(value) => updateFilter('status', value)"
                            >
                                <SelectTrigger id="student-status-filter" class="w-full">
                                    <SelectValue placeholder="All statuses" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">All statuses</SelectItem>
                                    <SelectItem value="active">Active only</SelectItem>
                                    <SelectItem value="inactive">Inactive only</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-2">
                            <Label for="student-sort-filter">Sort</Label>
                            <Select
                                :model-value="filters.sort || '__all__'"
                                @update:model-value="(value) => updateFilter('sort', value)"
                            >
                                <SelectTrigger id="student-sort-filter" class="w-full">
                                    <SelectValue placeholder="Newest first" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">Newest first</SelectItem>
                                    <SelectItem value="name_asc">Name A-Z</SelectItem>
                                    <SelectItem value="name_desc">Name Z-A</SelectItem>
                                    <SelectItem value="admission_asc">Admission No A-Z</SelectItem>
                                    <SelectItem value="admission_desc">Admission No Z-A</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="flex items-end gap-3">
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
                                                <Button
                                                    v-if="student.email"
                                                    variant="outline"
                                                    size="sm"
                                                    @click="resendLogin(student)"
                                                >
                                                    <KeyRound class="size-4" />
                                                    Resend Login
                                                </Button>
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
