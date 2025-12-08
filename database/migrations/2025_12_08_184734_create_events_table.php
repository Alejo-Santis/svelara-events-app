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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Creador del evento');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('short_description', 500)->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('timezone', 50)->default('America/Bogota');
            $table->string('location')->nullable()->comment('Dirección física');
            $table->string('venue_name')->nullable()->comment('Nombre del lugar');
            $table->boolean('is_online')->default(false);
            $table->string('online_url')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency', 3)->default('COP');
            $table->integer('capacity')->nullable()->comment('Capacidad máxima');
            $table->boolean('allow_waitlist')->default(false);
            $table->timestamp('registration_deadline')->nullable();
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->integer('views_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            // Índices
            $table->index('user_id');
            $table->index('category_id');
            $table->index('is_public');
            $table->index('is_published');
            $table->index('status');
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
