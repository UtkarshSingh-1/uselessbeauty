<?php 

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
    if ( isset( $_SERVER['HTTP_HOST'] ) && ( $_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' ) ) {
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
            var match = document.cookie.match(new RegExp('(^| )ulb_visitor_country=([^;]+)'));
            if (match && match[2] === 'IN') {
                window.location.href = window.location.origin + '/in' + path + window.location.search + window.location.hash;
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
    static $is_in = null;
    if ( $is_in !== null ) {
        return $is_in;
    }
    if ( isset( $_SERVER['REQUEST_URI'] ) ) {
        $uri = $_SERVER['REQUEST_URI'];
        if ( preg_match( '/^\/in(\/|\?|$)/', $uri ) ) {
            $is_in = true;
            return true;
        }
    }
    $is_in = false;
    return false;
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
 * Filter individual product prices to display converted INR amounts.
 */
add_filter( 'woocommerce_product_get_price', 'ulb_convert_price_to_inr', 999, 2 );
add_filter( 'woocommerce_product_get_regular_price', 'ulb_convert_price_to_inr', 999, 2 );
add_filter( 'woocommerce_product_get_sale_price', 'ulb_convert_price_to_inr', 999, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'ulb_convert_price_to_inr', 999, 2 );
add_filter( 'woocommerce_product_variation_get_regular_price', 'ulb_convert_price_to_inr', 999, 2 );
add_filter( 'woocommerce_product_variation_get_sale_price', 'ulb_convert_price_to_inr', 999, 2 );

function ulb_convert_price_to_inr( $price, $product ) {
    if ( empty( $price ) ) {
        return $price;
    }
    
    if ( ulb_is_india_region() ) {
        $rate = ulb_get_aed_to_inr_rate();
        return round( floatval( $price ) * $rate );
    }
    
    return $price;
}

/**
 * Filter variation price ranges array for variable products.
 */
add_filter( 'woocommerce_variation_prices', 'ulb_convert_variation_prices_array', 999, 3 );
function ulb_convert_variation_prices_array( $prices_array, $product, $for_display ) {
    if ( ulb_is_india_region() ) {
        $rate = ulb_get_aed_to_inr_rate();
        foreach ( $prices_array as $key => $values ) {
            foreach ( $values as $variation_id => $price ) {
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
            // India: Hide Paymob, keep Razorpay/Cashfree/COD
            if ( strpos( $gateway_id, 'paymob' ) !== false ) {
                unset( $available_gateways[$gateway_id] );
            }
        } else {
            // UAE/Global: Hide Razorpay/Cashfree, keep Paymob
            if ( strpos( $gateway_id, 'razorpay' ) !== false || strpos( $gateway_id, 'cashfree' ) !== false ) {
                unset( $available_gateways[$gateway_id] );
            }
        }
    }
    return $available_gateways;
}

/**
 * Filter shipping rates by region.
 */
add_filter( 'woocommerce_package_rates', 'ulb_filter_shipping_rates_by_region', 999, 2 );
function ulb_filter_shipping_rates_by_region( $rates, $package ) {
    $is_india = ulb_is_india_region();
    
    foreach ( $rates as $rate_id => $rate ) {
        if ( ! $is_india ) {
            // UAE/Global: Hide India shipping methods (e.g. Shiprocket)
            if ( strpos( $rate_id, 'shiprocket' ) !== false ) {
                unset( $rates[$rate_id] );
            }
        }
    }
    return $rates;
}
