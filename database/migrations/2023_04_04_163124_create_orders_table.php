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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade');

            $table->string('inn')->nullable();
            $table->string('org_name')->nullable();
            $table->string('phone');
            $table->string('name');
            $table->string('email');
            $table->date('end_date')->nullable();

            $table->foreignId('status_id')
                ->default(1)
                ->constrained()
                ->onUpdate('cascade');

            $table->foreignId('geo_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade');

            $table->text('user_comment')->nullable();
            $table->text('admin_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
