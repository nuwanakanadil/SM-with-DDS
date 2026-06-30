export interface ShipmentInquiryOriginDestination {
    id?: number | string | null;
    coo_name?: string | null;
    coo?: string | null;
    place_of_origin?: string | null;
    aol?: string | null;
    pol?: string | null;
}

export interface ShipmentInquiryResource {
    origins_and_destinations?: ShipmentInquiryOriginDestination[] | null;
}
