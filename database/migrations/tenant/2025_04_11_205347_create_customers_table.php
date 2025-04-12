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
        // First check if the customers table already exists
        if (Schema::hasTable('customers')) {
            // If it exists, alter it to match our schema if needed
            Schema::table('customers', function (Blueprint $table) {
                // Check and add columns that might be missing
                if (!Schema::hasColumn('customers', 'first_name')) {
                    // If first_name doesn't exist, we need to rename the existing columns or add new ones
                    // Check if there's a 'name' column to split
                    if (Schema::hasColumn('customers', 'name')) {
                        // We'll add the columns but leave the existing data as is
                        $table->string('first_name')->nullable()->after('id');
                        $table->string('last_name')->nullable()->after('first_name');
                    } else {
                        // Just add the columns
                        $table->string('first_name')->nullable()->after('id');
                        $table->string('last_name')->nullable()->after('first_name');
                    }
                }
                
                // Add other columns that might be missing
                if (!Schema::hasColumn('customers', 'password')) {
                    $table->string('password')->nullable()->after('email');
                }
                
                if (!Schema::hasColumn('customers', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'status')) {
                    $table->string('status')->default('active');
                }
                
                if (!Schema::hasColumn('customers', 'accepts_marketing')) {
                    $table->boolean('accepts_marketing')->default(false);
                }
                
                if (!Schema::hasColumn('customers', 'is_guest')) {
                    $table->boolean('is_guest')->default(false);
                }
                
                if (!Schema::hasColumn('customers', 'customer_group')) {
                    $table->string('customer_group')->default('default');
                }
                
                if (!Schema::hasColumn('customers', 'tax_exempt')) {
                    $table->string('tax_exempt')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'last_login_at')) {
                    $table->timestamp('last_login_at')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'preferences')) {
                    $table->json('preferences')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'currency')) {
                    $table->string('currency')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'locale')) {
                    $table->string('locale')->nullable();
                }
                
                if (!Schema::hasColumn('customers', 'remember_token')) {
                    $table->rememberToken();
                }
                
                if (!Schema::hasColumn('customers', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        } else {
            // If it doesn't exist, create the table
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('password')->nullable();
                $table->string('phone')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('status')->default('active');
                $table->boolean('accepts_marketing')->default(false);
                $table->boolean('is_guest')->default(false);
                $table->string('customer_group')->default('default');
                $table->string('tax_exempt')->nullable();
                $table->timestamp('last_login_at')->nullable();
                $table->json('preferences')->nullable();
                $table->string('currency')->nullable();
                $table->string('locale')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't drop the customers table if it already existed
        // but we can remove columns we added if needed
    }
};
