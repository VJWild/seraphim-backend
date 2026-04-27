<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Correr migraciones
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // Relación con el usuario (Si se borra el usuario, se borra el perfil)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Datos de Identidad y Físicos
            $table->string('id_number')->unique();
            $table->string('full_name');
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F', 'Otro']);
            $table->decimal('weight', 5, 2)->nullable(); // Ej: 120.50 kg
            $table->decimal('height', 3, 2)->nullable(); // Ej: 1.85 m

            // Datos Médicos (Vitales)
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->text('allergies')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->boolean('organ_donor')->default(false);
            $table->string('health_insurance')->nullable();

            // Geolocalización y Sistema
            $table->string('address_text');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Slug único para el código QR
            $table->string('qr_slug')->unique();

            $table->timestamps();
        });
    }

    /**
     * Reversa las migraciones
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
