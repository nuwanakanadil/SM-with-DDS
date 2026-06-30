<?php

namespace App\Enums;

enum Permissions: string
{
    case ViewDashboard = 'view_dashboard';
    case ViewStudents = 'view_students';
    case ManageStudents = 'manage_students';
    case ViewAssessments = 'view_assessments';
    case ManageAssessments = 'manage_assessments';
    case ViewResults = 'view_results';
    case ManageResults = 'manage_results';
    case ViewAnalysis = 'view_analysis';
    case ManageStaff = 'manage_staff';
    case ViewOwnResults = 'view_own_results';
    case ViewOwnPlacement = 'view_own_placement';
}
