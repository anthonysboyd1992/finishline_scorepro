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
        Schema::table('lineup_scripts', function (Blueprint $table) {
            $table->string('race_type')->default('Heat')->after('slug')->index();
        });

        // Drop the old unique on slug alone, add a composite unique on slug + race_type
        Schema::table('lineup_scripts', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['slug', 'race_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lineup_scripts', function (Blueprint $table) {
            $table->dropUnique(['slug', 'race_type']);
            $table->unique('slug');
            $table->dropColumn('race_type');
        });
    }
};
