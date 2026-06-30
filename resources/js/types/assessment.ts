export interface Assessment {
    id: number;
    title: string;
    class_name?: string | null;
    assessment_date?: string | null;
    total_marks: number;
    is_published: boolean;
}
