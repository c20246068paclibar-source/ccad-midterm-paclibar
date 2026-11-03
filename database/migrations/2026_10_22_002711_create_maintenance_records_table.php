
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
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();

            // Reference to the equipment asset
            $table->foreignId('film_asset_id')
                ->constrained('film_assets')
                ->restrictOnDelete();

            // Reference to the related dispatch (optional)
            $table->foreignId('asset_dispatch_id')
                ->nullable()
                ->constrained('asset_dispatches')
                ->nullOnDelete();

            // Maintenance information
            $table->text('description');

            $table->timestamp('reported_at');

            $table->timestamp('resolved_at')->nullable();

            // Created and updated timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
