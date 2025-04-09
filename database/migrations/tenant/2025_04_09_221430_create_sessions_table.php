<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // session ID
            $table->foreignId('user_id')->nullable()->index(); // optional user relation
            $table->string('ip_address', 45)->nullable(); // IPv6 safe
            $table->text('user_agent')->nullable(); // browser info
            $table->text('payload'); // actual session data (serialized)
            $table->integer('last_activity')->index(); // UNIX timestamp of activity
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};

