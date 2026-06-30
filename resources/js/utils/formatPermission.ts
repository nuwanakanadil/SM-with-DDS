export const formatPermission = (permission: string | null | undefined) => {
    if (!permission) return '-';

    return permission
        .replaceAll('_', ' ')
        .replace(/\b\w/g, (char) => char.toUpperCase());
};