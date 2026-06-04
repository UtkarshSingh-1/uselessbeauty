<?php
/**
 * Mini-cart by UpsellWP
 *
 * @package   upsellwp-mini-cart
 * @author    Team UpsellWP <team@upsellwp.com>
 * @license   GPL-3.0-or-later
 * @link      https://upsellwp.com
 */

namespace UWPMC\App\Handlers;

use UWPMC\App\Helpers\WC;
use WC_Shipping_Free_Shipping;
use WC_Shipping_Zones;

defined('ABSPATH') || exit;

class Shipping
{
    /**
     * To get the free shipping data.
     *
     * @return array
     */
    public static function getFreeShippingData(): array
    {
        $data = [];
        $cart = WC::getCart();
        $packages = self::getShippingPackages();
        if (empty($packages) && !empty($cart) && method_exists($cart, 'calculate_shipping')) {
            $cart->calculate_shipping();
        }
        if (!empty($packages) && !empty($cart)) {
            $package = $packages[0] ?? [];
            $shipping_zone = self::getZoneMatchedShipping($package);
            if (!empty($shipping_zone)) {
                $shipping_methods = self::getShippingMethods($shipping_zone);
                if (!empty($shipping_methods)) {
                    $free_shipping_object = false;
                    foreach ($shipping_methods as $method) {
                        if (self::isFreeShippingMethod($method)) {
                            $free_shipping_object = $method;
                            break;
                        }
                    }
                    if (!empty($free_shipping_object) && method_exists($cart, 'get_subtotal')) {
                        $subtotal = $cart->get_subtotal();
                        if ($free_shipping_object->requires === 'min_amount' || $free_shipping_object->requires === 'either') {
                            if ($free_shipping_object->ignore_discounts === 'no' && !empty($cart->get_coupon_discount_totals())) {
                                foreach ($cart->get_coupon_discount_totals() as $coupon_code => $coupon_value) {
                                    $subtotal -= $coupon_value;
                                }
                            }
                            $amount_left = $free_shipping_object->min_amount - $subtotal;
                            $message = $amount_left > 0
                                /* translators: %s: amount */
                                ? sprintf(__('Spend an extra %s to get free shipping!', 'upsellwp-mini-cart'), wc_price($amount_left))
                                : __('Great news! Now you are eligible for free shipping.', 'upsellwp-mini-cart');
                            $data = [
                                'target' => $free_shipping_object->min_amount,
                                'current' => $subtotal,
                                'message' => $message,
                            ];
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * To get the shipping packages.
     *
     * @return false|array
     */
    public static function getShippingPackages()
    {
        if (function_exists('WC') && method_exists('WooCommerce', 'shipping')
            && method_exists('WC_Shipping', 'get_packages')
        ) {
            return WC()->shipping()->get_packages();
        }
        return false;
    }

    /**
     * To get the zone matched shipping package.
     *
     * @param array $package
     * @return \WC_Shipping_Zone|false
     */
    public static function getZoneMatchedShipping(array $package)
    {
        if (!empty($package) && method_exists('WC_Shipping_Zones', 'get_zone_matching_package')) {
            return WC_Shipping_Zones::get_zone_matching_package($package);
        }
        return false;
    }

    /**
     * To get the shipping methods for the zone.
     *
     * @param object $zone
     * @return false|mixed
     */
    public static function getShippingMethods(object $zone)
    {
        if (!empty($zone) && method_exists($zone, 'get_shipping_methods')) {
            return $zone->get_shipping_methods(true);
        }
        return false;
    }

    /**
     * To check the object is instance of WC_Shipping_Free_Shipping.
     *
     * @param object $method
     * @return bool
     */
    public static function isFreeShippingMethod(object $method): bool
    {
        if (!empty($method) && class_exists('WC_Shipping_Free_Shipping')) {
            return $method instanceof WC_Shipping_Free_Shipping;
        }
        return false;
    }
}