<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Correr las migraciones.
     */
    public function up(): void
    {
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();

            // Relación con el perfil (Si se borra el perfil, se borran sus contactos)
            $table->foreignId('profile_id')->constrained()->cascadeOnDelete();

            // Datos del contacto
            $table->string('name');
            $table->string('relationship');
            $table->string('phone_number'); // Ej: +584141234567
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reversa las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
    }
};
