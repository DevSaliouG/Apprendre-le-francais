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
         Schema::create('streak_milestones', function (Blueprint $table) {
        $table->id();
        $table->integer('days_required');
        $table->string('badge_name');
        $table->string('badge_icon')->default('fa-fire');
        $table->text('notification_message');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('streak_milestones');
    }
};
