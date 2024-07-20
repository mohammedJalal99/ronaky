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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth')->nullable();
            $table->string('position')->nullable();
            $table->date('hire_date');
            $table->date('termination_date')->nullable();
            $table->foreignIdFor(\App\Models\Settings\Currency::class)->constrained()->restrictOnDelete();
            $table->decimal('salary',64,4)->default(0);
            $table->string('certification')->nullable();
            $table->string('note')->nullable();
            $table->string('image')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
