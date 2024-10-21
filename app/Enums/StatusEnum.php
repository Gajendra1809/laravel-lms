<?php
namespace App\Enums;

/**
 * Class StatusEnum
 *
 * Enumeration class for book status values
 */
enum StatusEnum: String
{
    case AVAILABLE = '1';
    case NOTAVAILABLE = '0';

    public function label()
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::NOTAVAILABLE => 'Not Available',
        };
    }
}
