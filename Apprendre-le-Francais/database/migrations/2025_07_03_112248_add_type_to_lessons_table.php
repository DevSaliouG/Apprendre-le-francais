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
         Schema::table('lessons', function (Blueprint $table) {
            // Ajoute une colonne ENUM avec les types de leçons autorisés
            $table->enum('type', [
                'grammaire', 
                'orthographe', 
                'vocabulaire', 
                'conjugaison'
            ])->default('grammaire')->after('level_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('type');  // Rollback: suppression de la colonne
        });
    }
};
