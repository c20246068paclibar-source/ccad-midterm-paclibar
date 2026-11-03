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
        Schema::create('film_assets', function (Blueprint $table) {
            $table->id();

            $table->string('equipment_tag')->unique();

            $table->string('equipment_category');

            $table->decimal('daily_rate', 10, 2);

            $table->string('status')->default('Available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_assets');
    }
};