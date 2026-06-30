import type { ShipmentInquiryResource } from '@/types/inquiry';

export type BulkOriginTableRow = {
    id: number | string;
    countryOfOrigin: string;
    placeOfOrigin: string;
    originLocation: string;
};

export const formatBulkOriginTableRows = (
    originsAndDestinations: ShipmentInquiryResource['origins_and_destinations'] | null | undefined,
): BulkOriginTableRow[] => {
    return (originsAndDestinations ?? []).map((origin, index) => ({
        id: origin.id ?? `origin-${index}`,
        countryOfOrigin: origin.coo_name ?? origin.coo ?? 'N/A',
        placeOfOrigin: origin.place_of_origin || '-',
        originLocation: origin.aol || origin.pol || '-',
    }));
};
