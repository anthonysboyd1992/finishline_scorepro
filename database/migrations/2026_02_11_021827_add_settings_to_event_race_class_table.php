<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_race_class', function (Blueprint $table) {
            $table->string('pill_mode')->nullable();
            $table->integer('pill_max')->nullable();
            $table->integer('pill_min')->nullable();
            $table->string('transponder_mode')->nullable();
            $table->integer('competitors_per_row')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('event_race_class', function (Blueprint $table) {
            $table->dropColumn(['pill_mode', 'pill_max', 'pill_min', 'transponder_mode', 'competitors_per_row']);
        });
    }
};
