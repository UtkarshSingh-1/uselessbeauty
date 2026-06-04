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

defined('ABSPATH') || exit;

class Page
{
    /**
     * To add admin menu.
     *
     * @return void
     */
    public static function addMenu()
    {
        add_menu_page(
            esc_html__('Side Cart', 'upsellwp-mini-cart'),
            esc_html__('Side Cart', 'upsellwp-mini-cart'),
            'manage_woocommerce',
            UWPMC_PLUGIN_SLUG,
            [__CLASS__, 'adminPage'],
            'dashicons-cart',
            60
        );
    }

    /**
     * To load admin menu page.
     *
     * @return void
     */
    public static function adminPage()
    {
        if (file_exists(UWPMC_PLUGIN_PATH . '/app/Views/Main.php')) {
            $status = wp_unslash(Input::get('uwpmc_status', '', 'query'));
            include UWPMC_PLUGIN_PATH . '/app/Views/Main.php';
        }
    }

    /**
     * To change something.
     */
    public static function addTweaks()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (wp_unslash(Input::get('page', '', 'query')) == UWPMC_PLUGIN_SLUG) {
            // remove notices
            remove_all_filters('admin_notices');

            // limit MCE (classic text editor) functionality
            add_filter('mce_buttons', function () {
                return [
                    'bold',
                    'italic',
                    'bullist',
                    'numlist',
                    'blockquote',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    'link',
                ];
            }, 1000);
            add_filter('mce_buttons_2', '__return_empty_array', 1000);
        }
    }
}