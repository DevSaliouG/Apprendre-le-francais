<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des niveaux de compétence
     */
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
    $table->id();
    $table->string('code', 10)->unique();
    $table->string('name', 50);
    $table->text('description')->nullable(); // Nouveau: description du niveau
    $table->string('color', 20)->default('gray'); // Pour l'UI
    $table->timestamps();
});
    }

    /**
     * Supprime la table des niveaux
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};