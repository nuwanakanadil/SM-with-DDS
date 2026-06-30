<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
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
import StudentLayout from '@/layouts/student/Layout.vue';
import type { AssessmentResult } from '@/types/result';
import { Head } from '@inertiajs/vue3';
import { BadgeCheck, FileSpreadsheet, Medal } from 'lucide-vue-next';

defineProps<{ results: AssessmentResult[] }>();
</script>

<template>
    <Head title="My Results" />
    <AppLayout :breadcrumbs="[{ title: 'Student' }, { title: 'My Results' }]">
        <StudentLayout>
            <div class="space-y-6">
                <PageHero
                    :eyebrow="APP_SYSTEM_SUBTITLE"
                    title="My Published Results"
                    :description="`A professional record of the assessment results released to your student account in ${APP_BRAND_NAME}.`"
                >
                    <template #meta>
                        <Badge variant="outline" class="rounded-full px-4 py-1.5">
                            <BadgeCheck class="size-3.5" />
                            {{ results.length }} result(s)
                        </Badge>
                    </template>
                </PageHero>

                <div class="grid gap-5 md:grid-cols-2">
                    <StatCard
                        label="Published Results"
                        :value="results.length"
                        hint="Results currently visible on your account."
                        :icon="Medal"
                    />
                    <StatCard
                        label="Result History"
                        :value="results.length"
                        hint="Every published record shown in one clean history view."
                        :icon="FileSpreadsheet"
                    />
                </div>

                <Card class="section-card overflow-hidden py-0">
                    <CardHeader class="border-b border-border/80 py-5">
                        <CardTitle>Result History</CardTitle>
                        <CardDescription>
                            Published results available only to your logged-in student profile.
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="p-0">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-secondary/70">
                                    <TableHead>Assessment</TableHead>
                                    <TableHead>Class</TableHead>
                                    <TableHead>Marks</TableHead>
                                    <TableHead>Total</TableHead>
                                    <TableHead>Remarks</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <template v-if="results.length">
                                    <TableRow v-for="result in results" :key="result.id" class="hover:bg-secondary/40">
                                        <TableCell class="font-semibold text-foreground">
                                            {{ result.assessment?.title }}
                                        </TableCell>
                                        <TableCell>{{ result.assessment?.class_name || '-' }}</TableCell>
                                        <TableCell>{{ result.marks }}</TableCell>
                                        <TableCell>{{ result.assessment?.total_marks }}</TableCell>
                                        <TableCell>{{ result.remarks || '-' }}</TableCell>
                                    </TableRow>
                                </template>

                                <TableEmpty v-else :colspan="5" class="py-14">
                                    No published results are available for your account in {{ APP_BRAND_NAME }} yet.
                                </TableEmpty>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </StudentLayout>
    </AppLayout>
</template>
