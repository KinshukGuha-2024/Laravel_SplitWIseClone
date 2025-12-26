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
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paid_by')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('paid_to')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('amount');
            $table->enum('type', ['repayment', 'settlement']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
