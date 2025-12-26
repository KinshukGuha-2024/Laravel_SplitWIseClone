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
        Schema::create('group_user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('expense_id')->constrained('group_expenses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('repayment_id')->constrained('repayments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('amount');
            $table->string('title');
            $table->string('description');
            $table->enum('type', ['owe', 'lent', 'repayment', 'settlement', 'not involved']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user_activities');
    }
};
