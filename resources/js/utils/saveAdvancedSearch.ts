export const saveAdvancedSearch = (storageKey: string, currentUserId: string | number | undefined, cleanParams: object) => {

    if (!currentUserId) {
        return;
    }

    if (typeof window === 'undefined' || !window.localStorage) {
        return;
    }

    try {
        const cleanJsonString = JSON.stringify(cleanParams);

        //bind user id to key
        globalThis.localStorage.setItem(`${storageKey}_${currentUserId}`, cleanJsonString);
    } catch (_error) {
        // Ignore storage errors and JSON serialization issues
        console.log(_error);
    }
};

export const checkAdvancedSearchData = (storageKey: string, currentUserId: string | number | undefined, baseUrl: string) => {

    if (!currentUserId) {
        return baseUrl;
    }

    if (typeof window === 'undefined' || !window.localStorage) {
        return baseUrl;
    }

    try {
        const prefix = globalThis.localStorage.getItem(`${storageKey}_${currentUserId}`);

        if (prefix != null) {
            const filtersObject = JSON.parse(prefix);
            const queryParams = new URLSearchParams(
                filtersObject as Record<string, string>,
            ).toString();
            return `${baseUrl}?${queryParams}`;
        }
    } catch (_error) {
        // Ignore storage and JSON parsing errors, fall back to base URL
        console.log(_error);
    }

    return baseUrl;
};

export const clearAdvancedSearchData = (storageKey: string, currentUserId: string | number | undefined) => {

    if (!currentUserId) {
        return;
    }

    if (typeof window === 'undefined' || !window.localStorage) {
        return;
    }

    try {
        globalThis.localStorage.removeItem(`${storageKey}_${currentUserId}`);
    } catch (_error) {
        // Ignore storage errors on clear
        console.log(_error);
    }
};
