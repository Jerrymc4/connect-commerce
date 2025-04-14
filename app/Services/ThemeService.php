<?php

namespace App\Services;

use App\Repositories\Interfaces\ThemeRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ThemeService
{
    protected $themeRepository;
    
    public function __construct(ThemeRepositoryInterface $themeRepository)
    {
        $this->themeRepository = $themeRepository;
    }
    
    /**
     * Get all theme settings.
     *
     * @return array
     */
    public function getThemeSettings()
    {
        return $this->themeRepository->getThemeSettings();
    }
    
    /**
     * Get a specific theme setting with fallback.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getThemeSetting(string $key, $default = null)
    {
        // Special case for cart badge color - fall back to primary color if not set
        if ($key === 'cart_badge_bg_color') {
            $value = $this->themeRepository->getThemeSetting($key);
            if (empty($value)) {
                return $this->themeRepository->getThemeSetting('primary_color', $default);
            }
        }
        
        return $this->themeRepository->getThemeSetting($key, $default);
    }
    
    /**
     * Update theme settings.
     *
     * @param array $settings
     * @param array $files
     * @return bool
     */
    public function updateThemeSettings(array $settings, array $files = [])
    {
        // Fix for custom CSS - ensure it's directly in the settings array
        if (isset($settings['custom_css'])) {
            // No additional processing needed, just make sure it gets passed through
        }
        
        // Flatten any nested arrays in the settings
        $flattenedSettings = [];
        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                // For array values like typography[base_size], flatten to typography_base_size
                foreach ($value as $subKey => $subValue) {
                    $flattenedSettings["{$key}_{$subKey}"] = $subValue;
                }
            } else {
                $flattenedSettings[$key] = $value;
            }
        }
        
        // Handle file uploads first
        foreach ($files as $type => $file) {
            if ($file) {
                $tenantId = tenant()->id;
                
                // Store files directly in the public storage path
                if ($type === 'logo') {
                    $filename = 'logo.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs("tenant-{$tenantId}/theme/logo", $filename, 'public');
                    $flattenedSettings['logo_url'] = $path;
                } elseif ($type === 'banner') {
                    $filename = 'banner.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs("tenant-{$tenantId}/theme/banner", $filename, 'public');
                    $flattenedSettings['banner_image'] = $path;
                }
            }
        }
        
        // Update settings
        $result = $this->themeRepository->updateThemeSettings($flattenedSettings);
        
        // Generate CSS variables
        $this->generateThemeCSS();
        
        return $result;
    }
    
    /**
     * Generate a CSS file with theme variables.
     *
     * @return void
     */
    public function generateThemeCSS()
    {
        $settings = $this->getThemeSettings();
        
        // Debug: Log the settings being used for CSS generation
        Log::info('Generating theme CSS with settings', ['settings' => $settings]);
        
        $css = ":root {\n";
        
        // Add CSS variables with direct values to ensure they're applied
        $css .= "  --color-primary: " . ($settings['primary_color'] ?? '#3B82F6') . ";\n";
        $css .= "  --color-button-bg: " . ($settings['button_bg_color'] ?? '#3B82F6') . ";\n";
        $css .= "  --color-button-text: " . ($settings['button_text_color'] ?? '#FFFFFF') . ";\n";
        $css .= "  --color-footer-bg: " . ($settings['footer_bg_color'] ?? '#1F2937') . ";\n";
        $css .= "  --color-navbar-text: " . ($settings['navbar_text_color'] ?? '#111827') . ";\n";
        $css .= "  --color-cart-badge: " . ($settings['cart_badge_bg_color'] ?? '#EF4444') . ";\n";
        $css .= "  --color-body-bg: " . ($settings['body_bg_color'] ?? '#F9FAFB') . ";\n";
        $css .= "  --color-link: " . ($settings['link_color'] ?? '#2563EB') . ";\n";
        $css .= "  --color-card-bg: " . ($settings['card_bg_color'] ?? '#FFFFFF') . ";\n";
        
        // Add font-family with quotes
        $fontFamily = $settings['font_family'] ?? 'Inter, sans-serif';
        if (strpos($fontFamily, ',') === false) {
            $fontFamily .= ', sans-serif';
        }
        $css .= "  --font-family: " . $fontFamily . ";\n";
        
        // Add border radius (ensure it has units)
        $borderRadius = $settings['border_radius'] ?? '0.375rem';
        if (is_numeric($borderRadius)) {
            $borderRadius .= 'px';
        }
        $css .= "  --border-radius: " . $borderRadius . ";\n";
        
        // Add font size if available
        if (isset($settings['typography_base_size'])) {
            $css .= "  --font-size-base: " . $settings['typography_base_size'] . ";\n";
        }
        
        $css .= "}\n\n";
        
        // Add more specific CSS rules
        $css .= "html, body {\n";
        $css .= "  background-color: var(--color-body-bg);\n";
        $css .= "  font-family: var(--font-family);\n";
        if (isset($settings['typography_base_size'])) {
            $css .= "  font-size: var(--font-size-base);\n";
        }
        $css .= "}\n\n";
        
        $css .= ".btn-primary {\n";
        $css .= "  background-color: var(--color-button-bg);\n";
        $css .= "  color: var(--color-button-text);\n";
        $css .= "}\n\n";
        
        $css .= "a {\n";
        $css .= "  color: var(--color-link);\n";
        $css .= "}\n\n";
        
        $css .= ".card {\n";
        $css .= "  background-color: var(--color-card-bg);\n";
        $css .= "  border-radius: var(--border-radius);\n";
        $css .= "}\n\n";
        
        $css .= "header .navbar {\n";
        $css .= "  color: var(--color-navbar-text);\n";
        $css .= "}\n\n";
        
        $css .= "footer {\n";
        $css .= "  background-color: var(--color-footer-bg);\n";
        $css .= "}\n\n";
        
        $css .= ".cart-count {\n";
        $css .= "  background-color: var(--color-cart-badge);\n";
        $css .= "}\n";
        
        // Add custom CSS if available
        if (isset($settings['custom_css']) && !empty($settings['custom_css'])) {
            $css .= "\n/* Custom CSS */\n";
            $css .= $settings['custom_css'] . "\n";
        }
        
        // Write directly to the filesystem using PHP's file functions
        // This bypasses Laravel's storage abstraction which might be causing issues
        $tenantId = tenant()->id;
        $cssDir = public_path("storage/tenant-{$tenantId}/theme");
        $cssFile = "{$cssDir}/theme.css";
        
        // Ensure the directory exists
        if (!is_dir($cssDir)) {
            mkdir($cssDir, 0755, true);
        }
        
        // Write the file
        file_put_contents($cssFile, $css);
    }

    public function getAvailableFonts()
    {
        return [
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
        ];
    }
} 