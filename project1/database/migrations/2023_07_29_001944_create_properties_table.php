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
            $table->foreignId('user_id');
            $table->string('name')->nullable();

            ///location
            $table->double('latitude');
            $table->double('longitude');

            $table->text('address');
            $table->text('about');
            $table->string('360_view')->nullable();
            $table->integer('area');
            $table->morphs('category'); 

            $table->integer('image_count')->default(0);
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
