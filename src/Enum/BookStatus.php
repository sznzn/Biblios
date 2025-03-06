<?php

namespace App\Enum;

enum BookStatus: string
{
    case AVAILABLE = 'available';
    case BORROWED = 'borrowed';
    case UNAVAILABLE = 'unavailable';
    
    public function getLabel(): string
    {
        return match($this) {
            self::AVAILABLE => 'Disponible',
            self::BORROWED => 'Emprunté',
            self::UNAVAILABLE => 'Indisponible',
        };
    }
}
