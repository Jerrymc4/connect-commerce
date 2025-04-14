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
        // Drop the existing table if it exists
        Schema::dropIfExists('theme_settings');
        
        // Recreate the table with key-value structure
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Setting key (e.g., primary_color, button_bg_color)
            $table->text('value')->nullable(); // Setting value
            $table->timestamps();
        });
        
        // Insert default settings
        $defaultSettings = [
            ['key' => 'primary_color', 'value' => '#3B82F6'],
            ['key' => 'button_bg_color', 'value' => '#3B82F6'],
            ['key' => 'button_text_color', 'value' => '#FFFFFF'],
            ['key' => 'footer_bg_color', 'value' => '#1F2937'],
            ['key' => 'navbar_text_color', 'value' => '#111827'],
            ['key' => 'cart_badge_bg_color', 'value' => '#EF4444'],
            ['key' => 'banner_image', 'value' => null],
            ['key' => 'banner_text', 'value' => null],
            ['key' => 'logo_url', 'value' => null],
            ['key' => 'body_bg_color', 'value' => '#F9FAFB'],
            ['key' => 'font_family', 'value' => 'Inter'],
            ['key' => 'link_color', 'value' => '#2563EB'],
            ['key' => 'card_bg_color', 'value' => '#FFFFFF'],
            ['key' => 'border_radius', 'value' => '6'],
        ];
        
        foreach ($defaultSettings as $setting) {
            DB::table('theme_settings')->insert([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
