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

class Plugin
{
    /**
     * To check plugin Dependencies.
     *
     * @return bool
     */
    public static function checkDependencies(): bool
    {
        global $wp_version;

        // check PHP version.
        if (!version_compare(PHP_VERSION, '7.2', '>=')) {
            $message = __('UpsellWP: Side Cart requires PHP version 7.2 or above.', 'upsellwp-mini-cart');
            self::adminErrorNotice($message);
            return false;
        }

        // check WordPress version.
        if (!version_compare($wp_version, '6.0', '>=')) {
            $message = __('UpsellWP: Side Cart requires WordPress version 6.0 or above.', 'upsellwp-mini-cart');
            self::adminErrorNotice($message);
            return false;
        }

        // check whether WooCommerce is installed and activate.
        if (!class_exists('WooCommerce')) {
            $message = __('UpsellWP: Side Cart requires WooCommerce is installed and activate.', 'upsellwp-mini-cart');
            self::adminErrorNotice($message);
            return false;
        }

        // check WooCommerce version.
        if (!defined('WC_VERSION') || !version_compare(WC_VERSION, '6.0', '>=')) {
            $message = __('UpsellWP: Side Cart requires WooCommerce version 6.0 or above.', 'upsellwp-mini-cart');
            self::adminErrorNotice($message);
            return false;
        }

        return true;
    }

    /**
     * To print dependencies missing message.
     *
     * @param string $message
     * @return void
     */
    public static function adminErrorNotice(string $message)
    {
        if (!is_admin()) return;

        add_action('admin_notices', function () use ($message) { ?>
            <div class="notice notice-error">
                <p><?php echo esc_html($message); ?></p>
            </div>
            <?php
        }, 1);
    }

    /**
     * Check UpsellWP plugin is active with version.
     *
     * @param string $version
     * @return bool
     */
    public static function isUpsellWPActive(string $version = '2.1'): bool
    {
        return defined('CUW_VERSION') && version_compare(CUW_VERSION, $version, '>=');
    }
}