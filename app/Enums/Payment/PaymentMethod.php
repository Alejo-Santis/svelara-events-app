<?php

namespace App\Enums\Payment;

enum PaymentMethod: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case MERCADOPAGO = 'mercadopago';
    case CASH = 'cash';
    case BANK_TRANSFER = 'bank_transfer';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case OTHER = 'other';

    public function label(): string
    {
        return match($this) {
            self::STRIPE => 'Stripe',
            self::PAYPAL => 'PayPal',
            self::MERCADOPAGO => 'MercadoPago',
            self::CASH => 'Efectivo',
            self::BANK_TRANSFER => 'Transferencia bancaria',
            self::CREDIT_CARD => 'Tarjeta de crédito',
            self::DEBIT_CARD => 'Tarjeta de débito',
            self::OTHER => 'Otro',
        };
    }
}
