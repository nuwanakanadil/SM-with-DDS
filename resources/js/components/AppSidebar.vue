<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { APP_BRAND_NAME, APP_SYSTEM_SUBTITLE } from '@/constants/branding';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import admin from '@/routes/admin';
import publicRoutes from '@/routes/public';
import student from '@/routes/student';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BarChart3,
    BriefcaseBusiness,
    ClipboardList,
    LayoutDashboard,
    Search,
    Medal,
    ScrollText,
    ShieldAlert,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const userRoles = computed(() => page.props.auth.roles ?? []);
const userPermissions = computed(() => page.props.auth.permissions ?? []);

const isAdminArea = computed(() => {
    if (page.url.startsWith('/admin')) {
        return true;
    }

    return userRoles.value.some((role) => ['admin', 'staff'].includes(role));
});

const adminItems = computed<NavItem[]>(() => {
    const items: (NavItem & { permission?: string })[] = [
        {
            title: 'Dashboard',
            href: admin.dashboard.url(),
            icon: LayoutDashboard,
            permission: 'view_dashboard',
        },
        {
            title: 'Analytics',
            href: admin.analysis.url(),
            icon: BarChart3,
            permission: 'view_analysis',
        },
        {
            title: 'Students',
            href: admin.students.index.url(),
            icon: Users,
            permission: 'view_students',
        },
        {
            title: 'Data Quality',
            href: '/admin/data-quality',
            icon: ShieldAlert,
            permission: 'view_analysis',
        },
        {
            title: 'Exams',
            href: admin.assessments.index.url(),
            icon: ScrollText,
            permission: 'view_assessments',
        },
        {
            title: 'Results',
            href: admin.results.index.url(),
            icon: ClipboardList,
            permission: 'view_results',
        },
    ];

    if (userPermissions.value.includes('manage_staff')) {
        items.push({
            title: 'Staff',
            href: '/admin/staff',
            icon: BriefcaseBusiness,
        });
    }

    return items.filter((item) => !item.permission || userPermissions.value.includes(item.permission));
});

const studentItems = computed<NavItem[]>(() => {
    if (userRoles.value.includes('student')) {
        return [
            { title: 'Dashboard', href: '/dashboard', icon: LayoutDashboard },
            { title: 'My Results', href: student.results.url(), icon: ClipboardList },
            { title: 'My Placement', href: student.placement.url(), icon: Medal },
            { title: 'Public Lookup', href: publicRoutes.results.url(), icon: Search },
        ];
    }

    return [{ title: 'Check Results', href: publicRoutes.results.url(), icon: Search }];
});

const mainNavItems = computed(() => (isAdminArea.value ? adminItems.value : studentItems.value));
const logoUrl = computed(() => (isAdminArea.value ? admin.dashboard.url() : publicRoutes.results.url()));
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="app-sidebar-theme">
        <SidebarHeader class="border-b border-sidebar-border px-3.5 py-3">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="h-auto rounded-lg px-0 py-0 hover:bg-transparent">
                        <Link :href="logoUrl" class="flex items-center gap-2.5">
                            <AppLogo />
                            <div class="grid flex-1 text-left leading-tight">
                                <span class="truncate text-[13px] font-semibold text-sidebar-foreground">
                                    {{ APP_BRAND_NAME }}
                                </span>
                                <span class="truncate text-[11px] text-sidebar-foreground/55">
                                    {{ APP_SYSTEM_SUBTITLE }}
                                </span>
                            </div>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="px-2.5 py-3">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="border-t border-sidebar-border px-2.5 py-3">
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>
