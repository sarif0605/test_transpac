<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('nip')->unique()->primary();
            $table->string('name', 70)->nullable();
            $table->string('position', 50);
            $table->string('duty_place', 100);
            $table->string('religion', 20);
            $table->string('phone_number', 15);
            $table->string('npwp', 50);
            $table->string('email', 70);
            $table->string('photo', 255)->nullable();
            $table->foreignId('work_unit_id')->nullable()->constrained('work_units')->onDelete('cascade');
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
