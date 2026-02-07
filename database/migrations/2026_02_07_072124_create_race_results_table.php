<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('race_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained()->cascadeOnDelete();
            $table->string('race_type');
            $table->integer('race_number')->default(1);
            $table->integer('laps_completed')->nullable();
            $table->decimal('best_lap_time', 8, 3)->nullable();
            $table->decimal('total_time', 8, 3)->nullable();
            $table->integer('finishing_position')->nullable();
            $table->integer('points')->default(0);
            $table->timestamps();

            $table->index(['entry_id', 'race_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('race_results');
    }
};
