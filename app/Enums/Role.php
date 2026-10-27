<?php

namespace App\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'super_admin';
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case WORKER = 'worker';
    case CUSTOMER = 'customer';
}
