<?php
/**
 * Mini-cart by UpsellWP
 *
 * @package   upsellwp-mini-cart
 * @author    Team UpsellWP <team@upsellwp.com>
 * @license   GPL-3.0-or-later
 * @link      https://upsellwp.com
 */

namespace UWPMC\App;

use UWPMC\App\Controllers\Ajax;
use UWPMC\App\Controllers\Assets;
use UWPMC\App\Controllers\Compatibility;
use UWPMC\App\Controllers\MiniCart;
use UWPMC\App\Controllers\Page;

defined('ABSPATH') || exit;

class Route
{
    /**
     * To add hooks.
     */
    public static function init()
    {

	    self::addGeneralHooks();
        self::addCompatibilityHooks();

        if (is_admin()) {
            self::addAdminHooks();
        } else {
            self::addStoreHooks();
        }
    }

    /**
     * To load admin hooks.
     */
    public static function addAdminHooks()
    {
        add_action('wp_loaded', [MiniCart::class, 'addProductsForPreview']);
        add_action('admin_enqueue_scripts', [Assets::class, 'loadAdminAssets']);
        add_action('admin_init', [Page::class, 'addTweaks']);
        add_action('admin_menu', [Page::class, 'addMenu']);

        if (wp_doing_ajax()) {
            add_action('wp_ajax_uwpmc_get_nonce', [MiniCart::class, 'getNonce']);
        }
    }

    /**
     * To load store hooks.
     */
    public static function addStoreHooks()
    {
        add_action('woocommerce_cart_emptied', [MiniCart::class, 'deleteSessionData']);
        add_action('wp_enqueue_scripts', [Assets::class, 'loadFrontendAssets']);
        add_action('wp_footer', [MiniCart::class, 'loadWidgetAndSidebar']);
    }

    /**
     * To load general hooks.
     */
    public static function addGeneralHooks()
    {
        add_action('wp_ajax_uwpmc_ajax', [Ajax::class, 'handleAuthRequests']);
        add_action('wp_ajax_nopriv_uwpmc_ajax', [Ajax::class, 'handleGuestRequests']);
    }

    /**
     * To add compatibility hooks.
     */
    public static function addCompatibilityHooks()
    {
        // to load mini-cart display locations in UpsellWP Cart Upsells campaign
        add_filter('cuw_cart_upsell_offer_display_locations_on_mini_cart', [Compatibility::class, 'addUpsellWPDisplayLocations'], 100);

        // to avoid cache data for Mini-cart display locations
        add_filter('cuw_cache_cart_upsell_offers_data', [Compatibility::class, 'ignoreUpsellWPDisplayCache'], 100, 2);
    }
}