
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
        Schema::create('asset_dispatches', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('production_id')
                ->constrained('productions')
                ->restrictOnDelete();

            $table->foreignId('film_asset_id')
                ->constrained('film_assets')
                ->restrictOnDelete();

            $table->foreignId('technician_id')
                ->constrained('technicians')
                ->restrictOnDelete();

            // Dispatch dates
            $table->date('checkout_date');

            $table->date('expected_return');

            $table->date('actual_return')->nullable();

            // Rental calculations
            $table->unsignedInteger('rental_days')->default(0);

            $table->decimal('total_rental_fee', 12, 2)->default(0);

            // Created and updated timestamps
            $table->timestamps();

            // Index for searching asset dispatch records
            $table->index(['film_asset_id', 'checkout_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_dispatches');
    }
};
