<?php

namespace App\Shared\Domain\Enum;

enum Roles: string
{
    case ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    case ROLE_USER = 'ROLE_USER';
}
