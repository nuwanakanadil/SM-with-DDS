export const formatDate = (date: string | null | undefined) => {
    if (!date) return '-';

    return new Date(date).toLocaleString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

export const formatDateOnly = (date: string | null | undefined) => {
    if (!date) return '-';

    return new Date(date).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

export const toDateInputValue = (date: string | null | undefined) => {
    if (!date) return '';

    return new Date(date).toISOString().slice(0, 10);
};
