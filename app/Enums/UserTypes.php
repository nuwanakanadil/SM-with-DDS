<?php

namespace App\Enums;

enum UserTypes: string
{
    case Admin = 'admin';
    case Staff = 'staff';
    case Student = 'student';
}
