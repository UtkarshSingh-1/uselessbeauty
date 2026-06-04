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

use UWPMC\App\Controllers\MiniCart;
use UWPMC\App\Handlers\Shipping;

defined('ABSPATH') || exit;

class Template
{

    /**
     * To get the template HTML or to print.
     *
     * @param $file
     * @param array $params
     * @param bool $print
     * @return false|string
     */
    public static function getHtml($file, array $params = [], bool $print = true)
    {
        if (strpos($file, '.php') === false && strpos($file, '.html') === false) {
            $file .= '.php';
        }
        $file_path = UWPMC_PLUGIN_PATH . 'templates/' . $file;
        if (function_exists('get_theme_file_path')) {
            $override_file_in_theme = get_theme_file_path(UWPMC_PLUGIN_SLUG . '/templates/' . $file);
            if (file_exists($override_file_in_theme)) {
                $file_path = $override_file_in_theme;
            }
        }
        $output = self::renderTemplate($file_path, $params);
        if ($print) {
            echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        return $output;
    }

    /**
     * Render template file.
     *
     * @param $file
     * @param $data
     * @return false|string
     */
    public static function renderTemplate($file, $data): string
    {
        if (file_exists($file)) {
            ob_start();
            extract($data);
            include $file;
            return ob_get_clean();
        }
        return false;
    }

    /**
     * To get the default settings.
     *
     * @return array
     */
    public static function getDefaultData(): array
    {
        return (array)apply_filters('uwpmc_default_template_data', [
            'data' => [
                'widget' => [
                    'position' => 'right',
                    'float_x' => '20',
                    'float_y' => '30',
                    'show' => 1,
                ],
                'slider' => [
                    'position' => 'right',
                    'auto_open' => 1,
                ],
                'header' => [
                    'title' => 'My Cart',
                ],
                'items' => [
                    'item' => [
                        'show_remove' => 1,
                        'display_price' => 'item_subtotal',
                    ],
                ],
                'coupon' => [
                    'enable' => 1,
                ],
                'actions' => [
                    'checkout' => [
                        'text' => 'Checkout',
                        'show_total' => 1,
                    ],
                    'cart' => [
                        'enable' => 0,
                        'text' => 'Cart',
                    ],
                ],
                'totals' => [
                    'show' => [
                        'subtotal' => 1,
                        'discount' => 0,
                        'total' => 0,
                    ],
                ],
            ],
            'active_theme' => 'theme-1',
            'advanced' => [
                'banner' => [
                    'enabled' => 0,
                    'list' => [],
                ],
                'goals' => [
                    'enable' => 0,
                    'list' => [],
                ],
                'recommendations' => [
                    'enable' => 1,
                ],
                'tabs' => [
                    'offers' => [
                        'enable' => 1,
                    ]
                ],
            ],
            'style' => self::getThemeStyle('theme-1'),
        ]);
    }

    /**
     * To get theme data
     *
     * @param string $theme_key
     * @return array
     */
    public static function getThemes(string $theme_key = ''): array
    {
        $themes = apply_filters('uwpmc_themes', [
            'theme-1' => [
                'name' => __('Theme 1', 'upsellwp-mini-cart'),
                'layout' => 'style-1',
            ],
            'theme-2' => [
                'name' => __('Theme 2', 'upsellwp-mini-cart'),
                'layout' => 'style-1',
            ],
            'theme-3' => [
                'name' => __('Theme 3', 'upsellwp-mini-cart'),
                'layout' => 'style-1',
            ],
            'theme-4' => [
                'name' => __('Theme 4', 'upsellwp-mini-cart'),
                'layout' => 'style-1',
            ],
        ]);

        if ($theme_key !== '') {
            return $themes[$theme_key];
        }

        return $themes;
    }

    /**
     * To get theme styles
     *
     * @param string $theme_key
     * @return array
     */
    public static function getThemeStyle(string $theme_key = ''): array
    {
        $themes = apply_filters('uwpmc_default_theme_styles', [
            'theme-1' => [
                'widget' => [
                    'background-color' => '#FFFFFF',
                    'color' => '#0A5CFF',
                ],
                'slider' => [
                    'width' => '360',
                    'background-type' => 'solid',
                    'background-color' => '#FFFFFF',
                    'gradient-deg' => 111,
                    'gradient-bg-color-1' => '',
                    'gradient-bg-color-2' => '',
                ],
                'card' => [
                    'border-width' => 'thin',
                    'border-color' => '#E8EAED',
                    'border-radius' => 6,
                ],
                'component_style' => [
                    'border-color' => '#E8EAED',
                ],
                'header' => [
                    'font-size' => '24px',
                    'color' => '#181C25',
                ],
                'item' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'coupon' => [
                    'background-color' => '#FFFFFF',
                ],
                'action' => [
                    'font-size' => '16px',
                    'background-color' => '#0A5CFF',
                    'color' => '#FAFAFA',
                ],
                'totals' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'recommendations' => [
                    'background-color' => '#F2F4F7',
                    'color' => '#181C25',
                ],
                'recommendation_items' => [
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'goals' => [
                    'background-color' => '#FFFFFF',
                    'color' => '#0A5CFF',
                ],
                'tabs' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#0A5CFF',
                ],
            ],
            'theme-2' => [
                'widget' => [
                    'background-color' => '#000000',
                    'color' => '#FAFAFA',
                ],
                'slider' => [
                    'width' => '360',
                    'background-type' => 'solid',
                    'background-color' => '#000000',
                    'gradient-deg' => 0,
                    'gradient-bg-color-1' => '',
                    'gradient-bg-color-2' => '',
                ],
                'card' => [
                    'border-width' => 'thin',
                    'border-color' => '#5C5C5C',
                    'border-radius' => 6,
                ],
                'component_style' => [
                    'border-color' => '#5C5C5C',
                ],
                'header' => [
                    'font-size' => '24px',
                    'color' => '#FAFAFA',
                ],
                'item' => [
                    'font-size' => '16px',
                    'background-color' => '#000000',
                    'color' => '#f6f5f4',
                ],
                'coupon' => [
                    'background-color' => '#191919',
                ],
                'action' => [
                    'font-size' => '16px',
                    'background-color' => '#CCFF00',
                    'color' => '#000000',
                ],
                'totals' => [
                    'font-size' => '16px',
                    'background-color' => '#191919',
                    'color' => '#CCFF00',
                ],
                'recommendations' => [
                    'background-color' => '#27272a',
                    'color' => '#ffffff',
                ],
                'recommendation_items' => [
                    'background-color' => '#191919',
                    'color' => '#CCFF00',
                ],
                'goals' => [
                    'background-color' => '#020617',
                    'color' => '#CCFF00',
                ],
                'tabs' => [
                    'font-size' => '16px',
                    'background-color' => '#191919',
                    'color' => '#CCFF00',
                ],
            ],
            'theme-3' => [
                'widget' => [
                    'background-color' => '#0A5CFF',
                    'color' => '#FAFAFA',
                ],
                'slider' => [
                    'width' => '360',
                    'background-type' => 'gradient',
                    'background-color' => '',
                    'gradient-deg' => 100,
                    'gradient-bg-color-1' => '#0A5CFF',
                    'gradient-bg-color-2' => '#063799',
                ],
                'card' => [
                    'border-width' => 'none',
                    'border-color' => '#f9f06b',
                    'border-radius' => 6,
                ],
                'component_style' => [
                    'border-color' => '#E8EAED',
                ],
                'header' => [
                    'font-size' => '24px',
                    'color' => '#FAFAFA',
                ],
                'item' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'coupon' => [
                    'background-color' => '#FFFFFF',
                ],
                'action' => [
                    'font-size' => '16px',
                    'background-color' => '#0A5AFA',
                    'color' => '#FAFAFA',
                ],
                'totals' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'recommendations' => [
                    'background-color' => '#F2F4F7',
                    'color' => '#181C25',
                ],
                'recommendation_items' => [
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'goals' => [
                    'background-color' => '#FFFFFF',
                    'color' => '#0A5AFA',
                ],
                'tabs' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#0A5AFA',
                ],
            ],
            'theme-4' => [
                'widget' => [
                    'background-color' => '#2B238A',
                    'color' => '#FAFAFA',
                ],
                'slider' => [
                    'width' => '360',
                    'background-type' => 'gradient',
                    'background-color' => '',
                    'gradient-deg' => 100,
                    'gradient-bg-color-1' => '#4B3CF0',
                    'gradient-bg-color-2' => '#2B238A',
                ],
                'card' => [
                    'border-width' => 'none',
                    'border-color' => '',
                    'border-radius' => 6,
                ],
                'component_style' => [
                    'border-color' => '#E8EAED',
                ],
                'header' => [
                    'font-size' => '24px',
                    'color' => '#FAFAFA',
                ],
                'item' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'coupon' => [
                    'background-color' => '#FFFFFF',
                ],
                'action' => [
                    'font-size' => '16px',
                    'background-color' => '#FD5634',
                    'color' => '#FAFAFA',
                ],
                'totals' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'recommendations' => [
                    'background-color' => '#F2F4F7',
                    'color' => '#181C25',
                ],
                'recommendation_items' => [
                    'background-color' => '#FFFFFF',
                    'color' => '#181C25',
                ],
                'goals' => [
                    'background-color' => '#FFFFFF',
                    'color' => '#FD5634',
                ],
                'tabs' => [
                    'font-size' => '16px',
                    'background-color' => '#FFFFFF',
                    'color' => '#FD5634',
                ],
            ],
        ]);

        if ($theme_key !== '') {
            return $themes[$theme_key];
        }
        return $themes;
    }

    /**
     * Get tabs data.
     *
     * @param string $tab
     * @return array
     */
    public static function getTabs(string $tab = ''): array
    {
        $tabs_info = apply_filters('uwpmc_tabs', [
            'offers' => [
                'title' => __('Offers', 'upsellwp-mini-cart'),
                'load' => Plugin::isUpsellWPActive(),
                'require_plugin_name' => __('Install UpsellWP', 'upsellwp-mini-cart'),
                'plugin_url' => network_admin_url('plugin-install.php?tab=plugin-information&plugin=checkout-upsell-and-order-bumps&TB_iframe=true&width=772&height=512'),
            ],
        ]);

        if ($tab !== '') {
            return $tabs_info[$tab];
        }
        return $tabs_info;
    }

    /**
     * To format the styles.
     *
     * @param array $data
     * @return array
     */
    public static function prepareInlineStyles(array $data): array
    {
        if (isset($data['style'])) {
            $section_styles = [];
            foreach ($data['style'] as $section => $style) {
                $styles = '';
                if ($section == 'slider') {
                    if (!empty($style)) {
                        if (isset($style['width'])) {
                            $styles = $styles . 'width' . ': ' . $style['width'] . 'px; ';
                        }
                        if ($style['background-type'] == 'gradient') {
                            $style['gradient-deg'] = $style['gradient-deg'] ?? 0;
                            $styles = $styles . 'background' . ': linear-gradient(' .
                                $style['gradient-deg'] . 'deg, ' .
                                $style['gradient-bg-color-1'] . ', ' .
                                $style['gradient-bg-color-2']
                                . ');';
                        } else {
                            $styles = $styles . 'background-color' . ': ' . $style['background-color'] . ';';
                        }
                    }
                } else {
                    foreach ($style as $property_name => $value) {
                        if (!empty($value)) {
                            if ($property_name == 'border-width') {
                                $styles = $styles . 'border' . ': ' . (($value != 'none') ? $value . ' solid ' : $value) . '; ';
                            } else {
                                $styles = $styles . $property_name . ': ' . $value
                                    . (($property_name == 'border-radius') ? 'px; ' : '; ');
                            }
                        }
                    }
                }
                $section_styles[$section] = $styles;
            }
            $data['style'] = $section_styles;
        }
        if (!empty($data['advanced']['banner']['list'])) {
            foreach ($data['advanced']['banner']['list'] as $banner_key => $banner_content) {
                $style = '';
                if (!empty($banner_content)) {
                    foreach ($banner_content as $property_name => $value) {
                        if (!empty($value) && $property_name != 'content') {
                            $style = $style . $property_name . ': ' . $value . '; ';
                        }
                    }
                }
                $data['advanced']['banner']['list'][$banner_key]['style'] = $style;
            }
        }
        return $data;
    }

    /**
     * To prepare data.
     *
     * @param $data
     * @return mixed|null
     */
    public static function prepareData($data)
    {
        if (!empty($data)) {
            $data = self::prepareInlineStyles($data);
            $advanced_data = $data['advanced'] ?? [];

            $advanced_data['banner'] = $advanced_data['banner'] ?? [];
            $advanced_data['goals'] = $advanced_data['goals'] ?? [];
            $advanced_data['recommendations'] = $advanced_data['recommendations'] ?? [];

            // to set tabs data
            $advanced_data['tabs'] = self::prepareTabsData($advanced_data['tabs'] ?? []);

            // to set goals data
            if (!empty($advanced_data['goals']) && !empty($advanced_data['goals']['enable'])) {
                if (!empty($advanced_data['goals']['list']['free_shipping'])) {
                    $advanced_data['goals']['list']['free_shipping'] = Shipping::getFreeShippingData();
                }
            }

            // to set recommended items data
            if (!empty($advanced_data['recommendations']) && !empty($advanced_data['recommendations']['enable'])) {
                $advanced_data['recommendations']['ids'] = MiniCart::getRelatedProductIds();
            } else {
                MiniCart::deleteSessionData();
            }
            $data['advanced'] = $advanced_data;

            // to load cart page url
            if (!empty($data['data']['actions']['cart'])) {
                $data['data']['actions']['cart']['url'] = wc_get_cart_url();
            }

            // to load checkout page url
            if (!empty($data['data']['actions']['checkout'])) {
                $data['data']['actions']['checkout']['url'] = wc_get_checkout_url();
            }
        }
        return apply_filters('uwpmc_processed_template_data', $data);
    }

    /**
     * Prepare tabs data
     *
     * @param array $tabs_data
     * @return array
     */
    public static function prepareTabsData(array $tabs_data): array
    {
        $default_tabs = [
            'cart' => [
                'title' => __('Cart', 'upsellwp-mini-cart'),
                'enable' => '1',
                'load' => true,
            ],
        ];
        if (is_admin()) {
            if (empty($tabs_data)) {
                $tabs_data = array_merge($default_tabs, self::getTabs());
                $tabs_data['cart']['enable'] = 0;
            } else {
                $enabled_tabs = 0;
                foreach (self::getTabs() as $tab_slug => $tab) {
                    $tabs_data[$tab_slug] = array_merge($tabs_data[$tab_slug] ?? [], self::getTabs($tab_slug));
                    if (!empty($tabs_data[$tab_slug]['enable'])) {
                        $enabled_tabs += 1;
                    }
                    if (empty($tabs_data[$tab_slug]['load'])) {
                        unset($tabs_data[$tab_slug]);
                    }
                }
                if (empty($enabled_tabs)) {
                    $tabs_data = array_merge($tabs_data, $default_tabs);
                    $tabs_data['cart']['enable'] = 0;
                }
            }
        } else if (!empty($tabs_data)) {
            foreach ($tabs_data as $tab_slug => $tab) {
                $tabs_data[$tab_slug] = array_merge($tab, self::getTabs($tab_slug));
                if (empty($tabs_data[$tab_slug]['load']) || empty($tabs_data[$tab_slug]['enable'])) {
                    unset($tabs_data[$tab_slug]);
                }
            }
        }
        return !empty($tabs_data) ? array_merge($default_tabs, $tabs_data) : [];
    }
}
