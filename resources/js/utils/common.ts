export const isTrashed = (item: { deleted_at?: string | null } | undefined) => {
    if (!item) {
        return false;
    }
    return item?.deleted_at !== null;
};

export const inqStatusCheck = (status: string) => {
    if (status === 'Uncommitted') {
        return 'font-medium text-purple-600';
    } else if (status === 'Pending') {
        return 'font-medium text-orange-400';
    } else if (status === 'Finalized') {
        return 'font-medium text-green-600';
    } else if (status === 'Attended') {
        return 'font-medium text-blue-600';
    } else if (status === 'Cancelled') {
        return 'font-medium text-red-600';
    }
};
