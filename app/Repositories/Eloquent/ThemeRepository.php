<?php

namespace App\Repositories\Eloquent;

use App\Models\ThemeSettings;
use App\Repositories\Interfaces\ThemeRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ThemeRepository implements ThemeRepositoryInterface
{
    protected $model;
    protected $cacheKey;
    
    public function __construct(ThemeSettings $model)
    {
        $this->model = $model;
        // Handle case where tenant() might be null (like in tinker or commands)
        try {
            $tenantId = tenant() ? tenant()->id : 'default';
            $this->cacheKey = 'theme_settings_' . $tenantId;
        } catch (\Exception $e) {
            $this->cacheKey = 'theme_settings_default';
        }
    }
    
    /**
     * Get all theme settings.
     *
     * @return array
     */
    public function getThemeSettings()
    {
        try {
            // Use a simpler caching approach without tagging
            $cachedSettings = cache($this->cacheKey);
            
            if ($cachedSettings !== null) {
                return $cachedSettings;
            }
        } catch (\Exception $e) {
            // If caching fails for any reason, continue without it
        }
        
        // Fetch from database - assuming key-value structure
        $settings = $this->model->all()->pluck('value', 'key')->toArray();
        
        // Process JSON values
        foreach ($settings as $key => $value) {
            // Try to decode JSON values
            if (is_string($value) && $this->isJson($value)) {
                $settings[$key] = json_decode($value, true);
            }
        }
        
        // Merge with defaults
        $mergedSettings = array_merge($this->getDefaultThemeSettings(), $settings);
        
        // Try to store in cache, but don't fail if it doesn't work
        try {
            cache([$this->cacheKey => $mergedSettings], 3600);
        } catch (\Exception $e) {
            // Silent fail - we can work without the cache
        }
        
        return $mergedSettings;
    }
    
    /**
     * Get a specific theme setting by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getThemeSetting(string $key, $default = null)
    {
        $settings = $this->getThemeSettings();
        return $settings[$key] ?? $default;
    }
    
    /**
     * Update theme settings.
     *
     * @param array $settings
     * @return bool
     */
    public function updateThemeSettings(array $settings)
    {
        // Debug: Log the settings being updated
        Log::info('Updating theme settings', ['settings' => $settings]);
        
        // Since the database table seems to be using a key-value structure,
        // we need to handle it differently than with the column-based approach
        foreach ($settings as $key => $value) {
            // Convert arrays and objects to JSON strings for storage
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }
            
            // Debug: Log each key-value pair being saved
            Log::info('Saving theme setting', ['key' => $key, 'value' => $value]);
            
            // Update or create a setting record for each key-value pair
            $this->model->updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        // Invalidate cache - use the global helper to avoid tagging issues
        try {
            Log::info('Clearing cache for theme settings', ['cache_key' => $this->cacheKey]);
            cache()->forget($this->cacheKey);
        } catch (\Exception $e) {
            // Silent fail - we can work without the cache
            Log::error('Failed to clear cache', ['error' => $e->getMessage()]);
        }
        
        return true;
    }
    
    /**
     * Upload a theme asset (image/file).
     *
     * @param mixed $file
     * @param string $type
     * @return string|null Path to uploaded file
     */
    public function uploadThemeAsset($file, string $type)
    {
        if (!$file) {
            return null;
        }
        
        $path = Storage::disk('public')->put(
            "tenant-" . tenant()->id . "/theme/{$type}",
            $file
        );
        
        return $path;
    }
    
    /**
     * Get default theme settings.
     *
     * @return array
     */
    private function getDefaultThemeSettings()
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
        ];
    }
    
    /**
     * Check if a string is valid JSON.
     *
     * @param string $string
     * @return bool
     */
    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
} 