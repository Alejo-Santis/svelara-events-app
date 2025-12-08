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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable()->comment('Categoría del log: event, user, payment, etc.');
            $table->text('description')->comment('Descripción de la acción');

            // Usuario que realizó la acción
            $table->foreignId('causer_id')->nullable()->constrained('users')->onDelete('set null')->comment('Usuario que realizó la acción');
            $table->string('causer_type')->nullable()->comment('Tipo de usuario (User)');

            // Entidad afectada (polimórfica)
            $table->nullableMorphs('subject', 'activity_logs_subject_index');

            // Tipo de acción
            $table->string('event')->comment('created, updated, deleted, viewed, etc.');

            // Datos adicionales
            $table->json('properties')->nullable()->comment('Datos adicionales: cambios anteriores, nuevos valores, metadata');

            // Información de contexto
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable()->comment('GET, POST, PUT, DELETE');

            // Batch ID para agrupar acciones relacionadas
            $table->uuid('batch_uuid')->nullable()->comment('Para agrupar múltiples logs de una misma operación');

            $table->timestamps();

            // Índices para búsquedas eficientes
            $table->index('log_name');
            $table->index('causer_id');
            $table->index('event');
            $table->index('batch_uuid');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
