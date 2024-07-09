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
        Schema::create('car_names', function (Blueprint $table) {
            $table->unsignedBigInteger('car_id');
            $table->string('seat_name');
            $table->boolean('isBooked')->default(false);

            // Khóa ngoại đến bảng cars
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');

            // Khóa ngoại đến bảng seats
            $table->foreign('seat_name')->references('name')->on('seats')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_names');
    }
};
