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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->integer('room_count');
            $table->integer('bathroom_count');
            $table->integer('kitchen_count');
            $table->integer('storey');
            $table->integer('balkony')->nullable();
            $table->boolean('pool')->nullable();
            $table->boolean('parking')->nullable();
            $table->boolean('security_cameras')->nullable();
            $table->boolean('Wi-Fi')->nullable();
            $table->boolean('garden')->nullable();
            //$table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
