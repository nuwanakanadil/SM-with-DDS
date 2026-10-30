<script setup lang="ts">
import PageHero from '@/components/PageHero.vue';
import StatCard from '@/components/StatCard.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import AdminLayout from '@/layouts/admin/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Activity, ClipboardX, ShieldAlert, UserRoundCheck, UsersRound } from 'lucide-vue-next';

type ActivityRow = { id: number; description: string; event?: string | null; subject_type?: string | null; subject_id?: number | null; created_at: string; causer?: { name?: string | null; email?: string | null } | null };

defineProps<{
    summary: { active_students: number; unassigned_group: number; results_for_unassigned: number; draft_results: number };
    groupBreakdown: { grade: string; class_group: string; students: number }[];
    recentActivity: ActivityRow[];
}>();
</script>

<template>
    <Head title="Data Quality" />
    <AppLayout :breadcrumbs="[{ title: 'Admin' }, { title: 'Data Quality' }]">
        <AdminLayout>
            <div class="space-y-6">
                <PageHero eyebrow="System Health" title="Data Quality & Audit" description="Find records that can distort ranks or pending-result counts, then review recent administrative changes." />

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <StatCard label="Active Students" :value="summary.active_students" hint="Current active student records." :icon="UserRoundCheck" />
                    <StatCard label="Group Unassigned" :value="summary.unassigned_group" hint="Must be assigned before new marks entry." :icon="UsersRound" tone="warning" />
                    <StatCard label="Legacy Results Affected" :value="summary.results_for_unassigned" hint="Existing results belonging to unassigned students." :icon="ShieldAlert" tone="warning" />
                    <StatCard label="Results in Draft Exams" :value="summary.draft_results" hint="Saved but not publicly visible." :icon="ClipboardX" />
                </div>

                <Card v-if="summary.unassigned_group" class="section-card border-amber-300 bg-amber-50/60 dark:bg-amber-950/10">
                    <CardContent class="flex flex-col gap-4 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div><p class="font-semibold">Group assignment needs attention</p><p class="text-sm text-muted-foreground">Open the filtered student list, select records, and use the new bulk assignment control.</p></div>
                        <Button as-child><Link href="/admin/students?class_group=unassigned">Fix unassigned students</Link></Button>
                    </CardContent>
                </Card>

                <div class="grid gap-6 xl:grid-cols-2">
                    <Card class="section-card overflow-hidden py-0">
                        <CardHeader class="border-b py-5"><CardTitle>Grade and Group Distribution</CardTitle><CardDescription>Use this to spot missing or unexpectedly small groups.</CardDescription></CardHeader>
                        <CardContent class="max-h-[36rem] overflow-auto p-0">
                            <Table><TableHeader><TableRow class="bg-secondary/70"><TableHead>Grade</TableHead><TableHead>Group</TableHead><TableHead class="text-right">Students</TableHead></TableRow></TableHeader><TableBody><TableRow v-for="row in groupBreakdown" :key="`${row.grade}-${row.class_group}`"><TableCell class="font-semibold">{{ row.grade }}</TableCell><TableCell><Badge :variant="row.class_group.includes('Unassigned') ? 'destructive' : 'outline'">{{ row.class_group }}</Badge></TableCell><TableCell class="text-right">{{ row.students }}</TableCell></TableRow></TableBody></Table>
                        </CardContent>
                    </Card>

                    <Card class="section-card overflow-hidden py-0">
                        <CardHeader class="border-b py-5"><CardTitle class="flex items-center gap-2"><Activity class="size-4" />Recent Activity</CardTitle><CardDescription>Latest 25 recorded changes for troubleshooting and accountability.</CardDescription></CardHeader>
                        <CardContent class="max-h-[36rem] overflow-auto p-0">
                            <div v-for="entry in recentActivity" :key="entry.id" class="border-b p-4 last:border-0">
                                <div class="flex items-start justify-between gap-3"><p class="font-medium capitalize">{{ entry.description }}</p><Badge variant="outline">{{ entry.event ?? 'change' }}</Badge></div>
                                <p class="mt-1 text-xs text-muted-foreground">{{ entry.causer?.name ?? entry.causer?.email ?? 'System' }} · {{ new Date(entry.created_at).toLocaleString() }} · record #{{ entry.subject_id ?? '-' }}</p>
                            </div>
                            <p v-if="!recentActivity.length" class="p-8 text-center text-muted-foreground">No activity has been recorded yet.</p>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AdminLayout>
    </AppLayout>
</template>
