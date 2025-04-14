<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThemeSettingsRequest;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    protected $themeService;
    
    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    /**
     * Display the theme configuration form.
     */
    public function index(): View
    {
        $themeSettings = $this->themeService->getThemeSettings();
        
        // Available fonts for the dropdown
        $availableFonts = [
            'Inter, sans-serif' => 'Inter',
            'Roboto, sans-serif' => 'Roboto',
            'Open Sans, sans-serif' => 'Open Sans',
            'Lato, sans-serif' => 'Lato',
            'Montserrat, sans-serif' => 'Montserrat',
            'Poppins, sans-serif' => 'Poppins',
            'Raleway, sans-serif' => 'Raleway',
            'Nunito, sans-serif' => 'Nunito',
        ];
        
        return view('store.theme', compact('themeSettings', 'availableFonts'));
    }

    /**
     * Update the theme settings.
     */
    public function update(ThemeSettingsRequest $request)
    {
        // Get validated data
        $validated = $request->validated();
        
        // Extract file uploads
        $files = [
            'logo' => $request->file('logo'),
            'banner' => $request->file('banner'),
        ];
        
        // Update settings
        $this->themeService->updateThemeSettings($validated, $files);
        
        return redirect()->route('admin.settings', ['tab' => 'theme'])
            ->with('success', 'Theme settings saved successfully');
    }

    /**
     * Preview the theme.
     */
    public function preview(): View
    {
        $themeSettings = $this->themeService->getThemeSettings();
        
        return view('store.theme.preview', compact('themeSettings'));
    }
    
    /**
     * Reset theme to defaults.
     */
    public function reset()
    {
        // Reset to default theme settings by clearing the DB
        // The repository will use defaults when there are no records
        $this->themeService->updateThemeSettings([]);
        
        return redirect()->route('admin.settings', ['tab' => 'theme'])
            ->with('success', 'Theme settings have been reset to defaults');
    }
} 