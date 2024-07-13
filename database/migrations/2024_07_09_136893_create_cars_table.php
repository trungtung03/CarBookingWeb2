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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('depature_location');
            $table->string('destination');
            $table->string('name');
            $table->string('license_plates');
            $table->string('image')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('type_name');

            // Khóa ngoại đến bảng car_types
            $table->foreign('type_name')->references('type_name')->on('car_types')->onDelete('cascade');

            $table->unsignedBigInteger('id_user');

            // Khóa ngoại đến bảng users
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
