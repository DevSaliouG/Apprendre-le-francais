<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des résultats utilisateurs
     */
    public function up(): void
    {
        Schema::create('user_results', function (Blueprint $table) {
            $table->id();
            
            // Clé étrangère vers la table users
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            // Clé étrangère vers la table questions
            $table->foreignId('question_id')
                  ->constrained()
                  ->cascadeOnDelete();
            
            // Réponse donnée par l'utilisateur
            $table->text('reponse');
            
            // Indicateur de réponse correcte
            $table->boolean('correct')->default(false);
            
            // Type de test avec valeurs communes prédéfinies
            $table->enum('test_type', [
                'placement', 
                'evaluation',
                'pratique',
                'examen',
                 'speaking',  // Ajoutez cette valeur
                'writing'
            ])->default('pratique');
            
            $table->timestamps();
            
            // Index composites pour les performances
            $table->index(['user_id', 'question_id']);
            
            // Index supplémentaire pour les requêtes fréquentes
            $table->index('correct');
        });
    }

    /**
     * Supprime la table des résultats utilisateurs
     */
    public function down(): void
    {
        Schema::table('user_results', function (Blueprint $table) {
            // Suppression des contraintes de clé étrangère en premier
            $table->dropForeign(['user_id']);
            $table->dropForeign(['question_id']);
        });

        Schema::dropIfExists('user_results');
    }
};