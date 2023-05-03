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
        Schema::create('order_leasing_vehicles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_leasing_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('type_id')
                ->constrained('vehicle_types')
                ->onUpdate('cascade');

            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('state')->nullable();
            $table->integer('count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_leasing_vehicles');
    }
};
