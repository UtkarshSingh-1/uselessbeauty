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
    if ( class_exists( 'WC_Geolocation' ) ) {
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