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

class Compatibility
{
    /**
     * To add display locations.
     *
     * @hooked cuw_cart_upsell_offer_display_locations_on_mini_cart
     */
    public static function addUpsellWPDisplayLocations($locations)
    {
        return array_merge($locations, self::getMiniCartLocations());
    }

    /**
     * To ignore cart upsell display cache.
     *
     * @hooked cuw_cache_cart_upsell_offers_data
     */
    public static function ignoreUpsellWPDisplayCache($cache, $location)
    {
        if (in_array($location, array_keys(self::getMiniCartLocations()))) {
            $cache = false;
        }
        return $cache;
    }

    /**
     * Returns mini-cart locations.
     *
     * @return array
     */
    public static function getMiniCartLocations(): array
    {
        return [
            'uwpmc_offer_contents' => esc_html__("Side Cart - Offers section", 'upsellwp-mini-cart'),
        ];
    }
}