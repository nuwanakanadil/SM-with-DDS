export interface Assessment {
    id: number;
    title: string;
    class_name?: string | null;
    class_group?: string | null;
    academic_year?: number | null;
    term?: string | null;
    exam_type?: string | null;
    assessment_date?: string | null;
    total_marks: number;
    is_published: boolean;
}
