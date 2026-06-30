export const CONTACT_PERSON_IDS_QUERY_PARAM = 'contact_person_ids[]';

export const buildInquiryPreviewUrl = (
    baseUrl: string,
    contactPersonIds: readonly string[],
): string => {
    const query = new URLSearchParams();

    contactPersonIds.forEach((contactPersonId) => {
        query.append(CONTACT_PERSON_IDS_QUERY_PARAM, contactPersonId);
    });

    const queryString = query.toString();

    if (!queryString) {
        return baseUrl;
    }

    const separator = baseUrl.includes('?') ? '&' : '?';

    return `${baseUrl}${separator}${queryString}`;
};
