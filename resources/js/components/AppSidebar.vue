<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { dashboard } from '@/routes';
import admin from '@/routes/admin';
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
    GraduationCap,
    LayoutDashboard,
    Medal,
    ScrollText,
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
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: admin.dashboard.url(),
            icon: LayoutDashboard,
        },
        {
            title: 'Analytics',
            href: admin.analysis.url(),
            icon: BarChart3,
        },
        {
            title: 'Students',
            href: admin.students.index.url(),
            icon: Users,
        },
        {
            title: 'Exams',
            href: admin.assessments.index.url(),
            icon: ScrollText,
        },
        {
            title: 'Results',
            href: admin.results.index.url(),
            icon: ClipboardList,
        },
    ];

    if (userPermissions.value.includes('manage_staff')) {
        items.push({
            title: 'Staff',
            href: '/admin/staff',
            icon: BriefcaseBusiness,
        });
    }

    return items;
});

const studentItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard.url(),
        icon: GraduationCap,
    },
    {
        title: 'My Results',
        href: student.results.url(),
        icon: GraduationCap,
    },
    {
        title: 'My Placement',
        href: student.placement.url(),
        icon: Medal,
    },
];

const mainNavItems = computed(() => (isAdminArea.value ? adminItems.value : studentItems));
const logoUrl = computed(() => (isAdminArea.value ? admin.dashboard.url() : dashboard.url()));
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="app-sidebar-theme">
        <SidebarHeader class="border-b border-sidebar-border px-3.5 py-3">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child class="h-auto rounded-lg px-0 py-0 hover:bg-transparent">
                        <Link :href="logoUrl" class="flex items-center">
                            <AppLogo />
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
