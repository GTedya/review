<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_dealer_vehicles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_dealer_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('vehicle_type_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade');

            $table->string('vehicle_brand')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->integer('vehicle_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_dealer_vehicles');
    }
};
