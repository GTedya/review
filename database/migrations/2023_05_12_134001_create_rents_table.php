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
        Schema::create('rents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade');

            $table->foreignId('geo_id')
                ->constrained()
                ->onUpdate('cascade');

            $table->boolean('is_published')->default(true);
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('type');
            $table->text('text');
            $table->timestamp('active_until');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
