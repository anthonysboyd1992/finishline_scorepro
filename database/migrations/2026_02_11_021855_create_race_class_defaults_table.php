<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('race_class_defaults', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_class_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('pill_mode')->nullable();
            $table->integer('pill_max')->nullable();
            $table->integer('pill_min')->nullable();
            $table->string('transponder_mode')->nullable();
            $table->integer('competitors_per_row')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('race_class_defaults');
    }
};
