<?php

namespace App\Enums;

/**
 * 撮影枠（午前・午後）
 */
enum Slot: string
{
    case Morning = 'am';
    case Afternoon = 'pm';

    public function label(): string
    {
        return match ($this) {
            self::Morning => '午前',
            self::Afternoon => '午後',
        };
    }
}
