<?php

namespace App\Repositories\Interfaces;

interface ThemeRepositoryInterface
{
    /**
     * Get all theme settings.
     *
     * @return array
     */
    public function getThemeSettings();
    
    /**
     * Get a specific theme setting by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getThemeSetting(string $key, $default = null);
    
    /**
     * Update theme settings.
     *
     * @param array $settings
     * @return bool
     */
    public function updateThemeSettings(array $settings);
    
    /**
     * Upload a theme asset (image/file).
     *
     * @param mixed $file
     * @param string $type
     * @return string|null Path to uploaded file
     */
    public function uploadThemeAsset($file, string $type);
} 