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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')       // Links to companies table
                  ->constrained()                  // Adds foreign key constraint
                  ->cascadeOnDelete();             // Delete orders when company deleted
            $table->decimal('amount', 10, 2);      // Order value (up to 99,999,999.99)
            $table->string('status')               // pending | paid | failed
                  ->default('pending');
            $table->text('notes')->nullable();      // Optional notes
            $table->date('order_date');             // When the order was placed
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
