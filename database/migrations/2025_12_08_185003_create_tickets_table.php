<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ticket_number', 50)->unique()->comment('Número único de ticket');
            $table->text('qr_code')->comment('Código QR en base64 o ruta');
            $table->enum('status', ['active', 'used', 'cancelled', 'refunded'])->default('active');
            $table->decimal('price_paid', 10, 2)->default(0.00);
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamp('used_at')->nullable();
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Índices
            $table->index('event_id');
            $table->index('user_id');
            $table->index('payment_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
