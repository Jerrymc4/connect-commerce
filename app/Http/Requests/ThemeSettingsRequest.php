<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ThemeSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'primary_color' => 'required|string|max:25',
            'button_bg_color' => 'required|string|max:25',
            'button_text_color' => 'required|string|max:25',
            'footer_bg_color' => 'required|string|max:25',
            'navbar_text_color' => 'required|string|max:25',
            'cart_badge_bg_color' => 'nullable|string|max:25',
            'banner_text' => 'nullable|string|max:255',
            'body_bg_color' => 'required|string|max:25',
            'font_family' => 'required|string|max:100',
            'link_color' => 'required|string|max:25',
            'card_bg_color' => 'required|string|max:25',
            'border_radius' => 'required|string|max:25',
            'button_style' => 'nullable|string|in:filled,outline,flat,3d,gradient',
            'button_border_radius' => 'nullable|string|max:25',
            'button_hover_effect' => 'nullable|string|in:darken,lighten,zoom,glow,none',
            'newsletter_button_color' => 'nullable|string|max:25',
            'logo' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:2048',
        ];
    }
    
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'primary_color.required' => 'Primary color is required',
            'button_bg_color.required' => 'Button background color is required',
            'button_text_color.required' => 'Button text color is required',
            'footer_bg_color.required' => 'Footer background color is required',
            'navbar_text_color.required' => 'Navbar text color is required',
            'body_bg_color.required' => 'Body background color is required',
            'font_family.required' => 'Font family is required',
            'link_color.required' => 'Link color is required',
            'card_bg_color.required' => 'Card background color is required',
            'border_radius.required' => 'Border radius is required',
        ];
    }
} 