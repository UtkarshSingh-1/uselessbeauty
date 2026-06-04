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

use UWPMC\App\Helpers\Input;
use UWPMC\App\Helpers\Template;
use UWPMC\App\Helpers\WC;

defined('ABSPATH') || exit;

class Ajax
{
    /**
     * Get authenticated user request handlers.
     *
     * @return array
     */
    private static function getAuthRequestHandlers(): array
    {
        return (array)apply_filters('uwpmc_ajax_auth_request_handlers', [
            'save_settings' => [__CLASS__, 'saveSettings'],
            'remove_item_from_cart' => [__CLASS__, 'removeItemFromCart'],
            'update_item_quantity' => [__CLASS__, 'updateItemQuantity'],
            'get_sidebar_fragments' => [__CLASS__, 'getSidebarFragments'],
            'apply_coupon' => [__CLASS__, 'applyCoupon'],
            'remove_coupon' => [__CLASS__, 'removeCoupon'],
            'add_product_to_cart' => [__CLASS__, 'addProductToCart'],
            'get_banner_image' => [__CLASS__, 'getBannerImage'],
            'get_side_cart_data' => [__CLASS__, 'getSideCartData'],
            'get_tabs_info' => [__CLASS__, 'getTabsInfo'],
            'get_themes_list' => [__CLASS__, 'getThemesList'],
            'get_theme_styles' => [__CLASS__, 'getThemeStyles'],
            'get_side_cart_preview' => [__CLASS__, 'getSideCartPreview'],
            'get_translated_texts' => [__CLASS__, 'getAllTranslatedText'],
        ]);
    }

    /**
     * Get non-authenticated (guest) user request handlers.
     *
     * @return array
     */
    private static function getGuestRequestHandlers(): array
    {
        return (array)apply_filters('uwpmc_ajax_guest_request_handlers', [
            'remove_item_from_cart' => [__CLASS__, 'removeItemFromCart'],
            'update_item_quantity' => [__CLASS__, 'updateItemQuantity'],
            'get_sidebar_fragments' => [__CLASS__, 'getSidebarFragments'],
            'apply_coupon' => [__CLASS__, 'applyCoupon'],
            'remove_coupon' => [__CLASS__, 'removeCoupon'],
            'add_product_to_cart' => [__CLASS__, 'addProductToCart'],
        ]);
    }

    /**
     * To handle authenticated user requests.
     *
     * @return void
     */
    public static function handleAuthRequests()
    {
        $nonce = wp_unslash(Input::get('nonce', '', 'post'));
        if (empty($nonce) || !wp_verify_nonce($nonce, 'uwpmc_nonce')) {
            wp_send_json_error(['message' => __("Security check failed!", 'upsellwp-mini-cart')]);
        }

        $method = wp_unslash(Input::get('method', '', 'post'));
        $handlers = self::getAuthRequestHandlers();
        if (!empty($method) && isset($handlers[$method]) && is_callable($handlers[$method])) {
            wp_send_json_success(call_user_func($handlers[$method]));
        }
        wp_send_json_error(['message' => __("Method not exists.", 'upsellwp-mini-cart')]);
    }

    /**
     * To handle non-authenticated (guest) user requests.
     *
     * @return void
     */
    public static function handleGuestRequests()
    {
        $nonce = wp_unslash(Input::get('nonce', '', 'post'));
        if (empty($nonce) || !wp_verify_nonce($nonce, 'uwpmc_nonce')) {
            wp_send_json_error(['message' => __("Security check failed!", 'upsellwp-mini-cart')]);
        }

        $method = wp_unslash(Input::get('method', '', 'post'));
        $handlers = self::getGuestRequestHandlers();
        if (!empty($method) && isset($handlers[$method]) && is_callable($handlers[$method])) {
            wp_send_json_success(call_user_func($handlers[$method]));
        }
        wp_send_json_error(['message' => __("Method not exists.", 'upsellwp-mini-cart')]);
    }

    /**
     * To save settings.
     *
     * @return array
     */
    public static function saveSettings()
    {
        $settings = Input::get('uwpmc_settings', '', 'post');
        if (!empty($settings)) {
            $data = wp_kses_post_deep(wp_unslash($settings));
            if (MiniCart::saveSetting($data)) {
                return ['message' => __('Saved Successfully', 'upsellwp-mini-cart')];
            }
        }
        return ['status' => 'error'];
    }

    /**
     * Remove item from cart.
     *
     * @return array
     */
    private static function removeItemFromCart(): array
    {
        $cart_item_key = wp_unslash(Input::get('cart_item_key', '', 'post'));
        $cart = WC::getCart();
        if (!empty($cart) && !empty($cart_item_key) && method_exists($cart, 'remove_cart_item')) {
            $removed = $cart->remove_cart_item($cart_item_key);
            return self::prepareResponse([
                'status' => $removed ? 'success' : 'error',
                'removed' => $removed,
            ]);
        }
        return ['status' => "error"];
    }

    /**
     * To update cart item quantity.
     *
     * @return array
     */
    private static function updateItemQuantity(): array
    {
        $cart_item_key = wp_unslash(Input::get('cart_item_key', '', 'post'));
        $current_quantity = wp_unslash(Input::get('current_quantity', '', 'post'));
        $quantity_action = wp_unslash(Input::get('quantity_action', '', 'post'));
        $cart = WC::getCart();

        if (!empty($cart) && !empty($cart_item_key) && !empty($quantity_action)
            && method_exists($cart, 'remove_cart_item')
            && method_exists($cart, 'set_quantity')
        ) {
            if (empty($current_quantity)) {
                $quantity_updated = $cart->remove_cart_item($cart_item_key);
            } else {
                if ($quantity_action == 'plus') {
                    $current_quantity += 1;
                } elseif ($quantity_action == 'minus') {
                    $current_quantity -= 1;
                }
                $cart_item = WC::getCartItem($cart_item_key);
                if (!empty($cart_item['product_id']) && !WC::isPurchasableProduct($cart_item['product_id'], $current_quantity)) {
                    return ['status' => "error", 'message' => __("No more quantities are available in stock.", 'upsellwp-mini-cart')];
                }
                $quantity_updated = $cart->set_quantity($cart_item_key, $current_quantity);
            }
            return self::prepareResponse([
                'status' => $quantity_updated ? 'success' : 'error',
                'quantity_updated' => $quantity_updated,
            ]);
        }
        return ['status' => "error"];
    }

    /**
     * Get banner image
     *
     * @return array
     */
    private static function getBannerImage(): array
    {
        $image_id = wp_unslash(Input::get('image_id', '', 'post'));
        $html = '';
        if ($image_id) {
            $html = wp_get_attachment_image($image_id, 'small');
        }
        return ['html' => $html];
    }

    /**
     * To get sidebar html.
     *
     * @return array
     */
    private static function getSidebarFragments(): array
    {
        return self::prepareResponse();
    }

    /**
     * To apply coupon.
     *
     * @return array
     */
    private static function applyCoupon(): array
    {
        $coupon_code = wp_unslash(Input::get('coupon_code', '', 'post'));
        $cart = WC::getCart();
        if (!empty($cart) && !empty($coupon_code) && isset(WC()->session)
            && method_exists($cart, 'apply_coupon')
            && method_exists($cart, 'calculate_totals')
            && function_exists('wc_clear_notices')
        ) {
            wc_clear_notices();
            $applied = $cart->apply_coupon($coupon_code);

            $session = WC::getSession('wc_notices', []);
            $message = '';
            if (isset($session['success'])) {
                $message = $session['success'][0]['notice'];
            } elseif (isset($session['error'])) {
                $message = $session['error'][0]['notice'];
            }

            wc_clear_notices();

            if ($applied) {
                WC()->cart->calculate_totals();
            }

            return self::prepareResponse([
                'status' => $applied ? 'success' : 'error',
                'applied' => $applied,
                'message' => $message,
            ]);

        }
        return ['status' => 'error'];

    }

    /**
     * To remove coupon.
     *
     * @return array
     */
    private static function removeCoupon(): array
    {
        $coupon_code = wp_unslash(Input::get('coupon_code', '', 'post'));
        $cart = WC::getCart();
        if (!empty($cart) && !empty($coupon_code)
            && method_exists($cart, 'remove_coupon')
            && method_exists($cart, 'calculate_totals')
            && function_exists('wc_clear_notices')
        ) {
            $message = __("Unable to remove coupon.", 'upsellwp-mini-cart');
            $removed = $cart->remove_coupon($coupon_code);
            if ($removed) {
                wc_clear_notices();
                WC()->cart->calculate_totals();
                $message = __("Coupon has been removed.", 'upsellwp-mini-cart');
            }

            return self::prepareResponse([
                'status' => $removed ? 'success' : 'error',
                'removed' => $removed,
                'message' => $message,
            ]);
        }
        return ['status' => "error"];
    }

    /**
     * Add product to cart.
     *
     * @return array
     */
    private static function addProductToCart(): array
    {
        $product_id = wp_unslash(Input::get('product_id', '', 'post'));
        $quantity = wp_unslash(Input::get('quantity', 1, 'post'));
        if (!empty($product_id)) {
            $cart = WC::getCart();
            try {
                $added = $cart->add_to_cart($product_id, $quantity);
                return self::prepareResponse([
                    'status' => $added ? 'success' : 'error',
                    'added' => $added,
                ]);
            } catch (\Exception $e) {
            }
        }
        return ['status' => "error"];
    }

    /**
     * To get side cart data.
     *
     * @return array
     */
    public static function getSideCartData(): array
    {
        return MiniCart::getData();
    }


    /**
     * To get tab info
     *
     * @return array
     */
    public static function getTabsInfo(): array
    {
        return Template::getTabs();
    }

    /**
     * To get themes list.
     *
     * @return array
     */
    public static function getThemesList(): array
    {
        $themes = Template::getThemes();
        foreach ($themes as $theme_key => $theme) {
            $callback = function ($data) use ($theme_key) {
                $data['style'] = Template::getThemeStyle($theme_key);
                return $data;
            };

            add_filter('uwpmc_template_data', $callback, 100);
            $html = uwpmc_get_template('widget', [], false) . uwpmc_get_template('sidebar', [], false);
            remove_filter('uwpmc_template_data', $callback, 100);
            $themes[$theme_key]['html']  = $html;
        }

        return $themes;
    }

    /**
     * Get active theme styles
     *
     * @return array
     */
    public static function getThemeStyles(): array
    {
        $active_theme = Input::get('active_theme', '', 'post');
        return Template::getThemeStyle($active_theme);
    }

    /**
     * Get side cart preview
     *
     * @return string
     */
    public static function getSideCartPreview(): string
    {
        $html = MiniCart::getTemplate('widget', [], false);
        $html .= MiniCart::getTemplate('sidebar', [], false);
        return $html;
    }

    /**
     * To prepare response with sidebar data.
     *
     * @param array $extra_data
     * @return array
     */
    private static function prepareResponse(array $extra_data = []): array
    {
        if (!empty(Input::get('process_notice', '', 'post'))) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $extra_data = array_merge(WC::getLastNoticeFromSession(true), $extra_data);
        }

        return array_merge([
            'cart_body' => MiniCart::getTemplate('contents/cart', [], false),
            'cart_items_qty' => method_exists(WC()->cart, 'get_cart_contents_count')
                ? WC()->cart->get_cart_contents_count() : '',
        ], $extra_data);
    }

    /**
     * Get translated text.
     *
     * @return void
     */
    public static function getAllTranslatedText()
    {
        $labels = Labels::getLabels();
        if (!empty($labels)) {
            wp_send_json_success($labels);
        }
        wp_send_json_error(__('Error - Something wrong', 'upsellwp-mini-cart'));
    }
}