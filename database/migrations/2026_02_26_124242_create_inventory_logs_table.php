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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventory_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', [
                'stock_in',
                'stock_out',
                'sale',
                'return',
                'adjustment',
                'reserved',
                'released'
            ]);

            $table->integer('quantity');

            $table->integer('before_quantity');
            $table->integer('after_quantity');

            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
