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
        Schema::table('learning_streaks', function (Blueprint $table) {
        $table->timestamp('last_reminder_sent_at')->nullable();
        $table->boolean('reminder_sent')->default(false);
        $table->boolean('break_risk_alerted')->default(false);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_streaks', function (Blueprint $table) {
            //
        });
    }
};
