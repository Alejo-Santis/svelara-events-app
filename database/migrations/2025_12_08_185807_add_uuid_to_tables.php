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
        // Agregar UUID a users
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->index('uuid');
        });

        // Agregar UUID a categories
        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->index('uuid');
        });

        // Agregar UUID a tags
        Schema::table('tags', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->index('uuid');
        });

        // Agregar UUID a events
        Schema::table('events', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->index('uuid');
        });

        // Agregar UUID a tickets
        Schema::table('tickets', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->index('uuid');
        });

        // Agregar UUID a payments
        Schema::table('payments', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->index('uuid');
        });

        // Agregar UUID a event_images
        Schema::table('event_images', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->index('uuid');
        });

        // Agregar UUID a event_invitations
        Schema::table('event_invitations', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('event_images', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('event_invitations', function (Blueprint $table) {
            $table->dropIndex(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
