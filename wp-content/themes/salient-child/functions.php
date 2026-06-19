<?php 

// Intercept and strip virtual /in prefix for India region before WordPress routes the request
if ( ! defined( 'ULB_IS_INDIA_REGION' ) ) {
    $is_in = false;
    if ( isset( $_SERVER['REQUEST_URI'] ) ) {
        $uri = $_SERVER['REQUEST_URI'];
        if ( preg_match( '/^\/in(\/|\?|$)/', $uri ) ) {
            $is_in = true;
            $rewritten = preg_replace( '/^\/in/', '', $uri );
            if ( empty( $rewritten ) || $rewritten[0] !== '/' ) {
                $rewritten = '/' . $rewritten;
            }
            $_SERVER['REQUEST_URI'] = $rewritten;
        }
    }

    // Fallback 1: Check HTTP Referer (highly reliable for AJAX, add-to-cart, and fragment refreshes)
    if ( ! $is_in && isset( $_SERVER['HTTP_REFERER'] ) ) {
        $referer_path = parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_PATH );
        if ( $referer_path && preg_match( '/^\/in(\/|\?|$)/', $referer_path ) ) {
            $is_in = true;
        }
    }

    // Fallback 2: Check cookie (for AJAX/REST requests only to avoid affecting direct URL browsing)
    $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || isset( $_GET['wc-ajax'] );
    if ( ! $is_in && $is_ajax && isset( $_COOKIE['ulb_region'] ) && $_COOKIE['ulb_region'] === 'IN' ) {
        $is_in = true;
    }

    define( 'ULB_IS_INDIA_REGION', $is_in );
}

/**
 * Manage regional cookie for AJAX request fallback detection.
 */
add_action( 'init', 'ulb_set_region_cookie' );
function ulb_set_region_cookie() {
    if ( ! is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ! isset( $_GET['wc-ajax'] ) ) {
        if ( ulb_is_india_region() ) {
            if ( ! isset( $_COOKIE['ulb_region'] ) || $_COOKIE['ulb_region'] !== 'IN' ) {
                setcookie( 'ulb_region', 'IN', time() + ( 30 * DAY_IN_SECONDS ), '/' );
            }
        } else {
            if ( isset( $_COOKIE['ulb_region'] ) ) {
                setcookie( 'ulb_region', '', time() - 3600, '/' );
            }
        }
    }
}


add_action( 'wp_enqueue_scripts', 'salient_child_enqueue_styles', 100);

function salient_child_enqueue_styles() {
		
		$nectar_theme_version = nectar_get_theme_version();
		wp_enqueue_style( 'salient-child-style', get_stylesheet_directory_uri() . '/style.css', '', $nectar_theme_version );
		
    if ( is_rtl() ) {
   		wp_enqueue_style(  'salient-rtl',  get_template_directory_uri(). '/rtl.css', array(), '1', 'screen' );
		}

		// Enqueue the GeoIP redirect script
		wp_enqueue_script( 'ulb-geoip-redirect', get_stylesheet_directory_uri() . '/js/geoip-redirect.js', array('jquery'), '1.0.0', true );
		wp_localize_script( 'ulb-geoip-redirect', 'ulb_geo_opt', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) );
}

/**
 * Custom AJAX hook for Geolocation
 */
add_action( 'wp_ajax_ulb_geolocate_user', 'ulb_geolocate_user_callback' );
add_action( 'wp_ajax_nopriv_ulb_geolocate_user', 'ulb_geolocate_user_callback' );

function ulb_geolocate_user_callback() {
    $country = '';
    
    // Local testing override: Force 'IN' (India) on local servers to allow direct verification
    if ( isset( $_SERVER['HTTP_HOST'] ) && ( $_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || str_ends_with( $_SERVER['HTTP_HOST'], '.local' ) || str_contains( $_SERVER['HTTP_HOST'], 'local' ) ) ) {
        $country = 'IN';
    } elseif ( class_exists( 'WC_Geolocation' ) ) {
        $location = WC_Geolocation::geolocate_ip();
        $country = isset( $location['country'] ) ? $location['country'] : '';
    }
    
    wp_send_json_success( array( 'country' => $country ) );
}

/**
 * Inject fast inline redirection in wp_head if country cookie is already set to IN
 */
add_action( 'wp_head', 'ulb_fast_inline_geoip_redirect', 1 );

function ulb_fast_inline_geoip_redirect() {
    ?>
    <script type="text/javascript">
    (function() {
        var path = window.location.pathname;
        if (!path.match(/^\/in(\/|$)/)) {
            var choice = document.cookie.match(new RegExp('(^| )ulb_redirect_choice=([^;]+)'));
            if (choice) {
                if (choice[2] === 'stay') {
                    return;
                }
                if (choice[2] === 'switch') {
                    window.location.href = window.location.origin + '/in' + path + window.location.search + window.location.hash;
                    return;
                }
            }
        }
    })();
    </script>
    <?php
}

/**
 * --------------------------------------------------------------------------
 * Phase 2: Programmatic Multi-Currency, Payment, and Shipping Routing
 * --------------------------------------------------------------------------
 */

/**
 * Helper: Detect if visitor is in the India region (/in/) based on the request URL.
 */
function ulb_is_india_region() {
    return defined( 'ULB_IS_INDIA_REGION' ) && ULB_IS_INDIA_REGION;
}

/**
 * Set WooCommerce currency to INR when in the India region.
 */
add_filter( 'woocommerce_currency', 'ulb_set_india_currency', 999 );
function ulb_set_india_currency( $currency ) {
    if ( ulb_is_india_region() ) {
        return 'INR';
    }
    return $currency;
}

/**
 * Fetch and cache the AED to INR exchange rate via API (cached for 12 hours).
 */
function ulb_get_aed_to_inr_rate() {
    $rate = get_transient( 'ulb_aed_to_inr_rate' );
    if ( $rate === false ) {
        $response = wp_remote_get( 'https://open.er-api.com/v6/latest/AED' );
        if ( ! is_wp_error( $response ) ) {
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );
            if ( isset( $data['rates']['INR'] ) ) {
                $rate = floatval( $data['rates']['INR'] );
                set_transient( 'ulb_aed_to_inr_rate', $rate, 12 * HOUR_IN_SECONDS );
            }
        }
        
        // Fallback exchange rate if API is unreachable
        if ( $rate === false ) {
            $rate = 22.7;
        }
    }
    return $rate;
}

/**
 * Add custom INR price fields to WooCommerce simple products.
 */
add_action( 'woocommerce_product_options_general_product_data', 'ulb_add_custom_inr_fields' );
function ulb_add_custom_inr_fields() {
    echo '<div class="options_group">';
    
    woocommerce_wp_text_input( array(
        'id'          => '_inr_regular_price',
        'label'       => __( 'Regular Price (INR)', 'woocommerce' ),
        'placeholder' => '',
        'description' => __( 'Custom regular price for India store in INR (₹). Leave blank to convert dynamically.', 'woocommerce' ),
        'desc_tip'    => 'true',
        'type'        => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        )
    ) );

    woocommerce_wp_text_input( array(
        'id'          => '_inr_sale_price',
        'label'       => __( 'Sale Price (INR)', 'woocommerce' ),
        'placeholder' => '',
        'description' => __( 'Custom sale price for India store in INR (₹). Leave blank to convert dynamically.', 'woocommerce' ),
        'desc_tip'    => 'true',
        'type'        => 'number',
        'custom_attributes' => array(
            'step' => 'any',
            'min'  => '0'
        )
    ) );
    
    echo '</div>';
}

/**
 * Save custom INR price fields for simple products.
 */
add_action( 'woocommerce_process_product_meta', 'ulb_save_custom_inr_fields' );
function ulb_save_custom_inr_fields( $post_id ) {
    $inr_regular = isset( $_POST['_inr_regular_price'] ) ? sanitize_text_field( $_POST['_inr_regular_price'] ) : '';
    $inr_sale    = isset( $_POST['_inr_sale_price'] ) ? sanitize_text_field( $_POST['_inr_sale_price'] ) : '';

    if ( $inr_regular !== '' ) {
        update_post_meta( $post_id, '_inr_regular_price', $inr_regular );
    } else {
        delete_post_meta( $post_id, '_inr_regular_price' );
    }

    if ( $inr_sale !== '' ) {
        update_post_meta( $post_id, '_inr_sale_price', $inr_sale );
    } else {
        delete_post_meta( $post_id, '_inr_sale_price' );
    }
}

/**
 * Add custom INR price fields to variations.
 */
add_action( 'woocommerce_product_after_variable_attributes', 'ulb_add_custom_inr_variation_fields', 10, 3 );
function ulb_add_custom_inr_variation_fields( $loop, $variation_data, $variation ) {
    $variation_id = $variation->ID;
    $inr_regular = get_post_meta( $variation_id, '_inr_regular_price', true );
    $inr_sale    = get_post_meta( $variation_id, '_inr_sale_price', true );
    ?>
    <div class="variable_custom_inr_prices" style="clear: both; margin-top: 10px;">
        <p class="form-row form-row-first">
            <label><?php _e( 'Regular Price (INR)', 'woocommerce' ); ?></label>
            <input type="number" step="any" min="0" name="_inr_variation_regular_price[<?php echo $loop; ?>]" value="<?php echo esc_attr( $inr_regular ); ?>" />
            <span class="description" style="font-size: 11px; color: #777;"><?php _e( 'Leave blank to convert AED dynamically.', 'woocommerce' ); ?></span>
        </p>
        <p class="form-row form-row-last">
            <label><?php _e( 'Sale Price (INR)', 'woocommerce' ); ?></label>
            <input type="number" step="any" min="0" name="_inr_variation_sale_price[<?php echo $loop; ?>]" value="<?php echo esc_attr( $inr_sale ); ?>" />
            <span class="description" style="font-size: 11px; color: #777;"><?php _e( 'Leave blank to convert AED dynamically.', 'woocommerce' ); ?></span>
        </p>
    </div>
    <?php
}

/**
 * Save custom INR price fields for variations.
 */
add_action( 'woocommerce_save_product_variation', 'ulb_save_custom_inr_variation_fields', 10, 2 );
function ulb_save_custom_inr_variation_fields( $variation_id, $i ) {
    $inr_regular = isset( $_POST['_inr_variation_regular_price'][$i] ) ? sanitize_text_field( $_POST['_inr_variation_regular_price'][$i] ) : '';
    $inr_sale    = isset( $_POST['_inr_variation_sale_price'][$i] ) ? sanitize_text_field( $_POST['_inr_variation_sale_price'][$i] ) : '';

    if ( $inr_regular !== '' ) {
        update_post_meta( $variation_id, '_inr_regular_price', $inr_regular );
    } else {
        delete_post_meta( $variation_id, '_inr_regular_price' );
    }

    if ( $inr_sale !== '' ) {
        update_post_meta( $variation_id, '_inr_sale_price', $inr_sale );
    } else {
        delete_post_meta( $variation_id, '_inr_sale_price' );
    }
}

/**
 * Filter individual product prices to display converted or custom INR amounts.
 */
add_filter( 'woocommerce_product_get_price', 'ulb_filter_active_price_inr', 999, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'ulb_filter_active_price_inr', 999, 2 );

function ulb_filter_active_price_inr( $price, $product ) {
    if ( ! ulb_is_india_region() ) {
        return $price;
    }

    $inr_reg  = $product->get_meta( '_inr_regular_price', true );
    $inr_sale = $product->get_meta( '_inr_sale_price', true );

    // If a custom sale price is set and the product is on sale
    if ( $inr_sale !== '' && floatval( $inr_sale ) > 0 ) {
        if ( $product->is_on_sale() || $inr_reg === '' || floatval( $inr_sale ) < floatval( $inr_reg ) ) {
            return $inr_sale;
        }
    }

    // Else if a custom regular price is set
    if ( $inr_reg !== '' && floatval( $inr_reg ) > 0 ) {
        return $inr_reg;
    }

    // Fallback: Dynamic conversion of the active AED price
    if ( empty( $price ) ) {
        return $price;
    }
    $rate = ulb_get_aed_to_inr_rate();
    return round( floatval( $price ) * $rate );
}

add_filter( 'woocommerce_product_get_regular_price', 'ulb_filter_regular_price_inr', 999, 2 );
add_filter( 'woocommerce_product_variation_get_regular_price', 'ulb_filter_regular_price_inr', 999, 2 );

function ulb_filter_regular_price_inr( $price, $product ) {
    if ( ! ulb_is_india_region() ) {
        return $price;
    }

    $inr_reg = $product->get_meta( '_inr_regular_price', true );
    if ( $inr_reg !== '' && floatval( $inr_reg ) > 0 ) {
        return $inr_reg;
    }

    if ( empty( $price ) ) {
        return $price;
    }
    $rate = ulb_get_aed_to_inr_rate();
    return round( floatval( $price ) * $rate );
}

add_filter( 'woocommerce_product_get_sale_price', 'ulb_filter_sale_price_inr', 999, 2 );
add_filter( 'woocommerce_product_variation_get_sale_price', 'ulb_filter_sale_price_inr', 999, 2 );

function ulb_filter_sale_price_inr( $price, $product ) {
    if ( ! ulb_is_india_region() ) {
        return $price;
    }

    $inr_sale = $product->get_meta( '_inr_sale_price', true );
    if ( $inr_sale !== '' && floatval( $inr_sale ) > 0 ) {
        return $inr_sale;
    }

    if ( empty( $price ) ) {
        return $price;
    }
    $rate = ulb_get_aed_to_inr_rate();
    return round( floatval( $price ) * $rate );
}

/**
 * Filter variation price ranges array for variable products to reflect custom/converted INR prices.
 */
add_filter( 'woocommerce_variation_prices', 'ulb_convert_variation_prices_array', 999, 3 );
function ulb_convert_variation_prices_array( $prices_array, $product, $for_display ) {
    if ( ! ulb_is_india_region() ) {
        return $prices_array;
    }

    foreach ( $prices_array as $key => $values ) {
        foreach ( $values as $variation_id => $price ) {
            $variation = wc_get_product( $variation_id );
            if ( ! $variation ) {
                continue;
            }

            $custom_price = '';
            if ( $key === 'price' ) {
                $custom_price = ulb_filter_active_price_inr( $price, $variation );
            } elseif ( $key === 'regular_price' ) {
                $custom_price = ulb_filter_regular_price_inr( $price, $variation );
            } elseif ( $key === 'sale_price' ) {
                $custom_price = ulb_filter_sale_price_inr( $price, $variation );
            }

            if ( $custom_price !== '' && $custom_price !== 0 && $custom_price !== '0' ) {
                $prices_array[$key][$variation_id] = $custom_price;
            } else {
                $rate = ulb_get_aed_to_inr_rate();
                $prices_array[$key][$variation_id] = round( floatval( $price ) * $rate );
            }
        }
    }
    return $prices_array;
}

/**
 * Filter available payment gateways by region.
 */
add_filter( 'woocommerce_available_payment_gateways', 'ulb_filter_payment_gateways_by_region', 999 );
function ulb_filter_payment_gateways_by_region( $available_gateways ) {
    $is_india = ulb_is_india_region();
    
    foreach ( $available_gateways as $gateway_id => $gateway ) {
        if ( $is_india ) {
            // India: Hide Paymob, keep Razorpay & COD
            if ( strpos( $gateway_id, 'paymob' ) !== false ) {
                unset( $available_gateways[$gateway_id] );
            }
        } else {
            // UAE/Global: Hide Razorpay, keep Paymob
            if ( strpos( $gateway_id, 'razorpay' ) !== false ) {
                unset( $available_gateways[$gateway_id] );
            }
        }
    }
    return $available_gateways;
}

/**
 * Filter and convert shipping rates by region.
 */
add_filter( 'woocommerce_package_rates', 'ulb_filter_shipping_rates_by_region', 999, 2 );
function ulb_filter_shipping_rates_by_region( $rates, $package ) {
    $is_india = ulb_is_india_region();
    
    if ( $is_india ) {
        $rate_conversion = ulb_get_aed_to_inr_rate();
        foreach ( $rates as $rate_id => $rate ) {
            // Convert standard shipping costs from AED to INR
            if ( isset( $rates[$rate_id]->cost ) ) {
                $rates[$rate_id]->cost = round( floatval( $rate->cost ) * $rate_conversion );
            }
            
            // Convert shipping taxes from AED to INR if applicable
            $taxes = $rate->taxes;
            if ( ! empty( $taxes ) ) {
                foreach ( $taxes as $key => $tax ) {
                    $taxes[$key] = round( floatval( $tax ) * $rate_conversion );
                }
                $rates[$rate_id]->taxes = $taxes;
            }
        }
    } else {
        // UAE/Global: Hide India shipping methods (e.g. Shiprocket)
        foreach ( $rates as $rate_id => $rate ) {
            if ( strpos( $rate_id, 'shiprocket' ) !== false ) {
                unset( $rates[$rate_id] );
            }
        }
    }
    return $rates;
}

/**
 * Filter home URL to preserve the virtual /in prefix for internal links.
 */
add_filter( 'home_url', 'ulb_filter_home_url', 9999, 4 );
function ulb_filter_home_url( $url, $path, $orig_scheme, $blog_id ) {
    if ( ulb_is_india_region() ) {
        if ( empty( $path ) || ! preg_match( '/^(wp-admin|wp-login|wp-content|wp-includes|wp-json|admin-ajax)/', ltrim( $path, '/' ) ) ) {
            $parsed = parse_url( $url );
            if ( isset( $parsed['host'] ) ) {
                $host = $parsed['host'];
                $url_path = isset( $parsed['path'] ) ? $parsed['path'] : '';
                if ( ! preg_match( '/^\/in(\/|$)/', $url_path ) ) {
                    $port_suffix = isset( $parsed['port'] ) ? ':' . $parsed['port'] : '';
                    $search_target = $host . $port_suffix;
                    $url = str_replace( $search_target, $search_target . '/in', $url );
                }
            }
        }
    }
    return $url;
}

/**
 * Disable canonical redirection for virtual /in/ routing to prevent redirect loops.
 */
add_filter( 'redirect_canonical', 'ulb_disable_canonical_redirect_for_india', 9999, 2 );
function ulb_disable_canonical_redirect_for_india( $redirect_url, $requested_url ) {
    if ( ulb_is_india_region() ) {
        return false;
    }
    return $redirect_url;
}

/**
 * Inject Country Selector Modal HTML into the footer of default region pages.
 */
add_action( 'wp_footer', 'ulb_inject_country_modal' );
function ulb_inject_country_modal() {
    if ( ! ulb_is_india_region() ) {
        ?>
        <div id="ulb-country-modal" class="ulb-modal-overlay">
            <div class="ulb-modal-card">
                <button class="ulb-modal-close" id="ulb-modal-close-btn" aria-label="Close">&times;</button>
                <div class="ulb-modal-header">
                    <span class="ulb-modal-flag">🇮🇳</span>
                    <h3>Welcome to USE-LESS Beauty</h3>
                </div>
                <div class="ulb-modal-body">
                    <p>We noticed you are visiting from India. Would you like to switch to our India Store to view pricing in INR (₹) and use domestic shipping?</p>
                </div>
                <div class="ulb-modal-footer">
                    <button id="ulb-btn-switch" class="ulb-btn ulb-btn-primary">Switch to India Store</button>
                    <button id="ulb-btn-stay" class="ulb-btn ulb-btn-secondary">Stay on Global Store</button>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Add Region Restriction field to WooCommerce Coupon options.
 */
add_action( 'woocommerce_coupon_options', 'ulb_add_coupon_region_restriction_field' );
function ulb_add_coupon_region_restriction_field() {
    woocommerce_wp_select( array(
        'id'          => '_coupon_region_restriction',
        'label'       => __( 'Region Restriction', 'woocommerce' ),
        'options'     => array(
            'all'    => __( 'All Regions', 'woocommerce' ),
            'global' => __( 'Global / UAE Store Only', 'woocommerce' ),
            'india'  => __( 'India Store Only', 'woocommerce' ),
        ),
        'description' => __( 'Choose which store region this coupon can be used in.', 'woocommerce' ),
        'desc_tip'    => true,
    ) );
}

/**
 * Save Region Restriction field when a coupon is saved.
 */
add_action( 'woocommerce_coupon_options_save', 'ulb_save_coupon_region_restriction_field', 10, 2 );
function ulb_save_coupon_region_restriction_field( $coupon_id, $coupon ) {
    $restriction = isset( $_POST['_coupon_region_restriction'] ) ? sanitize_text_field( $_POST['_coupon_region_restriction'] ) : 'all';
    $coupon->update_meta_data( '_coupon_region_restriction', $restriction );
    $coupon->save();
}

/**
 * Validate coupon region restriction at checkout.
 */
add_filter( 'woocommerce_coupon_is_valid', 'ulb_validate_coupon_region_restriction', 10, 3 );
function ulb_validate_coupon_region_restriction( $is_valid, $coupon, $discount ) {
    if ( ! $is_valid ) {
        return $is_valid;
    }

    $restriction = $coupon->get_meta( '_coupon_region_restriction', true );
    if ( empty( $restriction ) || $restriction === 'all' ) {
        return $is_valid;
    }

    $is_india = ulb_is_india_region();

    if ( $restriction === 'india' && ! $is_india ) {
        throw new Exception( __( 'This coupon is only valid on the India Store.', 'woocommerce' ) );
    }

    if ( $restriction === 'global' && $is_india ) {
        throw new Exception( __( 'This coupon is only valid on the Global Store.', 'woocommerce' ) );
    }

    return $is_valid;
}

/**
 * Save region metadata to the order upon checkout.
 */
add_action( 'woocommerce_checkout_create_order', 'ulb_save_order_region_metadata', 10, 2 );
function ulb_save_order_region_metadata( $order, $data ) {
    if ( ulb_is_india_region() ) {
        $order->update_meta_data( '_ulb_region', 'IN' );
        $order->update_meta_data( 'ulb_region', 'IN' );
        $order->update_meta_data( 'store_region', 'India' );
    } else {
        $order->update_meta_data( '_ulb_region', 'GL' );
        $order->update_meta_data( 'ulb_region', 'GL' );
        $order->update_meta_data( 'store_region', 'Global / UAE' );
    }
}

/**
 * Identify and push region properties to Klaviyo profile.
 */
add_action( 'wp_footer', 'ulb_klaviyo_frontend_identify', 999 );
function ulb_klaviyo_frontend_identify() {
    $region = ulb_is_india_region() ? 'India' : 'Global';
    $region_code = ulb_is_india_region() ? 'IN' : 'GL';
    ?>
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var klaviyo = window.klaviyo || [];
        klaviyo.push(['identify', {
            'Store Region': '<?php echo esc_js($region); ?>',
            'Store Region Code': '<?php echo esc_js($region_code); ?>'
        }]);
    });
    </script>
    <?php
}

/**
 * Add Region property to Klaviyo Started Checkout event.
 */
add_filter( 'kl_started_checkout', 'ulb_klaviyo_started_checkout_region', 10, 2 );
function ulb_klaviyo_started_checkout_region( $event_data, $cart ) {
    $event_data['Store Region'] = ulb_is_india_region() ? 'India' : 'Global';
    $event_data['Store Region Code'] = ulb_is_india_region() ? 'IN' : 'GL';
    return $event_data;
}

/**
 * Add Region property to Klaviyo Added to Cart event.
 */
add_filter( 'kl_added_to_cart', 'ulb_klaviyo_added_to_cart_region', 10, 4 );
function ulb_klaviyo_added_to_cart_region( $added_to_cart, $added_product, $quantity, $wck_cart ) {
    $added_to_cart['Store Region'] = ulb_is_india_region() ? 'India' : 'Global';
    $added_to_cart['Store Region Code'] = ulb_is_india_region() ? 'IN' : 'GL';
    return $added_to_cart;
}

/**
 * Add Razorpay columns to WooCommerce orders table.
 */
add_filter( 'manage_edit-shop_order_columns', 'ulb_razorpay_order_list_columns', 20 );
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'ulb_razorpay_order_list_columns', 20 );

function ulb_razorpay_order_list_columns( $columns ) {
    $columns['payment_method_title'] = __( 'Payment Method', 'woocommerce' );
    $columns['razorpay_order_id']   = __( 'Razorpay Order ID', 'woocommerce' );
    $columns['razorpay_payment_id'] = __( 'Razorpay Payment ID', 'woocommerce' );
    return $columns;
}

/**
 * Output data for the custom Razorpay and Payment Method columns.
 */
add_action( 'manage_shop_order_posts_custom_column', 'ulb_razorpay_order_columns_data', 20, 2 );
add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'ulb_razorpay_order_columns_data', 20, 2 );

function ulb_razorpay_order_columns_data( $colName, $orderId ) {
    $order = wc_get_order( $orderId );
    if ( ! $order ) {
        return;
    }

    if ( $colName === 'payment_method_title' ) {
        $method_title = $order->get_payment_method_title();
        echo ! empty( $method_title ) ? esc_html( $method_title ) : '—';
    }

    if ( $colName === 'razorpay_order_id' ) {
        $razorpayOrderId = $order->get_meta( '_razorpay_order_id' );
        if ( empty( $razorpayOrderId ) ) {
            $razorpayOrderId = $order->get_meta( 'razorpay_order_id' );
        }
        echo ! empty( $razorpayOrderId ) ? esc_html( $razorpayOrderId ) : '—';
    }

    if ( $colName === 'razorpay_payment_id' ) {
        $razorpayPaymentId = $order->get_meta( '_razorpay_payment_id' );
        if ( empty( $razorpayPaymentId ) ) {
            $razorpayPaymentId = $order->get_meta( 'razorpay_payment_id' );
        }
        echo ! empty( $razorpayPaymentId ) ? esc_html( $razorpayPaymentId ) : '—';
    }
}

/**
 * Convert INR order values to base currency (AED) before saving to WooCommerce Analytics database.
 */
add_filter( 'woocommerce_analytics_update_order_stats_data', 'ulb_convert_analytics_order_stats_to_base_currency', 10, 2 );
function ulb_convert_analytics_order_stats_to_base_currency( $data, $order ) {
    if ( ! $order ) {
        return $data;
    }

    $order_currency = $order->get_currency();

    if ( 'INR' === $order_currency ) {
        $rate = ulb_get_aed_to_inr_rate();
        if ( $rate > 0 ) {
            $monetary_fields = array(
                'total_sales',
                'tax_total',
                'shipping_total',
                'net_total',
            );

            foreach ( $monetary_fields as $field ) {
                if ( isset( $data[$field] ) ) {
                    $data[$field] = round( floatval( $data[$field] ) / $rate, 2 );
                }
            }
        }
    }
    return $data;
}

/**
 * Convert INR product revenue values to base currency (AED) after saving to WooCommerce Analytics product lookup database.
 */
add_action( 'woocommerce_analytics_update_product', 'ulb_convert_analytics_product_lookup_to_base_currency', 10, 2 );
function ulb_convert_analytics_product_lookup_to_base_currency( $order_item_id, $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }

    if ( 'INR' === $order->get_currency() ) {
        $rate = ulb_get_aed_to_inr_rate();
        if ( $rate > 0 ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'wc_order_product_lookup';
            
            $wpdb->query( $wpdb->prepare(
                "UPDATE {$table_name} 
                 SET product_net_revenue = ROUND(product_net_revenue / %f, 2),
                     coupon_amount = ROUND(coupon_amount / %f, 2),
                     tax_amount = ROUND(tax_amount / %f, 2),
                     shipping_amount = ROUND(shipping_amount / %f, 2),
                     shipping_tax_amount = ROUND(shipping_tax_amount / %f, 2),
                     product_gross_revenue = ROUND(product_gross_revenue / %f, 2)
                 WHERE order_item_id = %d",
                $rate, $rate, $rate, $rate, $rate, $rate, $order_item_id
            ) );
        }
    }
}

/**
 * One-time backfill of regional metadata and conversion of existing analytics stats for INR orders.
 */
add_action( 'admin_init', 'ulb_backfill_order_regions' );
function ulb_backfill_order_regions() {
    if ( get_option( 'ulb_region_backfill_done_v2' ) ) {
        return;
    }

    global $wpdb;

    // 1. Backfill order meta keys
    $orders = wc_get_orders( array(
        'limit'        => -1,
        'meta_key'     => '_ulb_region',
        'meta_compare' => 'EXISTS',
    ) );

    foreach ( $orders as $order ) {
        $region = $order->get_meta( '_ulb_region' );
        if ( ! empty( $region ) ) {
            $order->update_meta_data( 'ulb_region', $region );
            $order->update_meta_data( 'store_region', ( $region === 'IN' ) ? 'India' : 'Global / UAE' );
            $order->save();
        }
    }

    // 2. Convert existing INR orders in wc_order_stats and wc_order_product_lookup to AED
    $inr_orders = wc_get_orders( array(
        'limit'    => -1,
        'currency' => 'INR',
    ) );

    $rate = ulb_get_aed_to_inr_rate();
    if ( $rate > 0 ) {
        foreach ( $inr_orders as $order ) {
            $order_id = $order->get_id();

            // Update wc_order_stats
            $wpdb->query( $wpdb->prepare(
                "UPDATE {$wpdb->prefix}wc_order_stats 
                 SET total_sales = ROUND(total_sales / %f, 2),
                     tax_total = ROUND(tax_total / %f, 2),
                     shipping_total = ROUND(shipping_total / %f, 2),
                     net_total = ROUND(net_total / %f, 2)
                 WHERE order_id = %d AND total_sales > 100",
                $rate, $rate, $rate, $rate, $order_id
            ) );

            // Update wc_order_product_lookup
            $wpdb->query( $wpdb->prepare(
                "UPDATE {$wpdb->prefix}wc_order_product_lookup 
                 SET product_net_revenue = ROUND(product_net_revenue / %f, 2),
                     coupon_amount = ROUND(coupon_amount / %f, 2),
                     tax_amount = ROUND(tax_amount / %f, 2),
                     shipping_amount = ROUND(shipping_amount / %f, 2),
                     shipping_tax_amount = ROUND(shipping_tax_amount / %f, 2),
                     product_gross_revenue = ROUND(product_gross_revenue / %f, 2)
                 WHERE order_id = %d AND product_gross_revenue > 100",
                $rate, $rate, $rate, $rate, $rate, $rate, $order_id
            ) );
        }
    }

    // Force clear analytics cache
    if ( class_exists( 'Automattic\WooCommerce\Admin\API\Reports\Cache' ) ) {
        \Automattic\WooCommerce\Admin\API\Reports\Cache::invalidate();
    }

    update_option( 'ulb_region_backfill_done_v2', '1' );
}

/**
 * Add a custom dropdown filter for Region (India vs Global) to the WooCommerce Orders list.
 */
add_action( 'woocommerce_order_list_table_restrict_manage_orders', 'ulb_add_region_filter_dropdown', 25, 2 );
add_action( 'restrict_manage_posts', 'ulb_add_region_filter_dropdown', 25, 2 );

function ulb_add_region_filter_dropdown( $post_type = '', $which = '' ) {
    if ( ! is_admin() ) {
        return;
    }

    global $pagenow, $typenow;
    $is_shop_order = ( 'shop_order' === $post_type || 'shop_order' === $typenow );
    $is_hpos_page = ( isset( $_GET['page'] ) && 'wc-orders' === $_GET['page'] );

    if ( ! $is_shop_order && ! $is_hpos_page ) {
        return;
    }

    $current = isset( $_GET['filter_ulb_region'] ) ? sanitize_text_field( $_GET['filter_ulb_region'] ) : '';

    $regions = array(
        'IN' => __( 'India Store Only', 'woocommerce' ),
        'GL' => __( 'Global / UAE Store Only', 'woocommerce' ),
    );

    echo '<select name="filter_ulb_region" id="filter_ulb_region">';
    echo '<option value="">' . __( 'Filter by Store Region', 'woocommerce' ) . '</option>';
    
    foreach ( $regions as $value => $label ) {
        printf( 
            '<option value="%s" %s>%s</option>', 
            esc_attr( $value ), 
            selected( $current, $value, false ), 
            esc_html( $label ) 
        );
    }
    echo '</select>';
}

/**
 * Filter orders list by the selected region (Legacy Post-based Storage).
 */
add_filter( 'request', 'ulb_filter_orders_by_region_legacy' );
function ulb_filter_orders_by_region_legacy( $vars ) {
    global $pagenow;

    if ( is_admin() && $pagenow === 'edit.php' && isset( $vars['post_type'] ) && 'shop_order' === $vars['post_type'] && ! empty( $_GET['filter_ulb_region'] ) ) {
        $vars['meta_query'][] = array(
            'key'     => '_ulb_region',
            'value'   => sanitize_text_field( $_GET['filter_ulb_region'] ),
            'compare' => '=',
        );
    }
    return $vars;
}

/**
 * Filter orders list by the selected region (High-Performance Order Storage / HPOS).
 */
add_filter( 'woocommerce_order_list_table_prepare_items_query_args', 'ulb_filter_orders_by_region_hpos' );
function ulb_filter_orders_by_region_hpos( $query_args ) {
    if ( ! empty( $_GET['filter_ulb_region'] ) ) {
        $query_args['meta_query'][] = array(
            'key'     => '_ulb_region',
            'value'   => sanitize_text_field( $_GET['filter_ulb_region'] ),
            'compare' => '=',
        );
    }
    return $query_args;
}




