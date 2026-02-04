<?php

namespace App\Enums\Payment;

enum PaymentCurrencySymbol: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case COP = 'COP';
    case MXN = 'MXN';

    public function symbol(): string
    {
        return match($this) {
            self::USD => '$',
            self::EUR => '€',
            self::COP => '$',
            self::MXN => '$',
        };
    }
}
