@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Theme Customization</h1>
        <div class="flex space-x-2">
            <button type="button" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center">
                <i class="fas fa-desktop mr-2"></i> Preview
            </button>
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-save mr-2"></i> Save Changes
            </button>
        </div>
    </div>
    
    <div class="mb-6">
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Customize your store's appearance. Changes will be applied to your store in real-time.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Theme Selector -->
        <div class="md:w-1/4">
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 sticky top-4">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Theme Options</h2>
                
                <nav class="space-y-1">
                    <a href="#colors" class="block px-3 py-2 rounded-md bg-blue-50 text-blue-700 font-medium">
                        <i class="fas fa-palette mr-2"></i> Colors
                    </a>
                    <a href="#typography" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-font mr-2"></i> Typography
                    </a>
                    <a href="#header" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-heading mr-2"></i> Header
                    </a>
                    <a href="#footer" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-shoe-prints mr-2"></i> Footer
                    </a>
                    <a href="#homepage" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-home mr-2"></i> Homepage
                    </a>
                    <a href="#product-pages" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-box mr-2"></i> Product Pages
                    </a>
                    <a href="#checkout" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-shopping-cart mr-2"></i> Checkout
                    </a>
                    <a href="#custom-css" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-code mr-2"></i> Custom CSS
                    </a>
                </nav>
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Templates</h3>
                    <div class="space-y-3">
                        <button type="button" class="w-full flex items-center px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                            <span class="h-4 w-4 rounded-full bg-blue-600 mr-2"></span>
                            Modern
                        </button>
                        <button type="button" class="w-full flex items-center px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                            <span class="h-4 w-4 rounded-full bg-gray-400 mr-2"></span>
                            Minimal
                        </button>
                        <button type="button" class="w-full flex items-center px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                            <span class="h-4 w-4 rounded-full bg-gray-400 mr-2"></span>
                            Classic
                        </button>
                        <button type="button" class="w-full flex items-center px-3 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100">
                            <span class="h-4 w-4 rounded-full bg-gray-400 mr-2"></span>
                            Bold
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Theme Editor -->
        <div class="md:w-3/4">
            <form method="POST" action="{{ route('admin.theme.update') }}" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Colors Section -->
                <div id="colors" class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Colors</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-1">Primary Color</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 w-10">
                                    <input type="color" name="primary_color" id="primary_color" value="{{ $theme->primary_color ?? '#3B82F6' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                </span>
                                <input type="text" name="primary_color_hex" id="primary_color_hex" value="{{ $theme->primary_color ?? '#3B82F6' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Used for buttons, links, and accents</p>
                        </div>
                        
                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-1">Secondary Color</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 w-10">
                                    <input type="color" name="secondary_color" id="secondary_color" value="{{ $theme->secondary_color ?? '#10B981' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                </span>
                                <input type="text" name="secondary_color_hex" id="secondary_color_hex" value="{{ $theme->secondary_color ?? '#10B981' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Used for highlighting and secondary elements</p>
                        </div>
                        
                        <div>
                            <label for="background_color" class="block text-sm font-medium text-gray-700 mb-1">Background Color</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 w-10">
                                    <input type="color" name="background_color" id="background_color" value="{{ $theme->background_color ?? '#F9FAFB' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                </span>
                                <input type="text" name="background_color_hex" id="background_color_hex" value="{{ $theme->background_color ?? '#F9FAFB' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Main background color for your store</p>
                        </div>
                        
                        <div>
                            <label for="text_color" class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 w-10">
                                    <input type="color" name="text_color" id="text_color" value="{{ $theme->text_color ?? '#1F2937' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                </span>
                                <input type="text" name="text_color_hex" id="text_color_hex" value="{{ $theme->text_color ?? '#1F2937' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Main text color for your store</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Preview</h3>
                        <div class="p-4 border border-gray-200 rounded-md bg-gray-50 flex flex-wrap gap-4">
                            <div class="flex flex-col items-center">
                                <button type="button" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white" style="background-color: {{ $theme->primary_color ?? '#3B82F6' }}">
                                    Primary Button
                                </button>
                                <span class="mt-1 text-xs text-gray-500">Primary</span>
                            </div>
                            
                            <div class="flex flex-col items-center">
                                <button type="button" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white" style="background-color: {{ $theme->secondary_color ?? '#10B981' }}">
                                    Secondary Button
                                </button>
                                <span class="mt-1 text-xs text-gray-500">Secondary</span>
                            </div>
                            
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 flex items-center justify-center rounded-md" style="background-color: {{ $theme->background_color ?? '#F9FAFB' }}; color: {{ $theme->text_color ?? '#1F2937' }}">
                                    <span>Text</span>
                                </div>
                                <span class="mt-1 text-xs text-gray-500">Background & Text</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Typography Section -->
                <div id="typography" class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Typography</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="heading_font" class="block text-sm font-medium text-gray-700 mb-1">Heading Font</label>
                            <select name="heading_font" id="heading_font" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                <option value="Inter" {{ ($theme->heading_font ?? '') == 'Inter' ? 'selected' : '' }}>Inter</option>
                                <option value="Roboto" {{ ($theme->heading_font ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                <option value="Open Sans" {{ ($theme->heading_font ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                <option value="Montserrat" {{ ($theme->heading_font ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                                <option value="Playfair Display" {{ ($theme->heading_font ?? '') == 'Playfair Display' ? 'selected' : '' }}>Playfair Display</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Used for headings and titles</p>
                        </div>
                        
                        <div>
                            <label for="body_font" class="block text-sm font-medium text-gray-700 mb-1">Body Font</label>
                            <select name="body_font" id="body_font" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                <option value="Inter" {{ ($theme->body_font ?? '') == 'Inter' ? 'selected' : '' }}>Inter</option>
                                <option value="Roboto" {{ ($theme->body_font ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                <option value="Open Sans" {{ ($theme->body_font ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                <option value="Lato" {{ ($theme->body_font ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                                <option value="Nunito" {{ ($theme->body_font ?? '') == 'Nunito' ? 'selected' : '' }}>Nunito</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Used for paragraph text</p>
                        </div>
                        
                        <div>
                            <label for="base_font_size" class="block text-sm font-medium text-gray-700 mb-1">Base Font Size</label>
                            <select name="base_font_size" id="base_font_size" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                <option value="14px" {{ ($theme->base_font_size ?? '') == '14px' ? 'selected' : '' }}>Small (14px)</option>
                                <option value="16px" {{ ($theme->base_font_size ?? '') == '16px' || empty($theme->base_font_size) ? 'selected' : '' }}>Medium (16px)</option>
                                <option value="18px" {{ ($theme->base_font_size ?? '') == '18px' ? 'selected' : '' }}>Large (18px)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Base size for all text</p>
                        </div>
                        
                        <div>
                            <label for="heading_weight" class="block text-sm font-medium text-gray-700 mb-1">Heading Weight</label>
                            <select name="heading_weight" id="heading_weight" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                <option value="400" {{ ($theme->heading_weight ?? '') == '400' ? 'selected' : '' }}>Regular (400)</option>
                                <option value="500" {{ ($theme->heading_weight ?? '') == '500' ? 'selected' : '' }}>Medium (500)</option>
                                <option value="600" {{ ($theme->heading_weight ?? '') == '600' ? 'selected' : '' }}>Semi-Bold (600)</option>
                                <option value="700" {{ ($theme->heading_weight ?? '') == '700' || empty($theme->heading_weight) ? 'selected' : '' }}>Bold (700)</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Font weight for headings</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Preview</h3>
                        <div class="p-4 border border-gray-200 rounded-md">
                            <h1 class="text-2xl font-bold mb-2" style="font-family: {{ $theme->heading_font ?? 'Inter' }}; font-weight: {{ $theme->heading_weight ?? '700' }};">Heading Example</h1>
                            <p style="font-family: {{ $theme->body_font ?? 'Inter' }}; font-size: {{ $theme->base_font_size ?? '16px' }};">
                                This is how your body text will look across your website. The font family and size you choose will affect the readability and overall aesthetics of your store.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Header Section -->
                <div id="header" class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Header</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="header_layout" class="block text-sm font-medium text-gray-700 mb-1">Header Layout</label>
                            <select name="header_layout" id="header_layout" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                <option value="centered" {{ ($theme->header_layout ?? '') == 'centered' ? 'selected' : '' }}>Centered</option>
                                <option value="logo_left" {{ ($theme->header_layout ?? '') == 'logo_left' || empty($theme->header_layout) ? 'selected' : '' }}>Logo Left</option>
                                <option value="minimal" {{ ($theme->header_layout ?? '') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                                <option value="expanded" {{ ($theme->header_layout ?? '') == 'expanded' ? 'selected' : '' }}>Expanded</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="header_color" class="block text-sm font-medium text-gray-700 mb-1">Header Color</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 w-10">
                                    <input type="color" name="header_color" id="header_color" value="{{ $theme->header_color ?? '#FFFFFF' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                </span>
                                <input type="text" name="header_color_hex" id="header_color_hex" value="{{ $theme->header_color ?? '#FFFFFF' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="show_search" id="show_search" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ ($theme->show_search ?? true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="show_search" class="font-medium text-gray-700">Show Search Box</label>
                                <p class="text-gray-500">Display a search box in the header</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="sticky_header" id="sticky_header" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ ($theme->sticky_header ?? false) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="sticky_header" class="font-medium text-gray-700">Sticky Header</label>
                                <p class="text-gray-500">Keep the header visible when scrolling</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="show_announcements" id="show_announcements" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ ($theme->show_announcements ?? false) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="show_announcements" class="font-medium text-gray-700">Announcement Bar</label>
                                <p class="text-gray-500">Display an announcement bar above the header</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="announcement_text" class="block text-sm font-medium text-gray-700 mb-1">Announcement Text</label>
                        <input type="text" name="announcement_text" id="announcement_text" value="{{ $theme->announcement_text ?? 'Free shipping on all orders over $50!' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" {{ ($theme->show_announcements ?? false) ? '' : 'disabled' }}>
                    </div>
                </div>
                
                <!-- Footer Section -->
                <div id="footer" class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Footer</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="footer_layout" class="block text-sm font-medium text-gray-700 mb-1">Footer Layout</label>
                            <select name="footer_layout" id="footer_layout" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                <option value="simple" {{ ($theme->footer_layout ?? '') == 'simple' ? 'selected' : '' }}>Simple</option>
                                <option value="columns" {{ ($theme->footer_layout ?? '') == 'columns' || empty($theme->footer_layout) ? 'selected' : '' }}>Columns</option>
                                <option value="expanded" {{ ($theme->footer_layout ?? '') == 'expanded' ? 'selected' : '' }}>Expanded</option>
                                <option value="minimal" {{ ($theme->footer_layout ?? '') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="footer_color" class="block text-sm font-medium text-gray-700 mb-1">Footer Color</label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 w-10">
                                    <input type="color" name="footer_color" id="footer_color" value="{{ $theme->footer_color ?? '#1F2937' }}" class="h-6 w-6 rounded-full overflow-hidden cursor-pointer">
                                </span>
                                <input type="text" name="footer_color_hex" id="footer_color_hex" value="{{ $theme->footer_color ?? '#1F2937' }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 space-y-4">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="show_payment_icons" id="show_payment_icons" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ ($theme->show_payment_icons ?? true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="show_payment_icons" class="font-medium text-gray-700">Show Payment Icons</label>
                                <p class="text-gray-500">Display payment method icons in the footer</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="show_social_icons" id="show_social_icons" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ ($theme->show_social_icons ?? true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="show_social_icons" class="font-medium text-gray-700">Show Social Media Icons</label>
                                <p class="text-gray-500">Display social media links in the footer</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="show_newsletter_signup" id="show_newsletter_signup" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" {{ ($theme->show_newsletter_signup ?? true) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="show_newsletter_signup" class="font-medium text-gray-700">Newsletter Signup</label>
                                <p class="text-gray-500">Display newsletter signup form in the footer</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-1">Footer Copyright Text</label>
                        <input type="text" name="footer_text" id="footer_text" value="{{ $theme->footer_text ?? '© 2023 Your Store. All rights reserved.' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
                </div>
                
                <!-- Custom CSS Section -->
                <div id="custom-css" class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Custom CSS</h2>
                    
                    <div>
                        <label for="custom_css" class="block text-sm font-medium text-gray-700 mb-1">Custom CSS</label>
                        <textarea name="custom_css" id="custom_css" rows="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent font-mono text-sm">{{ $theme->custom_css ?? '/* Add your custom CSS here */
/* Example:
.product-title {
    font-weight: bold;
    color: #333;
}
*/' }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Add custom CSS to override theme styles</p>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <button type="reset" class="px-5 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset
                    </button>
                    <button type="submit" class="px-5 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Sync color input values
    document.querySelectorAll('input[type="color"]').forEach(colorInput => {
        const hexInput = document.getElementById(colorInput.id + '_hex');
        
        // Update hex input when color changes
        colorInput.addEventListener('input', () => {
            hexInput.value = colorInput.value;
        });
        
        // Update color input when hex changes
        hexInput.addEventListener('input', () => {
            colorInput.value = hexInput.value;
        });
    });
    
    // Toggle announcement text field
    document.getElementById('show_announcements').addEventListener('change', function() {
        document.getElementById('announcement_text').disabled = !this.checked;
    });
</script>
@endpush 