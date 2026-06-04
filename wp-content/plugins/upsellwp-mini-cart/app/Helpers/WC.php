<?php
/**
 * Mini-cart by UpsellWP
 *
 * @package   upsellwp-mini-cart
 * @author    Team UpsellWP <team@upsellwp.com>
 * @license   GPL-3.0-or-later
 * @link      https://upsellwp.com
 */

namespace UWPMC\App\Helpers;

defined('ABSPATH') || exit;

class WC
{
    /**
     * Get the product object.
     *
     * @param mixed $object_or_id
     * @return \WC_Product|false
     */
    public static function getProduct($object_or_id = false)
    {
        if (is_object($object_or_id) && is_a($object_or_id, '\WC_Product')) {
            return $object_or_id;
        } elseif (function_exists('wc_get_product') && $product = wc_get_product($object_or_id)) {
            return $product;
        }
        return false;
    }

    /**
     * To get cart object.
     *
     * @return \WC_Cart|null
     */
    public static function getCart(): ?\WC_Cart
    {
        if (function_exists('WC') && isset(WC()->cart)) {
            return WC()->cart;
        }
        return null;
    }

    /**
     * Get cart items.
     *
     * @return array
     */
    public static function getCartItems(): array
    {
        if (function_exists('WC') && isset(WC()->cart) && method_exists(WC()->cart, 'get_cart_contents')) {
            return WC()->cart->get_cart_contents();
        }
        return [];
    }

    /**
     * Get cart item.
     *
     * @param string $key
     * @return array
     */
    public static function getCartItem(string $key): array
    {
        if (function_exists('WC') && isset(WC()->cart) && method_exists(WC()->cart, 'get_cart_item')) {
            return WC()->cart->get_cart_item($key);
        }
        return [];
    }

    /**
     * Check if the product is purchasable
     *
     * @param object|int $object_or_id
     * @param int $quantity
     * @return bool
     */
    public static function isPurchasableProduct($object_or_id, int $quantity = 1): bool
    {
        $product = self::getProduct($object_or_id);
        if (is_object($product) && method_exists($product, 'is_purchasable') && $product->is_purchasable()) {
            if (method_exists($product, 'get_status') && $product->get_status() != 'publish') {
                return false;
            }
            if (method_exists($product, 'is_in_stock') && !$product->is_in_stock()) {
                return false;
            }
            if (method_exists($product, 'has_enough_stock') && !$product->has_enough_stock($quantity)) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * To get random product ids.
     *
     * @param int $limit
     * @return array
     */
    public static function getRandomProducts(int $limit = 3): array
    {
        $ids = [];
        $args = [
            'limit' => $limit,
            'orderby' => 'rand', // order by random
            'status' => 'publish', // exclude products in draft
            'stock_status' => 'instock', // exclude out of stock products
        ];
        $products = function_exists('wc_get_products') ? wc_get_products($args) : [];
        foreach ($products as $product) {
            if (is_object($product) && method_exists($product, 'get_id')) {
                $ids[] = $product->get_id();
            }
        }
        return apply_filters('uwpmc_random_product_ids', $ids);
    }

    /**
     * Get data from session.
     *
     * @param string $key
     * @param false|mixed $default
     * @return mixed
     */
    public static function getSession(string $key, $default = false)
    {
        if (function_exists('WC') && is_object(WC()->session) && method_exists(WC()->session, 'get')) {
            return WC()->session->get($key, $default);
        }
        return $default;
    }

    /**
     * Set data to session.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public static function setSession(string $key, $value): bool
    {
        if (function_exists('WC') && is_object(WC()->session) && method_exists(WC()->session, 'set')) {
            WC()->session->set($key, $value);
            return true;
        }
        return false;
    }

    /**
     * To delete session.
     *
     * @param string $key
     * @return bool
     */
    public static function deleteSession(string $key): bool
    {
        if (self::getSession($key)) {
            return self::setSession($key, null);
        }
        return false;
    }

    /**
     * To get the last notice from session.
     *
     * @param bool $clear_notices
     * @return array
     */
    public static function getLastNoticeFromSession($clear_notices = false): array
    {
        $session = self::getSession('wc_notices', []);
        if (empty($session)) {
            return [];
        }
        if ($clear_notices) {
            wc_clear_notices();
        }

        if (!empty($session['success'])) {
            foreach ($session['success'] as $data) {
                $message = $data['notice'];
            }
            $status = 'success';
        }

        if (empty($message) && !empty($session['error'])) {
            foreach ($session['error'] as $data) {
                $message = $data['notice'];
            }
            $status = 'error';
        }

        return [
            'message' => $message ?? '',
            'status' => $status ?? '',
        ];
    }
}