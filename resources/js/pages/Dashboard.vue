<script setup lang="ts">
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import PageHero from '@/components/PageHero.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ArrowRight, LayoutDashboard, ShieldCheck, Sparkles } from 'lucide-vue-next';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold tracking-[-0.03em] text-foreground">
                {{ APP_BRAND_NAME }}
            </h2>
        </template>

        <div class="space-y-8">
            <PageHero
                :eyebrow="APP_SYSTEM_SUBTITLE"
                title="Dashboard overview"
                description="Use the navigation to manage students, assessments, results, and rankings across Super Maths with Damith."
            />

            <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
                <Card class="section-card">
                    <CardHeader>
                        <CardDescription class="flex items-center gap-2 text-muted-foreground">
                            <LayoutDashboard class="size-4 text-primary" />
                            Portal status
                        </CardDescription>
                        <CardTitle class="text-3xl">Everything is connected</CardTitle>
                    </CardHeader>
                    <CardContent class="grid gap-4 text-[15px] leading-7 text-muted-foreground md:grid-cols-2">
                        <div class="info-tile">
                            Use the admin area to maintain records, publish assessments, and keep ranking data accurate across {{ APP_BRAND_NAME }}.
                        </div>
                        <div class="info-tile">
                            Student pages provide a clearer view of marks, latest results, and placement updates inside the {{ APP_SYSTEM_SUBTITLE.toLowerCase() }}.
                        </div>
                    </CardContent>
                </Card>

                <Card class="section-card bg-[linear-gradient(135deg,rgba(37,99,235,0.08),rgba(255,255,255,0.96))]">
                    <CardHeader>
                        <CardDescription class="flex items-center gap-2 text-muted-foreground">
                            <ShieldCheck class="size-4 text-primary" />
                            Quick orientation
                        </CardDescription>
                        <CardTitle>Next best actions</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4 text-[15px] leading-7 text-muted-foreground">
                        <div class="flex items-start gap-3 rounded-[1.35rem] border border-border/70 bg-white/80 px-4 py-4">
                            <Sparkles class="mt-0.5 size-4 text-primary" />
                            Review the latest screens and confirm the calmer layout feels right for your workflow.
                        </div>
                        <div class="flex items-start gap-3 rounded-[1.35rem] border border-border/70 bg-white/80 px-4 py-4">
                            <ArrowRight class="mt-0.5 size-4 text-primary" />
                            Continue into the modules from the sidebar to manage live academic data.
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
