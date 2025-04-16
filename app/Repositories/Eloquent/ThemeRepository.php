<?php

namespace App\Repositories\Eloquent;

use App\Models\ThemeSettings;
use App\Repositories\Interfaces\ThemeRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ThemeRepository implements ThemeRepositoryInterface
{
    protected ThemeSettings $model;
    protected string $cacheKey;

    public function __construct(ThemeSettings $model)
    {
        $this->model = $model;
        $this->cacheKey = 'theme_settings_' . (tenant()?->id ?? 'default');
    }

    public function getThemeSettings(): array
    {
        try {
            $cached = cache($this->cacheKey);
            if ($cached) {
                return $cached;
            }
        } catch (\Throwable $e) {
            Log::warning('Theme cache failed', ['error' => $e->getMessage()]);
        }

        $settings = $this->model->all()->pluck('value', 'key')->toArray();

        foreach ($settings as $key => $value) {
            if (is_string($value) && $this->isJson($value)) {
                $settings[$key] = json_decode($value, true);
            }
        }

        $merged = array_merge($this->getDefaultThemeSettings(), $settings);

        try {
            cache([$this->cacheKey => $merged], now()->addMinutes(60));
        } catch (\Throwable $e) {
            Log::warning('Unable to cache theme settings', ['error' => $e->getMessage()]);
        }

        return $merged;
    }

    public function getThemeSetting(string $key, $default = null): mixed
    {
        $settings = $this->getThemeSettings();
        return $settings[$key] ?? $default;
    }

    public function updateThemeSettings(array $settings): bool
    {
        Log::info('Updating theme settings', ['settings' => $settings]);

        foreach ($settings as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }

            $this->model->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->clearCache();

        return true;
    }

    public function clearCache(): void
    {
        try {
            Log::info('Clearing theme settings cache', ['key' => $this->cacheKey]);
            cache()->forget($this->cacheKey);
        } catch (\Throwable $e) {
            Log::error('Failed to clear cache', ['error' => $e->getMessage()]);
        }
    }

    private function getDefaultThemeSettings(): array
    {
        return [
            'primary_color' => '#3B82F6',
            'button_bg_color' => '#3B82F6',
            'button_text_color' => '#FFFFFF',
            'footer_bg_color' => '#1F2937',
            'navbar_text_color' => '#111827',
            'cart_badge_bg_color' => '#EF4444',
            'banner_image' => null,
            'banner_text' => null,
            'logo_url' => null,
            'body_bg_color' => '#F9FAFB',
            'font_family' => 'Inter, sans-serif',
            'link_color' => '#2563EB',
            'card_bg_color' => '#FFFFFF',
            'border_radius' => '0.375rem',
            'show_product_price' => true,
            'show_product_rating' => true,
            'show_product_description' => true,
            'show_facebook' => true,
            'show_twitter' => true,
            'show_instagram' => true,
            'show_youtube' => true,
            'show_pinterest' => true,
            'social_icon_style' => 'minimal',
            'social_icon_size' => 'medium',
            'mobile_menu_style' => 'slide',
            'product_card_style' => 'default',
            'product_image_size' => 'medium',
            'product_image_hover' => 'zoom',
            'menu_style' => 'default',
            'menu_item_spacing' => 'medium',
        ];
    }

    private function isJson($string): bool
    {
        if (!is_string($string)) return false;

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    /**
     * @inheritDoc
     */
    // public function uploadThemeAsset($file, string $type): string|null {
    // }
}
