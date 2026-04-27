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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();

            // Relación opcional con el usuario (nullable por si quieres permitir valoraciones anónimas)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Sistema de calificación de 1 a 5
            $table->tinyInteger('rating')->unsigned();

            // Comentarios o sugerencias libres
            $table->text('comments')->nullable();

            // Respuestas a encuestas dinámicas guardadas como JSON (muy flexible)
            $table->json('survey_responses')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
