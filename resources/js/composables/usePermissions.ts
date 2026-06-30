import { usePage } from '@inertiajs/vue3';

export function usePermissions() {
    const permissions = usePage().props.auth.permissions ?? [];
    const isSuperAdmin = usePage().props.auth.isSuperAdmin ?? false;

    const hasPermission = (permissionName: string): boolean => {
        if (isSuperAdmin) return true;
        if (!permissions) return false;
        return permissions.includes(permissionName);
    };

    return { hasPermission };
}
