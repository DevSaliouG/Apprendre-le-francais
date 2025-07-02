<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lesson_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->integer('score'); // Score en pourcentage
            $table->timestamp('completed_at');
            $table->timestamps();
            
            // Index composite pour éviter les doublons
            $table->unique(['user_id', 'lesson_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_completions');
    }
};