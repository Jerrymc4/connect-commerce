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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('provider'); // stripe, paypal, etc.
            $table->string('payment_type'); // credit_card, bank_account, etc.
            $table->string('token_id')->nullable(); // For stored payment tokens
            $table->string('card_type')->nullable(); // visa, mastercard, etc.
            $table->string('last_four')->nullable(); // Last four digits
            $table->string('expiry_month')->nullable();
            $table->string('expiry_year')->nullable();
            $table->string('cardholder_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
}; 