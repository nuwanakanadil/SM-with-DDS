export const prefixWithCdn = (path: string) => {
    const cdnUrl = import.meta.env.VITE_APP_CDN_URL;
    if (!cdnUrl?.includes('http')) {
        return prefixWithPublicUrl(path);
    }
    return path.includes('http') ? path : `${cdnUrl}/${path}`;
};

export const prefixWithPublicUrl = (path: string) => {
    const appUrl = import.meta.env.VITE_APP_URL;
    if (!appUrl?.includes('http')) {
        return path;
    }
    return path.includes('http') ? path : `${appUrl}/storage/${path}`;
};
