<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    /**
     * Display the theme configuration form.
     */
    public function index(): View
    {
        // Get the current tenant/store
        $store = tenant();
        
        // Get theme settings from store data
        $themeSettings = $store->data['theme'] ?? [
            'colors' => [
                'primary' => '#3B82F6',
                'secondary' => '#10B981',
                'accent' => '#8B5CF6',
                'background' => '#FFFFFF',
                'text' => '#111827',
            ],
            'typography' => [
                'heading_font' => 'Inter',
                'body_font' => 'Inter',
                'base_size' => '16px',
            ],
            'layout' => [
                'header_style' => 'default',
                'footer_style' => 'default',
                'sidebar_position' => 'left',
            ],
            'components' => [
                'button_style' => 'rounded',
                'card_style' => 'shadow',
            ],
            'custom_css' => '',
        ];
        
        // Get available fonts
        $availableFonts = [
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
        ];
        
        return view('store.theme', compact('themeSettings', 'availableFonts'));
    }

    /**
     * Update the theme settings.
     */
    public function update(Request $request)
    {
        // Validate the theme settings
        $validated = $request->validate([
            'colors.primary' => 'required|string',
            'colors.secondary' => 'required|string',
            'colors.accent' => 'required|string',
            'colors.background' => 'required|string',
            'colors.text' => 'required|string',
            'typography.heading_font' => 'required|string',
            'typography.body_font' => 'required|string',
            'typography.base_size' => 'required|string',
            'layout.header_style' => 'required|string',
            'layout.footer_style' => 'required|string',
            'layout.sidebar_position' => 'required|string',
            'components.button_style' => 'required|string',
            'components.card_style' => 'required|string',
            'custom_css' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
        ]);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('theme', 'public');
            $validated['logo'] = $logoPath;
        }
        
        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('theme', 'public');
            $validated['favicon'] = $faviconPath;
        }
        
        // Get the current tenant/store
        $store = tenant();
        $storeData = $store->data ?? [];
        
        // Update the theme settings
        $storeData['theme'] = $validated;
        $store->data = $storeData;
        $store->save();
        
        // Generate CSS file from theme settings
        $this->generateThemeCSS($validated);
        
        return redirect()->route('store.themes')
            ->with('success', 'Theme settings saved successfully');
    }

    /**
     * Generate CSS from theme settings.
     */
    private function generateThemeCSS(array $themeSettings): void
    {
        // Build CSS from theme settings
        $css = ":root {\n";
        $css .= "  --color-primary: {$themeSettings['colors']['primary']};\n";
        $css .= "  --color-secondary: {$themeSettings['colors']['secondary']};\n";
        $css .= "  --color-accent: {$themeSettings['colors']['accent']};\n";
        $css .= "  --color-background: {$themeSettings['colors']['background']};\n";
        $css .= "  --color-text: {$themeSettings['colors']['text']};\n";
        $css .= "  --font-heading: {$themeSettings['typography']['heading_font']}, sans-serif;\n";
        $css .= "  --font-body: {$themeSettings['typography']['body_font']}, sans-serif;\n";
        $css .= "  --font-size-base: {$themeSettings['typography']['base_size']};\n";
        $css .= "}\n\n";
        
        // Add component styles
        $css .= "/* Button Styles */\n";
        if ($themeSettings['components']['button_style'] === 'rounded') {
            $css .= ".btn { border-radius: 9999px; }\n";
        } elseif ($themeSettings['components']['button_style'] === 'square') {
            $css .= ".btn { border-radius: 0; }\n";
        } else {
            $css .= ".btn { border-radius: 0.375rem; }\n";
        }
        
        // Add card styles
        $css .= "\n/* Card Styles */\n";
        if ($themeSettings['components']['card_style'] === 'shadow') {
            $css .= ".card { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }\n";
        } elseif ($themeSettings['components']['card_style'] === 'border') {
            $css .= ".card { border: 1px solid rgba(0, 0, 0, 0.1); }\n";
        } else {
            $css .= ".card { border: none; }\n";
        }
        
        // Add custom CSS
        if (!empty($themeSettings['custom_css'])) {
            $css .= "\n/* Custom CSS */\n";
            $css .= $themeSettings['custom_css'] . "\n";
        }
        
        // Save the CSS file
        Storage::disk('public')->put('theme/custom.css', $css);
    }
} 