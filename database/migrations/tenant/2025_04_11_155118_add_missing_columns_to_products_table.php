<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add missing columns
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->string('dimensions')->nullable();
            $table->string('image')->nullable();
            $table->uuid('store_id')->nullable();
            $table->boolean('track_inventory')->default(false);
            
            // Add the new status column
            $table->string('status')->default('active');
        });
        
        // Set all products to active status by default
        DB::table('products')->update(['status' => 'active']);
        
        // We don't need to migrate from is_active because this looks like a new schema
        // where is_active might not exist yet. But we'll check and drop it if it does.
        if (Schema::hasColumn('products', 'is_active')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, add back the is_active column if we need to
        if (!Schema::hasColumn('products', 'is_active')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('price');
            });
            
            // Set is_active based on status
            DB::table('products')
                ->update(['is_active' => DB::raw("CASE WHEN status = 'active' THEN 1 ELSE 0 END")]);
        }
        
        // Remove added columns
        Schema::table('products', function (Blueprint $table) {
            // Check and drop status if it exists
            if (Schema::hasColumn('products', 'status')) {
                $table->dropColumn('status');
            }
            
            // Drop other columns that were added
            $table->dropColumn([
                'sale_price',
                'weight',
                'dimensions',
                'image',
                'store_id',
                'track_inventory'
            ]);
        });
    }
};
