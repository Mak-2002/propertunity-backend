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
        Schema::create('sale_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('post_id');
            $table->foreignId('payer_id')->references('id')->on('users');
            $table->foreignId('payee_id')->references('id')->on('users');
            $table->double('payment_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_contracts');
    }
};
