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
        Schema::table('order_items', function (Blueprint $table) {
            // Add product_name column if it doesn't exist
            if (!Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->after('product_id');
            }
            
            // Add sku column if it doesn't exist
            if (!Schema::hasColumn('order_items', 'sku')) {
                $table->string('sku')->nullable()->after('product_name');
            }
            
            // Add options column if it doesn't exist
            if (!Schema::hasColumn('order_items', 'options')) {
                $table->json('options')->nullable()->after('quantity');
            }
            
            // Rename unit_price to price if unit_price exists and price doesn't
            if (Schema::hasColumn('order_items', 'unit_price') && !Schema::hasColumn('order_items', 'price')) {
                $table->renameColumn('unit_price', 'price');
            } else if (!Schema::hasColumn('order_items', 'price')) {
                $table->decimal('price', 10, 2)->after('sku');
            }
            
            // Remove total_price if it exists (we calculate this on the fly)
            if (Schema::hasColumn('order_items', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Define reverse operations
            if (Schema::hasColumn('order_items', 'product_name')) {
                $table->dropColumn('product_name');
            }
            
            if (Schema::hasColumn('order_items', 'sku')) {
                $table->dropColumn('sku');
            }
            
            if (Schema::hasColumn('order_items', 'options')) {
                $table->dropColumn('options');
            }
            
            // Restore original column names
            if (Schema::hasColumn('order_items', 'price') && !Schema::hasColumn('order_items', 'unit_price')) {
                $table->renameColumn('price', 'unit_price');
            }
            
            // Add back total_price
            if (!Schema::hasColumn('order_items', 'total_price')) {
                $table->decimal('total_price', 10, 2)->after('quantity');
            }
        });
    }
};
