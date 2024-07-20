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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->foreignIdFor(\App\Models\Student\Parents::class)->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(\App\Models\Student\Driver::class)->nullable()->constrained()->restrictOnDelete();
            $table->foreignIdFor(\App\Models\Settings\Stage::class)->constrained()->restrictOnDelete();
            $table->date('birthdate');
            $table->enum('gender', ['male', 'female']);
            $table->text('address')->nullable();
            $table->date('start_date');
            $table->date('graduation_date')->nullable();
            $table->boolean('book')->default(0);
            $table->boolean('clothes')->default(0);
            $table->boolean('check')->default(0);
            $table->string('image')->nullable();
            $table->decimal('amount',64,4)->default(0);
            $table->integer('installments')->default(3);
            $table->decimal('per_month_amount',64,4)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
