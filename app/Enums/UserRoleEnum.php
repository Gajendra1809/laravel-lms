<?php
namespace App\Enums;

/**
 * Class UserRoleEnum
 *
 * Enumeration class for user role values
 */
enum UserRoleEnum: String
{
    case SUPERADMIN = '1';
    case ADMIN = '2';
    case USER = '3';
}
