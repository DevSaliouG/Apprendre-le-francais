<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des exercices associés aux leçons
     */
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
    $table->enum('type', ['écrit', 'oral', 'mixte'])->default('écrit');
    $table->integer('difficulty')->default(1); // 1-5
    $table->timestamps();
    $table->index('lesson_id');
});
    }

    /**
     * Supprime la table des exercices
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            // Supprime d'abord la contrainte de clé étrangère
            $table->dropForeign(['lesson_id']);
        });

        Schema::dropIfExists('exercises');
    }
};