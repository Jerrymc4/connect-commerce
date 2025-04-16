<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSettings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'string'
    ];

    /**
     * Get the computed cart badge color.
     * 
     * @return string
     */
    public function getCartBadgeColorAttribute($value)
    {
        // Fall back to primary color if not set
        return $value ?: $this->primary_color;
    }

    /**
     * Get the CSS variables for the theme.
     * 
     * @return string
     */
    public function getCssVariables(): string
    {
        $css = ":root {\n";
        $css .= "  --color-primary: {$this->primary_color};\n";
        $css .= "  --color-secondary: {$this->secondary_color};\n";
        $css .= "  --color-body-bg: {$this->body_bg_color};\n";
        $css .= "  --color-card-bg: {$this->card_bg_color};\n";
        $css .= "  --color-text: {$this->text_color};\n";
        $css .= "  --color-link: {$this->link_color};\n";
        $css .= "  --color-card-link: {$this->card_link_color};\n";
        $css .= "  --color-category-link: {$this->category_link_color};\n";
        $css .= "  --color-product-link: {$this->product_link_color};\n";
        $css .= "  --color-cart-badge: {$this->cart_badge_bg_color};\n";
        $css .= "  --font-family: '{$this->font_family}', sans-serif;\n";
        $css .= "}\n";

        return $css;
    }
}
