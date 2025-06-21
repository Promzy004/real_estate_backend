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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('thumbnail_image');
            $table->string('property_type');
            $table->string('location');
            $table->decimal('price');
            $table->text('description');
            $table->integer('bed');
            $table->integer('room');
            $table->integer('bath');
            $table->string('square_meter');
            $table->enum('status', ['pending', 'declined', 'approved'])->default('pending');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('images');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
