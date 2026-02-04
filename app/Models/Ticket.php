<?php

namespace App\Models;

use App\Enums\Ticket\TicketStatus;
use App\Traits\HasUuid;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory, HasUuid, LogsActivity;

    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_number',
        'qr_code',
        'status',
        'price_paid',
        'payment_id',
        'used_at',
        'issued_at',
        'expires_at',
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
        'used_at' => 'datetime',
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'status' => TicketStatus::class,
    ];

    /**
     * Boot del modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = self::generateTicketNumber();
            }
        });
    }

    // ========== RELACIONES ==========

    /**
     * Evento del ticket
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Usuario dueño del ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Pago asociado al ticket
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    // ========== SCOPES ==========

    /**
     * Scope para tickets activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', TicketStatus::ACTIVE);
    }

    /**
     * Scope para tickets usados
     */
    public function scopeUsed($query)
    {
        return $query->where('status', TicketStatus::USED);
    }

    /**
     * Scope para tickets válidos (activos y no expirados)
     */
    public function scopeValid($query)
    {
        return $query->where('status', TicketStatus::ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    // ========== HELPERS ==========

    /**
     * Generar número único de ticket
     */
    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-'.strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
        } while (self::where('ticket_number', $number)->exists());

        return $number;
    }

    /**
     * Verificar si el ticket es válido
     */
    public function isValid(): bool
    {
        if ($this->status !== TicketStatus::ACTIVE) {
            return false;
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Marcar ticket como usado
     */
    public function markAsUsed(): void
    {
        $this->update([
            'status' => TicketStatus::USED,
            'used_at' => now(),
        ]);
    }

    /**
     * Cancelar ticket
     */
    public function cancel(): void
    {
        $this->update(['status' => TicketStatus::CANCELLED]);
    }

    /**
     * Obtener URL del código QR
     */
    public function getQrCodeUrlAttribute(): string
    {
        if ($this->qr_code) {
            // Si es una ruta, devolver la URL completa
            if (filter_var($this->qr_code, FILTER_VALIDATE_URL)) {
                return $this->qr_code;
            }

            // Si es base64, devolverlo tal cual
            return $this->qr_code;
        }

        return '';
    }
}
