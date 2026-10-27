<?php

namespace App\Enums;

enum EmployeeStatus: string
{
    case AVAILABLE = 'available';
    case NOT_AVAILABLE = 'not_available';
}
