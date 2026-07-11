export interface Student {
    id: number;
    admission_no: string;
    first_name: string;
    last_name?: string | null;
    school?: string | null;
    email?: string | null;
    phone?: string | null;
    class_name?: string | null;
    is_active: boolean;
}
