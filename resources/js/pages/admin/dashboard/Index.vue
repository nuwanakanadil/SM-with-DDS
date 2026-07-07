<script setup lang="ts">
import PageHero from '@/components/PageHero.vue';
import StatCard from '@/components/StatCard.vue';
import { Badge } from '@/components/ui/badge';
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
import admin from '@/routes/admin';
import { Head, router } from '@inertiajs/vue3';
import {
    BriefcaseBusiness,
    CircleAlert,
    ClipboardList,
    ScrollText,
    Sparkles,
    Users,
} from 'lucide-vue-next';

const props = defineProps<{
    dashboard: {
        viewer: 'admin' | 'staff';
        selected_grade: string | null;
        grade_options: string[];
        summary: {
            total_students: number;
            total_staff: number;
            total_exams: number;
            results_entered: number;
            pending_results: number;
        };
        overview: Array<{
            grade: string;
            students: number;
            exams: number;
            results_entered: number;
            pending_results: number;
        }>;
    };
}>();

const updateGrade = (value: unknown) => {
    const grade = value == null || value === '__all__' ? undefined : String(value);

    router.get(admin.dashboard({
        query: {
            grade,
        },
    }), undefined, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Dashboard', href: admin.dashboard.url() }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero
                    eyebrow="Operations Overview"
                    :title="dashboard.viewer === 'admin' ? 'Admin Dashboard' : 'Staff Dashboard'"
                    description="Track grade-level workload, result-entry progress, and daily academic operations from one focused management dashboard."
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <Sparkles class="size-3.5" />
                            Grade-aware snapshot
                        </Badge>
                    </template>
                </PageHero>

                <Card class="section-card">
                    <CardHeader>
                        <CardTitle>Grade Filter</CardTitle>
                        <CardDescription>
                            Switch between all grades or focus this dashboard on a single grade.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="max-w-xl space-y-2">
                        <Label for="dashboard-grade-filter">Grade</Label>
                        <Select
                            :model-value="props.dashboard.selected_grade ?? '__all__'"
                            @update:model-value="updateGrade"
                        >
                            <SelectTrigger id="dashboard-grade-filter" class="w-full">
                                <SelectValue placeholder="All grades" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="__all__">All grades</SelectItem>
                                <SelectItem v-for="grade in props.dashboard.grade_options" :key="grade" :value="grade">
                                    {{ grade }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </CardContent>
                </Card>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                    <StatCard
                        label="Total Students"
                        :value="props.dashboard.summary.total_students"
                        hint="Active student records included in the current dashboard scope."
                        :icon="Users"
                    />
                    <StatCard
                        v-if="props.dashboard.viewer === 'admin'"
                        label="Total Staff"
                        :value="props.dashboard.summary.total_staff"
                        hint="Staff accounts currently available to support operations."
                        :icon="BriefcaseBusiness"
                    />
                    <StatCard
                        v-else
                        label="Results Entered"
                        :value="props.dashboard.summary.results_entered"
                        hint="Marks already saved for the selected dashboard scope."
                        :icon="ClipboardList"
                        tone="success"
                    />
                    <StatCard
                        label="Total Exams"
                        :value="props.dashboard.summary.total_exams"
                        hint="Exams currently affecting the selected grade view."
                        :icon="ScrollText"
                    />
                    <StatCard
                        label="Pending Results"
                        :value="props.dashboard.summary.pending_results"
                        hint="Expected result entries that still need attention."
                        :icon="CircleAlert"
                        tone="warning"
                    />
                </div>

                <Card class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <CardTitle>Grade-wise Overview</CardTitle>
                                <CardDescription>
                                    Compare workload and result-entry progress across each available grade.
                                </CardDescription>
                            </div>
                            <Badge variant="outline" class="rounded-full px-4 py-1.5">
                                {{ props.dashboard.overview.length }} grade view(s)
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-secondary/70">
                                    <TableHead>Grade</TableHead>
                                    <TableHead>Students</TableHead>
                                    <TableHead>Exams</TableHead>
                                    <TableHead>Results Entered</TableHead>
                                    <TableHead>Pending Results</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="props.dashboard.overview.length">
                                    <TableRow v-for="row in props.dashboard.overview" :key="row.grade">
                                        <TableCell class="font-semibold text-foreground">
                                            {{ row.grade }}
                                        </TableCell>
                                        <TableCell>{{ row.students }}</TableCell>
                                        <TableCell>{{ row.exams }}</TableCell>
                                        <TableCell>{{ row.results_entered }}</TableCell>
                                        <TableCell>{{ row.pending_results }}</TableCell>
                                    </TableRow>
                                </template>
                                <TableEmpty v-else :colspan="5" class="py-14">
                                    No grade-linked students or exams are available yet.
                                </TableEmpty>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
