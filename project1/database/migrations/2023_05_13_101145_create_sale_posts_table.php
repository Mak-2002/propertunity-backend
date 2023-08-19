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
        Schema::create('sale_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('view_plan_id')->nullable();
            $table->foreignId('property_id');
            //$table->string('property_type');
           // $table->unsignedBigInteger('property_id');
          //  $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->double('price');
            $table->boolean('visibility')->default(true);
            $table->boolean('approval')->nullable();
            $table->text('rejection_purpose')->nullable();
           // $table->unsignedBigInteger('postable_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_posts');
    }
};
