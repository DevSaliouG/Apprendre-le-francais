<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des leçons associées aux niveaux
     */
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            
            // Clé étrangère vers la table levels
            $table->foreignId('level_id')
                  ->constrained() // Version simplifiée de references()->on()
                  ->cascadeOnDelete(); // Supprime les leçons si le niveau est supprimé
            
            $table->string('title', 255); // Titre de la leçon avec limite de longueur
            $table->text('content'); // Contenu détaillé de la leçon

            $table->timestamps(); // created_at et updated_at
            
            // Index pour optimiser les requêtes par niveau
            $table->index('level_id');
        });
    }

    /**
     * Supprime la table des leçons
     */
    public function down(): void
    {
        // Supprime d'abord la contrainte de clé étrangère
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
        });
        
        Schema::dropIfExists('lessons');
    }
};