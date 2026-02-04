<?php

namespace App\Models;

use App\Enums\Payment\PaymentCurrencySymbol;
use App\Enums\Payment\PaymentMethod;
use App\Enums\Payment\PaymentStatus;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    protected $fillable = [
        'user_id',
        'event_id',
        'payment_method',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'payment_date',
        'refund_date',
        'refund_reason',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'refund_date' => 'datetime',
        'metadata' => 'array',
        'status' => PaymentStatus::class,
    ];

    // ========== RELACIONES ==========

    /**
     * Usuario que realizó el pago
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Evento del pago
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Tickets generados por este pago
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope para pagos completados
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', PaymentStatus::COMPLETED);
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', PaymentStatus::PENDING);
    }

    /**
     * Scope para pagos fallidos
     */
    public function scopeFailed($query)
    {
        return $query->where('status', PaymentStatus::FAILED);
    }

    /**
     * Scope para pagos reembolsados
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', PaymentStatus::REFUNDED);
    }

    /**
     * Scope para filtrar por método de pago
     */
    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    // ========== HELPERS ==========

    /**
     * Verificar si el pago está completado
     */
    public function isCompleted(): bool
    {
        return $this->status === PaymentStatus::COMPLETED;
    }

    /**
     * Verificar si el pago está pendiente
     */
    public function isPending(): bool
    {
        return $this->status === PaymentStatus::PENDING;
    }

    /**
     * Verificar si el pago fue reembolsado
     */
    public function isRefunded(): bool
    {
        return $this->status === PaymentStatus::REFUNDED;
    }

    /**
     * Marcar pago como completado
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => PaymentStatus::COMPLETED,
            'payment_date' => now(),
        ]);
    }

    /**
     * Marcar pago como fallido
     */
    public function markAsFailed(): void
    {
        $this->update(['status' => PaymentStatus::FAILED]);
    }

    /**
     * Procesar reembolso
     */
    public function refund(string|null $reason = null): void
    {
        $this->update([
            'status' => PaymentStatus::REFUNDED,
            'refund_date' => now(),
            'refund_reason' => $reason,
        ]);
    }

    /**
     * Obtener nombre legible del método de pago
     */
    public function getPaymentMethodNameAttribute(): string
    {
        return match($this->payment_method) {
            'stripe' => PaymentMethod::STRIPE->label(),
            'paypal' => PaymentMethod::PAYPAL->label(),
            'mercadopago' => PaymentMethod::MERCADOPAGO->label(),
            'cash' => PaymentMethod::CASH->label(),
            'bank_transfer' => PaymentMethod::BANK_TRANSFER->label(),
            'credit_card' => PaymentMethod::CREDIT_CARD->label(),
            'debit_card' => PaymentMethod::DEBIT_CARD->label(),
            'other' => PaymentMethod::OTHER->label(),
        };
    }

    /**
     * Obtener símbolo de moneda
     */
    public function getCurrencySymbolAttribute(): string
    {
        return match($this->currency) {
            'USD' => PaymentCurrencySymbol::USD->symbol(),
            'EUR' => PaymentCurrencySymbol::EUR->symbol(),
            'COP' => PaymentCurrencySymbol::COP->symbol(),
            'MXN' => PaymentCurrencySymbol::MXN->symbol(),
            default => $this->currency,
        };
    }

    /**
     * Obtener monto formateado
     */
    public function getFormattedAmountAttribute(): string
    {
        return $this->currency_symbol . number_format($this->amount, 2);
    }
}
