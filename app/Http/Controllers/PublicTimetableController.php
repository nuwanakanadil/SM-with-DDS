<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class PublicTimetableController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('student/Timetable');
    }
}
