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
        Schema::table('orders', function (Blueprint $table) {
            // Order identification
            $table->string('order_number')->unique()->after('id');
            
            // Status and payment information
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending')->after('customer_id');
            } else {
                $table->string('status')->default('pending')->change();
            }
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('shipping_method')->nullable()->after('payment_method');
            
            // Order amount columns
            if (Schema::hasColumn('orders', 'tax')) {
                $table->renameColumn('tax', 'tax_amount');
            } else {
                $table->decimal('tax_amount', 10, 2)->default(0)->after('subtotal');
            }
            if (Schema::hasColumn('orders', 'shipping')) {
                $table->renameColumn('shipping', 'shipping_amount');
            } else {
                $table->decimal('shipping_amount', 10, 2)->default(0)->after('tax_amount');
            }
            $table->decimal('discount_amount', 10, 2)->default(0)->after('shipping_amount');
            $table->text('notes')->nullable()->after('total');
            
            // Customer information
            $table->string('customer_name')->nullable()->after('notes');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            
            // Shipping address
            $table->string('shipping_address')->nullable()->after('customer_phone');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_state')->nullable()->after('shipping_city');
            $table->string('shipping_zip')->nullable()->after('shipping_state');
            $table->string('shipping_country')->nullable()->after('shipping_zip');
            
            // Billing address
            $table->string('billing_address')->nullable()->after('shipping_country');
            $table->string('billing_city')->nullable()->after('billing_address');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_zip')->nullable()->after('billing_state');
            $table->string('billing_country')->nullable()->after('billing_zip');
        });
        
        // Create order history table if it doesn't exist
        if (!Schema::hasTable('order_history')) {
            Schema::create('order_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->string('status');
                $table->text('comment')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_type')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove all added columns in reverse order
            $table->dropColumn([
                'order_number',
                'payment_status',
                'payment_method',
                'shipping_method',
                'discount_amount',
                'notes',
                'customer_name',
                'customer_email',
                'customer_phone',
                'shipping_address',
                'shipping_city',
                'shipping_state',
                'shipping_zip',
                'shipping_country',
                'billing_address',
                'billing_city',
                'billing_state',
                'billing_zip',
                'billing_country'
            ]);
            
            // Rename columns back to original names
            if (Schema::hasColumn('orders', 'tax_amount')) {
                $table->renameColumn('tax_amount', 'tax');
            }
            
            if (Schema::hasColumn('orders', 'shipping_amount')) {
                $table->renameColumn('shipping_amount', 'shipping');
            }
        });
        
        // Drop order history table
        Schema::dropIfExists('order_history');
    }
};
