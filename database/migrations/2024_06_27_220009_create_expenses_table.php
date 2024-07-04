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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Finance\ExpenseType::class)->constrained()->restrictOnDelete();
            $table->string('title')->nullable();
            $table->decimal('amount',64,0);
            $table->foreignIdFor(\App\Models\Settings\Currency::class)->constrained()->restrictOnDelete();
            $table->decimal('currency_rate',64,4)->default(0);
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
