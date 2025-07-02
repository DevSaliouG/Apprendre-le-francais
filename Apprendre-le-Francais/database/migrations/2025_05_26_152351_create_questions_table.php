<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des questions associées aux exercices
     */
    public function up(): void
    {
       Schema::create('questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
    $table->text('texte');
    $table->json('choix')->nullable();
    $table->string('reponse_correcte', 255);
    $table->string('fichier_audio', 2048)->nullable();
    $table->enum('format_reponse', ['choix_multiple', 'texte_libre', 'audio'])->default('choix_multiple');
    $table->timestamps();
    $table->index('exercise_id');
});
    }

    /**
     * Supprime la table des questions de manière sécurisée
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Supprime d'abord la contrainte de clé étrangère
            $table->dropForeign(['exercise_id']);
        });

        Schema::dropIfExists('questions');
    }
};