<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import ThemeIconToggle from '@/components/ThemeIconToggle.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { APP_BRAND_NAME } from '@/constants/branding';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { AlertCircle, Award, BookOpenCheck, LayoutDashboard, LoaderCircle, LogIn, LogOut, PhoneCall, Search, Trophy, UserCircle } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { login, logout } from '@/routes';
import admin from '@/routes/admin';

type PublicResult = {
    row_key: string;
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

const backgroundFormulas = [
    'x^2 + y^2 = r^2',
    'y = mx + c',
    'a^2 + b^2 = c^2',
    'f(x) = 2x + 5',
    'sin A / cos A',
    'avg = total / n',
    'rank = 1',
    'pi = 3.14',
    'sqrt 81 = 9',
    'P(A|B)',
    '3 : 1',
    '75 / 100',
    'x = 14',
    'sum n',
];

const page = usePage();
const admissionNo = ref('');
const loading = ref(false);
const error = ref('');
const searched = ref(false);
const payload = ref<PublicResultResponse | null>(null);

const hasResults = computed(() => Boolean(payload.value?.student && payload.value.results.length));
const user = computed(() => page.props.auth.user);
const roles = computed(() => page.props.auth.roles ?? []);
const isAdminOrStaff = computed(() => roles.value.some((role) => ['admin', 'staff'].includes(role)));

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

        if (response.status === 422) {
            throw new Error('Enter a valid admission number.');
        }

        if (response.status === 429) {
            throw new Error('Too many result searches. Please wait a minute and try again.');
        }

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

    <main class="relative min-h-screen overflow-hidden bg-background text-foreground">
        <div class="results-math-background" aria-hidden="true">
            <div class="results-math-background__grid"></div>
            <div class="results-math-background__axis results-math-background__axis--one"></div>
            <div class="results-math-background__axis results-math-background__axis--two"></div>
            <div class="results-math-background__ring results-math-background__ring--one"></div>
            <div class="results-math-background__ring results-math-background__ring--two"></div>
            <div class="results-math-background__triangle"></div>
            <div class="results-math-background__parabola">
                <svg viewBox="0 0 420 180" fill="none" preserveAspectRatio="none">
                    <path d="M8 162C82 68 142 22 210 22C278 22 338 68 412 162" pathLength="1" />
                </svg>
            </div>
            <div class="results-math-background__wave">
                <svg viewBox="0 0 520 160" fill="none" preserveAspectRatio="none">
                    <path d="M0 80C32 80 32 28 65 28C98 28 98 132 130 132C162 132 162 28 195 28C228 28 228 132 260 132C292 132 292 28 325 28C358 28 358 132 390 132C422 132 422 28 455 28C488 28 488 80 520 80" pathLength="1" />
                </svg>
            </div>
            <span
                v-for="(formula, index) in backgroundFormulas"
                :key="formula"
                class="results-math-background__formula"
                :style="{ '--formula-index': index }"
            >
                {{ formula }}
            </span>
        </div>

        <div class="fixed right-3 top-3 z-50 sm:right-5 sm:top-5">
            <div class="inline-flex items-center gap-2 rounded-xl border border-border bg-card/88 p-1 shadow-sm backdrop-blur-md dark:bg-card/78">
                <ThemeIconToggle />

                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button
                            type="button"
                            variant="ghost"
                            size="icon-sm"
                            class="rounded-lg"
                            aria-label="Account menu"
                            title="Account menu"
                        >
                            <UserCircle class="size-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end" class="w-56 rounded-lg">
                        <DropdownMenuItem v-if="!user" :as-child="true">
                            <Link :href="login.url()" class="w-full cursor-pointer">
                                <LogIn class="mr-2 size-4" />
                                Staff/Admin Login
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem v-else-if="isAdminOrStaff" :as-child="true">
                            <Link :href="admin.dashboard.url()" class="w-full cursor-pointer">
                                <LayoutDashboard class="mr-2 size-4" />
                                Admin Dashboard
                            </Link>
                        </DropdownMenuItem>
                        <DropdownMenuItem v-else :as-child="true">
                            <Link :href="logout.url()" method="post" as="button" class="w-full cursor-pointer">
                                <LogOut class="mr-2 size-4" />
                                Logout for Staff/Admin Login
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>

        <div class="relative z-10 border-b border-border bg-secondary/35 backdrop-blur-[1px]">
            <div
                class="mx-auto flex w-full max-w-6xl flex-col px-4 py-5 sm:px-6 lg:px-8"
                :class="hasResults ? 'min-h-[58vh]' : 'min-h-screen'"
            >
                <section class="mx-auto flex w-full max-w-3xl flex-1 flex-col justify-center py-8 sm:py-12">
                    <div class="student-dashboard-surface relative overflow-hidden rounded-[0.875rem] border border-primary/10 bg-[linear-gradient(135deg,rgba(255,255,255,0.78),rgba(219,234,254,0.62))] shadow-sm backdrop-blur-sm dark:border-white/8 dark:bg-[linear-gradient(135deg,rgba(15,23,42,0.78),rgba(30,41,59,0.62))]">
                        <div class="relative z-10 border-b border-border bg-card/82 px-5 py-6 backdrop-blur-sm sm:px-8 sm:py-8">
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

                        <div class="relative z-10 bg-background/78 px-5 py-5 backdrop-blur-sm sm:px-8 sm:py-6">
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

                            <div class="mt-4 flex flex-col gap-3 rounded-lg border border-primary/15 bg-secondary/55 px-4 py-3 text-sm leading-6 text-muted-foreground sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                                        <PhoneCall class="size-4" />
                                    </span>
                                    <p class="font-semibold">
                                        විභාග අංකය අමතක ද? එසේනම් මෙම අංකය අමතන්න
                                    </p>
                                </div>
                                <a
                                    href="tel:0712378372"
                                    class="inline-flex items-center justify-center rounded-lg border border-primary/20 bg-background px-3 py-2 font-bold text-primary transition hover:bg-primary hover:text-primary-foreground"
                                >
                                    0712378372
                                </a>
                            </div>

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
                        class="student-dashboard-surface relative mt-6 overflow-hidden rounded-[0.875rem] border border-border bg-card/90 px-5 py-8 text-center text-sm font-medium text-muted-foreground shadow-sm backdrop-blur-sm"
                    >
                        <div class="relative z-10">
                            <LoaderCircle class="mx-auto mb-3 size-6 animate-spin text-primary" />
                            Loading results...
                        </div>
                    </section>

                    <section
                        v-else-if="searched && !hasResults && !error"
                        class="student-dashboard-surface relative mt-6 overflow-hidden rounded-[0.875rem] border border-dashed border-border bg-card/90 px-5 py-8 text-center shadow-sm backdrop-blur-sm"
                    >
                        <div class="relative z-10">
                            <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-secondary text-primary">
                                <Search class="size-5" />
                            </div>
                            <h2 class="mt-4 text-lg font-semibold tracking-normal text-foreground">No results found</h2>
                            <p class="mt-1 text-sm text-muted-foreground">
                                Check the admission number and try again.
                            </p>
                        </div>
                    </section>
                </section>
            </div>
        </div>

        <section v-if="hasResults" class="relative z-10 bg-background/70 px-4 py-8 backdrop-blur-[1px] sm:px-6 lg:px-8">
            <div class="mx-auto w-full max-w-6xl space-y-6">
                <div class="student-dashboard-surface relative overflow-hidden rounded-[0.875rem] border border-border bg-card/92 p-5 shadow-sm backdrop-blur-sm sm:p-6">
                    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
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
                    <div class="student-dashboard-surface relative overflow-hidden rounded-[0.875rem] border border-border bg-card/92 p-5 shadow-sm backdrop-blur-sm">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-muted-foreground">Total</p>
                                <BookOpenCheck class="size-5 text-primary" />
                            </div>
                            <p class="mt-3 text-3xl font-bold tracking-normal">{{ formatNumber(payload?.summary?.total) }}</p>
                        </div>
                    </div>
                    <div class="student-dashboard-surface relative overflow-hidden rounded-[0.875rem] border border-border bg-card/92 p-5 shadow-sm backdrop-blur-sm">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-muted-foreground">Average</p>
                                <Award class="size-5 text-primary" />
                            </div>
                            <p class="mt-3 text-3xl font-bold tracking-normal">{{ formatNumber(payload?.summary?.average) }}</p>
                        </div>
                    </div>
                    <div class="student-dashboard-surface relative overflow-hidden rounded-[0.875rem] border border-border bg-card/92 p-5 shadow-sm backdrop-blur-sm">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-muted-foreground">Rank</p>
                                <Trophy class="size-5 text-primary" />
                            </div>
                            <p class="mt-3 text-3xl font-bold tracking-normal">{{ payload?.summary?.rank ? `#${payload.summary.rank}` : '-' }}</p>
                        </div>
                    </div>
                </div>

                <div
                    v-for="result in payload?.results"
                    :key="result.row_key"
                    class="student-dashboard-surface relative overflow-hidden rounded-[0.875rem] border border-border bg-card/92 shadow-sm backdrop-blur-sm"
                >
                    <div class="relative z-10 flex flex-col gap-4 border-b border-border bg-secondary/70 p-5 backdrop-blur-sm sm:flex-row sm:items-start sm:justify-between">
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

                    <div class="relative z-10 grid gap-3 bg-card/80 p-5 backdrop-blur-sm sm:hidden">
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

                    <div class="relative z-10 hidden overflow-x-auto bg-card/82 backdrop-blur-sm sm:block">
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
