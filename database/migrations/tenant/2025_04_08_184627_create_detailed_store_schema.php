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
      // Product categories
      Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->timestamps();
    });

    // Products
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->string('sku')->unique();
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
        $table->unsignedInteger('stock')->default(0);
        $table->boolean('is_active')->default(true);
        $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        $table->timestamps();
    });

    // Customers
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('phone')->nullable();
        $table->text('billing_address')->nullable();
        $table->text('shipping_address')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    // Orders
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled'])->default('pending');
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('tax', 10, 2)->default(0);
        $table->decimal('shipping', 10, 2)->default(0);
        $table->decimal('total', 10, 2)->default(0);
        $table->timestamps();
    });

    // Order Items
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->unsignedInteger('quantity');
        $table->decimal('unit_price', 10, 2);
        $table->decimal('total_price', 10, 2);
        $table->timestamps();
    });

    // Payments
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->string('payment_method'); // e.g. credit_card, paypal
        $table->decimal('amount', 10, 2);
        $table->string('transaction_id')->nullable();
        $table->string('status')->default('pending');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
