<?php
/**
 * Plugin Name:          UpsellWP Side Cart for WooCommerce
 * Plugin URI:           https://upsellwp.com/add-ons/mini-cart
 * Description:          Sliding WooCommerce side cart.
 * Version:              1.0.5
 * Requires at least:    6.0
 * Requires PHP:         7.2
 * Requires Plugins:     woocommerce
 * Author:               UpsellWP
 * Author URI:           https://upsellwp.com
 * Slug:                 upsellwp-mini-cart
 * Text Domain:          upsellwp-mini-cart
 * Domain Path:          /i18n/languages
 * License:              GPL v3 or later
 * License URI:          https://www.gnu.org/licenses/gpl-3.0.html
 *
 * WC requires at least: 6.0
 * WC tested up to:      10.4
 */

defined('ABSPATH') || exit;

// define basic plugin constants.
defined('UWPMC_PLUGIN_FILE') || define('UWPMC_PLUGIN_FILE', __FILE__);
defined('UWPMC_PLUGIN_PATH') || define('UWPMC_PLUGIN_PATH', plugin_dir_path(__FILE__));
defined('UWPMC_PLUGIN_NAME') || define('UWPMC_PLUGIN_NAME', 'UpsellWP: Side Cart');
defined('UWPMC_PLUGIN_SLUG') || define('UWPMC_PLUGIN_SLUG', 'upsellwp-mini-cart');
defined('UWPMC_PLUGIN_VERSION') || define('UWPMC_PLUGIN_VERSION', '1.0.5');

// to load composer autoload (PSR-4).
if (file_exists(UWPMC_PLUGIN_PATH . '/vendor/autoload.php')) {
    require UWPMC_PLUGIN_PATH . '/vendor/autoload.php';
}

// to bootstrap the plugin.
if (class_exists('UWPMC\App\Route') && !function_exists('uwpmc_get_template')) {

    add_action('plugins_loaded', function () {
        do_action('uwpmc_before_init');
        if (UWPMC\App\Helpers\Plugin::checkDependencies()) {
            UWPMC\App\Route::init(); // to init plugin hooks.
        }
        do_action('uwpmc_after_init');

        $i18n_path = dirname(plugin_basename(UWPMC_PLUGIN_FILE)) . '/i18n/languages';
	    load_theme_textdomain('upsellwp-mini-cart', false, $i18n_path);
    }, 1);

    /**
     * To get template.
     *
     * @param string $file
     * @param array $params
     * @param bool $print
     * @return string|false
     */
    function uwpmc_get_template(string $file, array $params = [], bool $print = true): string
    {
        return (string)\UWPMC\App\Controllers\MiniCart::getTemplate($file, $params, $print);
    }
}

// to declare WooCommerce features compatibility.
add_action('before_woocommerce_init', function () {
    if (method_exists('Automattic\WooCommerce\Utilities\FeaturesUtil', 'declare_compatibility')) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__);
    }
});
