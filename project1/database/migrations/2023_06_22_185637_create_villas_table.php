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
        Schema::create('villas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name')->nullable();
            ///location
            $table->text('address');
            $table->text('about');
            $table->string('360_view')->nullable();
            $table->string('image_library');
            $table->integer('room_count');
            $table->integer('bathroom_count');
            $table->integer('kitchen_count');
            $table->integer('storey');
            $table->integer('area');
            $table->integer('balkony')->nullable();
            $table->boolean('gym')->nullable();
            $table->boolean('pool')->nullable();
            $table->boolean('parking')->nullable();
            $table->boolean('security_cameras')->nullable();
            $table->boolean('Wi-Fi')->nullable();
            $table->boolean('security_gard')->nullable();
            $table->boolean('garden')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villas');
    }
};
