<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_race_class', function (Blueprint $table) {
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('race_class_id')->constrained()->cascadeOnDelete();
            $table->primary(['event_id', 'race_class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_race_class');
    }
};
