<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { APP_BRAND_NAME } from '@/constants/branding';
import { Head } from '@inertiajs/vue3';
import { AlertCircle, Award, BookOpenCheck, LoaderCircle, Search, Trophy } from 'lucide-vue-next';
import { computed, ref } from 'vue';

type PublicResult = {
    id: number;
    exam_name: string | null;
    subject: string | null;
    class_name: string | null;
    assessment_date: string | null;
    marks: number;
    total_marks: number | null;
    grade: string | null;
    remarks: string | null;
    average: number | null;
    rank: number | null;
};

type PublicResultResponse = {
    student: {
        name: string;
        admission_no: string;
        class_name: string | null;
    } | null;
    results: PublicResult[];
    summary: {
        total: number;
        average: number | null;
        rank: number | null;
    } | null;
};

const props = defineProps<{ searchUrl: string }>();

const admissionNo = ref('');
const loading = ref(false);
const error = ref('');
const searched = ref(false);
const payload = ref<PublicResultResponse | null>(null);

const hasResults = computed(() => Boolean(payload.value?.student && payload.value.results.length));

const resultCountLabel = computed(() => {
    const count = payload.value?.results.length ?? 0;

    return `${count} result${count === 1 ? '' : 's'}`;
});

const formatNumber = (value: number | null | undefined) => {
    if (value === null || value === undefined) {
        return '-';
    }

    return Number.isInteger(value) ? String(value) : value.toFixed(2);
};

const formatMarks = (marks: number, totalMarks: number | null) => {
    if (!totalMarks) {
        return formatNumber(marks);
    }

    return `${formatNumber(marks)} / ${formatNumber(totalMarks)}`;
};

const formatDate = (value: string | null) => {
    if (!value) {
        return '-';
    }

    const [year, month, day] = value.split('-').map(Number);

    return new Intl.DateTimeFormat('en', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    }).format(new Date(year, month - 1, day));
};

const viewResults = async () => {
    const query = admissionNo.value.trim();

    if (!query) {
        error.value = 'Enter an admission number to view results.';
        payload.value = null;
        searched.value = false;
        return;
    }

    loading.value = true;
    error.value = '';
    searched.value = true;

    try {
        const response = await fetch(`${props.searchUrl}?admission_no=${encodeURIComponent(query)}`, {
            headers: {
                Accept: 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Unable to load results right now.');
        }

        payload.value = await response.json();
    } catch (exception) {
        payload.value = null;
        error.value = exception instanceof Error ? exception.message : 'Unable to load results right now.';
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Head title="Student Results" />

    <main class="min-h-screen bg-background text-foreground">
        <div class="border-b border-border bg-secondary/70">
            <div
                class="mx-auto flex w-full max-w-6xl flex-col px-4 py-5 sm:px-6 lg:px-8"
                :class="hasResults ? 'min-h-[58vh]' : 'min-h-screen'"
            >
                <section class="mx-auto flex w-full max-w-3xl flex-1 flex-col justify-center py-8 sm:py-12">
                    <div class="overflow-hidden rounded-[0.875rem] border border-border bg-card shadow-sm">
                        <div class="border-b border-border bg-card px-5 py-6 sm:px-8 sm:py-8">
                            <div class="flex flex-col items-center text-center">
                                <div class="flex size-20 items-center justify-center rounded-[0.875rem] border border-border bg-background p-3 shadow-sm">
                                    <ApplicationLogo class="h-full w-full" />
                                </div>
                                <p class="mt-4 text-sm font-semibold text-primary">{{ APP_BRAND_NAME }}</p>
                                <h1 class="mt-2 text-3xl font-bold tracking-normal text-foreground sm:text-4xl">
                                    Student Results
                                </h1>
                            </div>
                        </div>

                        <div class="bg-background/70 px-5 py-5 sm:px-8 sm:py-6">
                            <form class="grid gap-3 sm:grid-cols-[1fr_auto]" @submit.prevent="viewResults">
                                <Input
                                    v-model="admissionNo"
                                    aria-label="Admission Number"
                                    autocomplete="off"
                                    class="h-12 rounded-lg border-border bg-card text-base shadow-none"
                                    placeholder="Admission Number"
                                />
                                <Button type="submit" size="lg" class="h-12 rounded-lg px-5" :disabled="loading">
                                    <LoaderCircle v-if="loading" class="size-4 animate-spin" />
                                    <Search v-else class="size-4" />
                                    {{ loading ? 'Loading...' : 'View Results' }}
                                </Button>
                            </form>

                            <p
                                v-if="error"
                                class="mt-4 flex items-start gap-2 rounded-lg border border-destructive/30 bg-destructive/10 px-4 py-3 text-sm font-medium text-destructive"
                            >
                                <AlertCircle class="mt-0.5 size-4 shrink-0" />
                                <span>{{ error }}</span>
                            </p>
                        </div>
                    </div>

                    <section
                        v-if="loading"
                        class="mt-6 rounded-[0.875rem] border border-border bg-card px-5 py-8 text-center text-sm font-medium text-muted-foreground shadow-sm"
                    >
                        <LoaderCircle class="mx-auto mb-3 size-6 animate-spin text-primary" />
                        Loading results...
                    </section>

                    <section
                        v-else-if="searched && !hasResults && !error"
                        class="mt-6 rounded-[0.875rem] border border-dashed border-border bg-card px-5 py-8 text-center shadow-sm"
                    >
                        <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-secondary text-primary">
                            <Search class="size-5" />
                        </div>
                        <h2 class="mt-4 text-lg font-semibold tracking-normal text-foreground">No results found</h2>
                        <p class="mt-1 text-sm text-muted-foreground">
                            Check the admission number and try again.
                        </p>
                    </section>
                </section>
            </div>
        </div>

        <section v-if="hasResults" class="bg-background px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto w-full max-w-6xl space-y-6">
                <div class="rounded-[0.875rem] border border-border bg-card p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-primary">{{ resultCountLabel }}</p>
                            <h2 class="mt-1 text-2xl font-bold tracking-normal text-foreground">
                                {{ payload?.student?.name }}
                            </h2>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-lg border border-border bg-secondary/60 px-4 py-3">
                                <p class="text-xs font-semibold uppercase text-muted-foreground">Admission Number</p>
                                <p class="mt-1 font-semibold text-foreground">{{ payload?.student?.admission_no }}</p>
                            </div>
                            <div class="rounded-lg border border-border bg-secondary/60 px-4 py-3">
                                <p class="text-xs font-semibold uppercase text-muted-foreground">Class</p>
                                <p class="mt-1 font-semibold text-foreground">{{ payload?.student?.class_name || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-[0.875rem] border border-border bg-card p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-muted-foreground">Total</p>
                            <BookOpenCheck class="size-5 text-primary" />
                        </div>
                        <p class="mt-3 text-3xl font-bold tracking-normal">{{ formatNumber(payload?.summary?.total) }}</p>
                    </div>
                    <div class="rounded-[0.875rem] border border-border bg-card p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-muted-foreground">Average</p>
                            <Award class="size-5 text-primary" />
                        </div>
                        <p class="mt-3 text-3xl font-bold tracking-normal">{{ formatNumber(payload?.summary?.average) }}</p>
                    </div>
                    <div class="rounded-[0.875rem] border border-border bg-card p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-muted-foreground">Rank</p>
                            <Trophy class="size-5 text-primary" />
                        </div>
                        <p class="mt-3 text-3xl font-bold tracking-normal">{{ payload?.summary?.rank ? `#${payload.summary.rank}` : '-' }}</p>
                    </div>
                </div>

                <div
                    v-for="result in payload?.results"
                    :key="result.id"
                    class="overflow-hidden rounded-[0.875rem] border border-border bg-card shadow-sm"
                >
                    <div class="flex flex-col gap-4 border-b border-border bg-secondary/60 p-5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase text-muted-foreground">{{ formatDate(result.assessment_date) }}</p>
                            <h3 class="mt-1 text-xl font-bold tracking-normal text-foreground">{{ result.exam_name || 'Exam Result' }}</h3>
                            <p class="mt-1 text-sm text-muted-foreground">{{ result.class_name || payload?.student?.class_name || '-' }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full border border-primary/20 bg-background px-3 py-1 text-sm font-semibold text-primary">
                                Grade {{ result.grade || '-' }}
                            </span>
                            <span class="rounded-full border border-border bg-background px-3 py-1 text-sm font-semibold text-foreground">
                                Rank {{ result.rank ? `#${result.rank}` : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid gap-3 p-5 sm:hidden">
                        <div class="rounded-lg border border-border bg-background px-4 py-3">
                            <p class="text-xs font-semibold uppercase text-muted-foreground">Subject / Paper</p>
                            <p class="mt-1 font-semibold text-foreground">{{ result.subject || result.exam_name || '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-lg border border-border bg-background px-4 py-3">
                                <p class="text-xs font-semibold uppercase text-muted-foreground">Marks</p>
                                <p class="mt-1 font-semibold text-foreground">{{ formatMarks(result.marks, result.total_marks) }}</p>
                            </div>
                            <div class="rounded-lg border border-border bg-background px-4 py-3">
                                <p class="text-xs font-semibold uppercase text-muted-foreground">Average</p>
                                <p class="mt-1 font-semibold text-foreground">{{ formatNumber(result.average) }}</p>
                            </div>
                        </div>
                        <div v-if="result.remarks" class="rounded-lg border border-border bg-background px-4 py-3">
                            <p class="text-xs font-semibold uppercase text-muted-foreground">Remarks</p>
                            <p class="mt-1 text-sm font-medium text-foreground">{{ result.remarks }}</p>
                        </div>
                    </div>

                    <div class="hidden overflow-x-auto sm:block">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Subject / Paper</TableHead>
                                    <TableHead>Marks</TableHead>
                                    <TableHead>Grade</TableHead>
                                    <TableHead>Total</TableHead>
                                    <TableHead>Average</TableHead>
                                    <TableHead>Remarks</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow>
                                    <TableCell class="font-semibold text-foreground">{{ result.subject || result.exam_name || '-' }}</TableCell>
                                    <TableCell>{{ formatNumber(result.marks) }}</TableCell>
                                    <TableCell>{{ result.grade || '-' }}</TableCell>
                                    <TableCell>{{ formatNumber(result.total_marks) }}</TableCell>
                                    <TableCell>{{ formatNumber(result.average) }}</TableCell>
                                    <TableCell>{{ result.remarks || '-' }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>
