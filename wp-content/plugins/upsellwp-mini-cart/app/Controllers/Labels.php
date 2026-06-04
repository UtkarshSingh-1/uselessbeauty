<?php
/**
 * Mini-cart by UpsellWP
 *
 * @package   upsellwp-mini-cart
 * @author    Team UpsellWP <team@upsellwp.com>
 * @license   GPL-3.0-or-later
 * @link      https://upsellwp.com
 */

namespace UWPMC\App\Controllers;

defined('ABSPATH') || exit;

class Labels
{

    /**
     * Get translated strings.
     *
     * @return array
     */
    public static function getLabels (): array
    {
        return [
            'customization' => __('Customization', 'upsellwp-mini-cart'),
            'theme' => __('Theme', 'upsellwp-mini-cart'),
            'advanced' => __('Advanced', 'upsellwp-mini-cart'),
            'widget' => __('Widget', 'upsellwp-mini-cart'),
            'show_widget' => __('Show widget', 'upsellwp-mini-cart'),
            'position' => __('Position', 'upsellwp-mini-cart'),
            'horizontally' => __('Horizontally', 'upsellwp-mini-cart'),
            'vertically' => __('Vertically', 'upsellwp-mini-cart'),
            'slider' => __('Slider', 'upsellwp-mini-cart'),
            'show_after_adding_product' => __('Show after adding product', 'upsellwp-mini-cart'),
            'header' => __('Header', 'upsellwp-mini-cart'),
            'title' => __('Title', 'upsellwp-mini-cart'),
            'items' => __('Items', 'upsellwp-mini-cart'),
            'show_remove_option' => __('Show remove option', 'upsellwp-mini-cart'),
            'product_price' => __('Product price', 'upsellwp-mini-cart'),
            'coupon' => __('Coupon', 'upsellwp-mini-cart'),
            'show_coupon' => __('Show coupon', 'upsellwp-mini-cart'),
            'buttons' => __('Buttons', 'upsellwp-mini-cart'),
            'checkout_text' => __('Checkout text', 'upsellwp-mini-cart'),
            'show_total' => __('Show total', 'upsellwp-mini-cart'),
            'show_cart_button' => __('Show cart button', 'upsellwp-mini-cart'),
            'cart_text' => __('Cart', 'upsellwp-mini-cart'),
            'cart_totals' => __('Cart totals', 'upsellwp-mini-cart'),
            'show_subtotal' => __('Show subtotal', 'upsellwp-mini-cart'),
            'show_discount' => __('Show discount', 'upsellwp-mini-cart'),
            'active_theme' => __('Active theme:', 'upsellwp-mini-cart'),
            'available_themes' => __('Available themes:', 'upsellwp-mini-cart'),
            'activate' => __('Activate', 'upsellwp-mini-cart'),
            'banner' => __('Banner', 'upsellwp-mini-cart'),
            'enable_banner_section' => __('Enable banner section', 'upsellwp-mini-cart'),
            'add' => __('Add', 'upsellwp-mini-cart'),
            'content' => __('Content', 'upsellwp-mini-cart'),
            'action' => __('Action', 'upsellwp-mini-cart'),
            'goals' => __('Goals', 'upsellwp-mini-cart'),
            'enable_goals_section' =>  __('Enable goals section', 'upsellwp-mini-cart'),
            'free_shipping' =>  __('Free shipping', 'upsellwp-mini-cart'),
            'recommendations' => __('Recommendations', 'upsellwp-mini-cart'),
            'enable_recommendation_section' => __('Enable recommendation section', 'upsellwp-mini-cart'),
            'offers' => __('Offers', 'upsellwp-mini-cart'),
            'enable_offers_section' => __('Enable offers section', 'upsellwp-mini-cart'),
            'save' => __('Save', 'upsellwp-mini-cart'),
            'activated' => __('Activated', 'upsellwp-mini-cart'),
            'edit' => __('Edit', 'upsellwp-mini-cart'),
            'right' => __('Right', 'upsellwp-mini-cart'),
            'left' => __('Left', 'upsellwp-mini-cart'),
            'show_item_subtotal' => __('Show item subtotal', 'upsellwp-mini-cart'),
            'show_product_price' => __('Show product price', 'upsellwp-mini-cart'),
            'delete' => __('Delete', 'upsellwp-mini-cart'),
            'text_color' => __('Text color', 'upsellwp-mini-cart'),
            'background_color' => __('Background color', 'upsellwp-mini-cart'),
            'close' => __('Close', 'upsellwp-mini-cart'),
            'security_verification_failed' => __('Security verification failed!', 'upsellwp-mini-cart'),
            'changes_saved_successfully' => __('Changes saved successfully!', 'upsellwp-mini-cart'),
            'side_cart' => __('Side Cart', 'upsellwp-mini-cart'),
            'by_upsellwp' => __('By UpsellWP', 'upsellwp-mini-cart'),
            'reset' => __('Reset', 'upsellwp-mini-cart'),
            'width' => __('Width', 'upsellwp-mini-cart'),
            'background_type' => __('Background type', 'upsellwp-mini-cart'),
            'solid' => __('Solid', 'upsellwp-mini-cart'),
            'gradient' => __('Gradient', 'upsellwp-mini-cart'),
            'border_width' => __('Border width', 'upsellwp-mini-cart'),
            'border_color' => __('Border color', 'upsellwp-mini-cart'),
            'border_radius' => __('Border radius', 'upsellwp-mini-cart'),
            'none' => __('None', 'upsellwp-mini-cart'),
            'thin' => __('Thin', 'upsellwp-mini-cart'),
            'medium' => __('Medium', 'upsellwp-mini-cart'),
            'component_color' => __('Component color', 'upsellwp-mini-cart'),
            'font_size' => __('Font size', 'upsellwp-mini-cart'),
            'default' => __('Default', 'upsellwp-mini-cart'),
            'banner_image_size' => __('The banner image height must be exactly 42px.', 'upsellwp-mini-cart'),
            'recommended_items' => __('Recommended items', 'upsellwp-mini-cart'),
            'tabs' => __('Tabs', 'upsellwp-mini-cart'),
            'color' => __('Color', 'upsellwp-mini-cart'),
            'preview' => __('Preview', 'upsellwp-mini-cart'),
            'gradient_degree' => __('Gradient degree', 'upsellwp-mini-cart'),
            'gradient_color_1' => __('Gradient color 1', 'upsellwp-mini-cart'),
            'gradient_color_2' => __('Gradient color 2', 'upsellwp-mini-cart'),
            'install_upsellwp' => __('Install UpsellWP', 'upsellwp-mini-cart'),
            'yes' => __('Yes', 'upsellwp-mini-cart'),
            'no' => __('No', 'upsellwp-mini-cart'),
            'add_text' => __('Add text', 'upsellwp-mini-cart'),
            'add_image' => __('Add image', 'upsellwp-mini-cart'),
            'add_banner' => __('Add banner', 'upsellwp-mini-cart'),
            'actions' => __('Actions', 'upsellwp-mini-cart'),
            'loading_banner_image' => __('Loading banner image', 'upsellwp-mini-cart'),
            'edit_banner' => __('Edit banner', 'upsellwp-mini-cart'),
            'write_something_here' => __('Write something here...', 'upsellwp-mini-cart'),
            'clear' => __('Clear', 'upsellwp-mini-cart'),
            'enter_checkout_button_text' => __('Enter checkout button text', 'upsellwp-mini-cart'),
            'enter_cart_button_text' => __('Enter cart button text', 'upsellwp-mini-cart'),
            'coupons' => __('Coupons', 'upsellwp-mini-cart'),
            'enter_side_cart_title' => __('Enter side cart title', 'upsellwp-mini-cart'),
            'select_product_price' => __('Select product price', 'upsellwp-mini-cart'),
            'select_slider_position' => __('Select slider position', 'upsellwp-mini-cart'),
            'show_after_adding_products' => __('Show after adding products', 'upsellwp-mini-cart'),
            'select_widget_position' => __('Select widget position', 'upsellwp-mini-cart'),
            'enter_your_horizontal_value' => __('Enter your horizontal value', 'upsellwp-mini-cart'),
            'enter_your_vertical_value' => __('Enter your vertical value', 'upsellwp-mini-cart'),
            'select_font_size' => __('Select font size', 'upsellwp-mini-cart'),
            'select_input_type' => __('Select input type', 'upsellwp-mini-cart'),
            'activate_theme' => __('Activate theme', 'upsellwp-mini-cart'),
            'confirm_activate_theme' => __('Are you sure, you want to activate this theme?', 'upsellwp-mini-cart'),
            'activate_theme_warning' => __('Upon activation, all locally made style changes are permanently lost.', 'upsellwp-mini-cart'),
        ];
    }
}