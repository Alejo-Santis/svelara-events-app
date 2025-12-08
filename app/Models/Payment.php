<?php

namespace App\Models;

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
        return $query->where('status', 'completed');
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para pagos fallidos
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope para pagos reembolsados
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
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
        return $this->status === 'completed';
    }

    /**
     * Verificar si el pago está pendiente
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verificar si el pago fue reembolsado
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Marcar pago como completado
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'payment_date' => now(),
        ]);
    }

    /**
     * Marcar pago como fallido
     */
    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    /**
     * Procesar reembolso
     */
    public function refund(string $reason = null): void
    {
        $this->update([
            'status' => 'refunded',
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
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            'mercadopago' => 'MercadoPago',
            default => ucfirst($this->payment_method),
        };
    }

    /**
     * Obtener símbolo de moneda
     */
    public function getCurrencySymbolAttribute(): string
    {
        return match($this->currency) {
            'USD' => '$',
            'EUR' => '€',
            'COP' => '$',
            'MXN' => '$',
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
