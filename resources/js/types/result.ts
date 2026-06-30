import type { Assessment } from './assessment';
import type { Student } from './student';

export interface AssessmentResult {
    id: number;
    assessment_id: number;
    student_id: number;
    marks: string | number;
    remarks?: string | null;
    assessment?: Assessment;
    student?: Student;
}

export interface ResultSummary {
    highest: number | null;
    lowest: number | null;
    average: number;
    students_count: number;
}

export interface StudentRank {
    rank: number;
    student_id: number;
    student_name: string;
    marks: string | number;
}
