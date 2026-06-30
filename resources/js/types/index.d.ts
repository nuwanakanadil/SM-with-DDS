import type { Component } from 'vue';

export type Appearance = 'light' | 'dark' | 'system';
export type ResolvedAppearance = 'light' | 'dark';
export type AppShellVariant = 'header' | 'sidebar';

export interface User {
    id: number;
    name: string;
    first_name?: string | null;
    last_name?: string | null;
    avatar?: string | null;
    email: string;
    email_verified_at?: string | null;
}

export interface BreadcrumbItem {
    title: string;
    href?: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: Component;
    isActive?: boolean;
    items?: NavItem[];
}

export interface PaginatedLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from?: number | null;
    to?: number | null;
    links: PaginatedLink[];
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User | null;
        roles?: string[];
        permissions?: string[];
        isSuperAdmin?: boolean;
    };
    sidebarOpen?: boolean;
    flash: {
        success?: string | null;
        error?: string | null;
        warning?: string | null;
        info?: string | null;
    };
};
