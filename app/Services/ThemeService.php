<?php

namespace App\Services;

use App\Repositories\Interfaces\ThemeRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ThemeService
{
    protected ThemeRepositoryInterface $themeRepository;

    public function __construct(ThemeRepositoryInterface $themeRepository)
    {
        $this->themeRepository = $themeRepository;
    }

    public function getThemeSettings(): array
    {
        return $this->themeRepository->getThemeSettings();
    }

    public function getThemeSetting(string $key, $default = null): mixed
    {
        if ($key === 'cart_badge_bg_color') {
            $value = $this->themeRepository->getThemeSetting($key);
            return $value ?: $this->themeRepository->getThemeSetting('primary_color', $default);
        }

        return $this->themeRepository->getThemeSetting($key, $default);
    }

    public function updateThemeSettings(array $settings, array $files = []): bool
    {
        $flattenedSettings = [];

        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $flattenedSettings["{$key}_{$subKey}"] = $subValue;
                }
            } else {
                $flattenedSettings[$key] = $value;
            }
        }

        foreach ($files as $type => $file) {
            if ($file) {
                $tenantId = tenant()->id;
                $filename = "{$type}." . $file->getClientOriginalExtension();
                $path = $file->storeAs("tenant-{$tenantId}/theme/{$type}", $filename, 'public');

                if ($type === 'logo') {
                    $flattenedSettings['logo_url'] = $path;
                } elseif ($type === 'banner') {
                    $flattenedSettings['banner_image'] = $path;
                }
            }
        }

        $result = $this->themeRepository->updateThemeSettings($flattenedSettings);
        $this->generateThemeCSS();

        return $result;
    }

    public function generateThemeCSS(): void
    {
        $settings = $this->getThemeSettings();
        $tenantId = tenant()->id;
        
        // Generate CSS content with a complete set of styles
        $css = ":root {\n";
        $css .= "  --primary: {$settings['primary_color']};\n";
        $css .= "  --secondary: {$settings['button_bg_color']};\n";
        $css .= "  --background: {$settings['body_bg_color']};\n";
        $css .= "  --text-primary: {$settings['navbar_text_color']};\n";
        $css .= "  --text-secondary: {$settings['navbar_text_color']};\n";
        $css .= "  --header-bg: {$settings['body_bg_color']};\n";
        $css .= "  --footer-bg: {$settings['footer_bg_color']};\n";
        $css .= "  --newsletter-bg: " . (isset($settings['newsletter_bg_color']) ? $settings['newsletter_bg_color'] : $settings['primary_color']) . ";\n";
        $css .= "  --newsletter-text: " . (isset($settings['newsletter_text_color']) ? $settings['newsletter_text_color'] : '#FFFFFF') . ";\n";
        $css .= "  --heading-font: {$settings['font_family']};\n";
        $css .= "  --body-font: {$settings['font_family']};\n";
        $css .= "  --base-font-size: 16px;\n";
        $css .= "  --heading-weight: 600;\n";
        $css .= "}\n\n";
        
        // Add base styles
        $css .= "body {\n";
        $css .= "  font-family: var(--body-font);\n";
        $css .= "  font-size: var(--base-font-size);\n";
        $css .= "  color: var(--text-primary);\n";
        $css .= "  background-color: var(--background);\n";
        $css .= "}\n\n";
        
        $css .= "h1, h2, h3, h4, h5, h6 {\n";
        $css .= "  font-family: var(--heading-font);\n";
        $css .= "  font-weight: var(--heading-weight);\n";
        $css .= "}\n\n";
        
        $css .= ".btn-primary {\n";
        $css .= "  background-color: var(--primary);\n";
        $css .= "  border-color: var(--primary);\n";
        $css .= "  color: {$settings['button_text_color']};\n";
        $css .= "}\n\n";
        
        $css .= ".btn-secondary {\n";
        $css .= "  background-color: var(--secondary);\n";
        $css .= "  border-color: var(--secondary);\n";
        $css .= "  color: {$settings['button_text_color']};\n";
        $css .= "}\n\n";
        
        // Header styles
        $css .= "header {\n";
        $css .= "  background-color: var(--header-bg);\n";
        $css .= "}\n\n";
        
        // Footer styles
        $css .= "footer {\n";
        $css .= "  background-color: var(--footer-bg);\n";
        $css .= "}\n\n";
        
        try {
            // Clean approach to ensure we're not creating loops
            // Directly write to the public path that the app is using
            $cssPath = public_path("storage/tenant-{$tenantId}/theme");
            if (!is_dir($cssPath)) {
                mkdir($cssPath, 0755, true);
            }
            
            file_put_contents("{$cssPath}/theme.css", $css);
            
            // Update the theme settings in the database
            $this->themeRepository->updateThemeSettings(['css_generated_at' => now()->toDateTimeString()]);
            
            Log::info("Theme CSS generated successfully for tenant {$tenantId}");
        } catch (\Exception $e) {
            Log::error('Failed to generate theme CSS', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    private function adjustColor(string $hex, int $amount): string
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, $r + $amount));
        $g = max(0, min(255, $g + $amount));
        $b = max(0, min(255, $b + $amount));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    public function getAvailableFonts(): array
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

    public function clearCache(): void
    {
        $this->themeRepository->clearCache();
    }
}
