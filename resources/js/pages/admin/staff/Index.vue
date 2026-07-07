<script setup lang="ts">
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
import type { PaginatedResponse } from '@/types';
import type { StaffAccount } from '@/types/staff';
import { formatDateOnly } from '@/utils/dateTime';
import { Head, Link, router } from '@inertiajs/vue3';
import { BriefcaseBusiness, Pencil, ShieldCheck, Trash2, UserRoundPlus, Users } from 'lucide-vue-next';
import { computed, reactive, ref } from 'vue';

const props = defineProps<{
    staffMembers: PaginatedResponse<StaffAccount>;
    filters?: {
        search?: string;
        sort?: string;
    };
}>();

const filters = reactive({
    search: props.filters?.search ?? '',
    sort: props.filters?.sort ?? '',
});

const isSearching = ref(false);

const verifiedCount = computed(
    () => props.staffMembers.data.filter((staff) => staff.email_verified_at).length,
);

const applyFilters = () => {
    router.get('/admin/staff', {
        search: filters.search || undefined,
        sort: filters.sort || undefined,
    }, {
        preserveState: true,
        replace: true,
        onFinish: () => {
            isSearching.value = false;
        },
    });
};

const processSearch = (value: string) => {
    filters.search = value;
    applyFilters();
};

const updateSort = (value: unknown) => {
    filters.sort = value == null || value === '__all__' ? '' : String(value);
    applyFilters();
};

const clearFilters = () => {
    filters.search = '';
    filters.sort = '';
    applyFilters();
};

const remove = (staff: StaffAccount) => {
    router.delete(`/admin/staff/${staff.id}`);
};
</script>

<template>
    <Head title="Staff Accounts" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Staff Accounts', href: '/admin/staff' }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Staff Access"
                    title="Manage Staff Accounts"
                    description="Create and maintain staff login accounts for the academic team that helps run student records, exams, and results."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <BriefcaseBusiness class="size-3.5" />
                            {{ staffMembers.total }} staff account(s)
                        </Badge>
                    </template>
                    <template #actions>
                        <IconButton :icon="UserRoundPlus" link="/admin/staff/create" size="lg">
                            Add Staff
                        </IconButton>
                    </template>
                </PageHero>

                <div class="grid gap-5 md:grid-cols-2">
                    <StatCard
                        label="Total Staff"
                        :value="staffMembers.total"
                        hint="All staff login accounts currently available in the admin workspace."
                        :icon="Users"
                    />
                    <StatCard
                        label="Verified On Page"
                        :value="verifiedCount"
                        hint="Staff records on this page that can sign in immediately."
                        :icon="ShieldCheck"
                        tone="success"
                    />
                </div>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Search and Filter</CardTitle>
                        <CardDescription>
                            Find staff accounts by name or email and choose how the list is sorted.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4 lg:grid-cols-4">
                        <div class="lg:col-span-3">
                            <SearchBar
                                v-model="isSearching"
                                :search="filters.search"
                                placeholder="Search staff"
                                @process-search="processSearch"
                            />
                        </div>
                        <div class="space-y-2">
                            <Label for="staff-sort-filter">Sort</Label>
                            <Select
                                :model-value="filters.sort || '__all__'"
                                @update:model-value="updateSort"
                            >
                                <SelectTrigger id="staff-sort-filter" class="w-full">
                                    <SelectValue placeholder="Newest first" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="__all__">Newest first</SelectItem>
                                    <SelectItem value="name_asc">Name A-Z</SelectItem>
                                    <SelectItem value="name_desc">Name Z-A</SelectItem>
                                    <SelectItem value="email_asc">Email A-Z</SelectItem>
                                    <SelectItem value="email_desc">Email Z-A</SelectItem>
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
                        <CardTitle>Staff Directory</CardTitle>
                        <CardDescription>
                            Operational view of staff accounts with immediate edit and removal access.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-secondary/70">
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Created</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="staffMembers.data.length">
                                    <TableRow v-for="staff in staffMembers.data" :key="staff.id">
                                        <TableCell class="font-semibold text-foreground">
                                            {{ staff.name }}
                                        </TableCell>
                                        <TableCell>{{ staff.email }}</TableCell>
                                        <TableCell>{{ formatDateOnly(staff.created_at) }}</TableCell>
                                        <TableCell>
                                            <Badge :variant="staff.email_verified_at ? 'default' : 'secondary'">
                                                {{ staff.email_verified_at ? 'Ready' : 'Pending' }}
                                            </Badge>
                                        </TableCell>
                                        <TableCell class="text-right">
                                            <div class="flex justify-end gap-2">
                                                <Button as-child variant="outline" size="icon-sm">
                                                    <Link :href="`/admin/staff/${staff.id}/edit`" aria-label="Edit staff" title="Edit staff">
                                                        <Pencil />
                                                    </Link>
                                                </Button>
                                                <ConfirmDialog
                                                    :title="`Delete ${staff.name}?`"
                                                    description="This removes the staff account from the admin workspace."
                                                    confirm-label="Delete Staff"
                                                    @confirm="remove(staff)"
                                                >
                                                    <template #trigger>
                                                        <Button variant="destructive" size="icon-sm" aria-label="Delete staff" title="Delete staff">
                                                            <Trash2 />
                                                        </Button>
                                                    </template>
                                                </ConfirmDialog>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </template>
                                <TableEmpty v-else :colspan="5" class="py-14">
                                    No staff accounts have been created yet. Add one to give your team admin-side access.
                                </TableEmpty>
                            </TableBody>
                        </Table>

                        <Pagination :response="staffMembers" />
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
