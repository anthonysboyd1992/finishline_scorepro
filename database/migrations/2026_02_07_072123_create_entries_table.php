<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('race_class_id')->constrained()->cascadeOnDelete();
            $table->string('car_number');
            $table->string('driver_name');
            $table->string('city')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'race_class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
