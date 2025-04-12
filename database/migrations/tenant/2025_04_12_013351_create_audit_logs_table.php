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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('store_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_type')->nullable();
            $table->string('event'); // 'created', 'updated', 'deleted', 'restored', etc.
            $table->string('auditable_type'); // The model class being audited
            $table->string('auditable_id'); // The ID of the record being audited
            $table->text('old_values')->nullable(); // JSON of old values before change
            $table->text('new_values')->nullable(); // JSON of new values after change
            $table->text('url')->nullable(); // URL where the action was performed
            $table->string('ip_address')->nullable(); // IP address of the user
            $table->string('user_agent')->nullable(); // Browser user agent
            $table->text('tags')->nullable(); // Optional JSON tags for filtering
            $table->timestamps();
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['event', 'auditable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
