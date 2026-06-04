<?php

/**
 * Salient functions and definitions.
 *
 * @package Salient
 * @since 1.0
 */


 /**
  * Define Constants.
  */
define( 'NECTAR_THEME_DIRECTORY', get_template_directory() );
define( 'NECTAR_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/nectar/' );
define( 'NECTAR_THEME_NAME', 'salient' );


if ( ! function_exists( 'get_nectar_theme_version' ) ) {
	function nectar_get_theme_version() {
		return '17.4.1';
	}
}


/**
 * Load text domain.
 */
add_action( 'after_setup_theme', 'nectar_lang_setup' );

if ( ! function_exists( 'nectar_lang_setup' ) ) {
	function nectar_lang_setup() {
		load_theme_textdomain( 'salient', get_template_directory() . '/lang' );
	}
}


/**
 * General WordPress.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/wp-general.php';


/**
 * Get Salient theme options.
 */
function get_nectar_theme_options() {

	$legacy_options  = get_option( 'salient' );
	$current_options = get_option( 'salient_redux' );

	if ( ! empty( $current_options ) && is_array($current_options) ) {
		return $current_options;
	} elseif ( ! empty( $legacy_options ) && is_array($legacy_options) ) {
		return $legacy_options;
	} else {
		return array();
	}
}

$nectar_options                    = get_nectar_theme_options();
$nectar_get_template_directory_uri = get_template_directory_uri();


require_once NECTAR_THEME_DIRECTORY . '/includes/class-nectar-theme-manager.php';


/**
 * Register/Enqueue theme assets.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/icon-collections.php';
require_once NECTAR_THEME_DIRECTORY . '/includes/class-nectar-element-assets.php';
require_once NECTAR_THEME_DIRECTORY . '/includes/class-nectar-element-styles.php';
require_once NECTAR_THEME_DIRECTORY . '/includes/class-nectar-lazy.php';
require_once NECTAR_THEME_DIRECTORY . '/includes/class-nectar-delay-js.php';
require_once NECTAR_THEME_DIRECTORY . '/includes/class-nectar-login.php';
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/enqueue-scripts.php';
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/enqueue-styles.php';
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/dynamic-styles.php';


/**
 * Salient Plugin notices.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/plugin-notices/salient-plugin-notices.php';


/**
 * Salient welcome page.
 */
 require_once NECTAR_THEME_DIRECTORY . '/nectar/welcome/welcome-page.php';


/**
 * Theme hooks & actions.
 */
function nectar_hooks_init() {

	require_once NECTAR_THEME_DIRECTORY . '/nectar/hooks/hooks.php';
	require_once NECTAR_THEME_DIRECTORY . '/nectar/hooks/actions.php';

}

add_action( 'after_setup_theme', 'nectar_hooks_init', 10 );


/**
 * Post category meta.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/meta/category-meta.php';


/**
 * Media and theme image sizes.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/media.php';


/**
 * Navigation menus
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/assets/functions/wp-menu-custom-items/menu-item-custom-fields.php';
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/nav-menus.php';


/**
 * TGM Plugin inclusion.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/tgm-plugin-activation/class-tgm-plugin-activation.php';
require_once NECTAR_THEME_DIRECTORY . '/nectar/tgm-plugin-activation/required_plugins.php';


/**
 * WPBakery functionality.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/wpbakery-init.php';


/**
 * Theme skin specific class and assets.
 */
$nectar_theme_skin    = NectarThemeManager::$skin;
$nectar_header_format = ( ! empty( $nectar_options['header_format'] ) ) ? $nectar_options['header_format'] : 'default';

add_filter( 'body_class', 'nectar_theme_skin_class' );

function nectar_theme_skin_class( $classes ) {
	global $nectar_theme_skin;
	$classes[] = $nectar_theme_skin;
	return $classes;
}


function nectar_theme_skin_css() {
	global $nectar_theme_skin;
	wp_enqueue_style( 'skin-' . $nectar_theme_skin );
}

add_action( 'wp_enqueue_scripts', 'nectar_theme_skin_css' );



/**
 * Search related.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/search.php';


/**
 * Register Widget areas.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/widget-related.php';


/**
 * Header navigation helpers.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/header.php';


/**
 * Blog helpers.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/blog.php';


/**
 * Page helpers.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/page.php';
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/footer.php';

/**
 * Theme options panel (Redux).
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/redux-salient.php';


/**
 * WordPress block editor helpers (Gutenberg).
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/gutenberg.php';


/**
 * Admin assets.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/admin-enqueue.php';


/**
 * Pagination Helpers.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/pagination.php';


/**
 * Page header.
 */
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/page-header.php';


/**
 * Third party.
 */
require_once NECTAR_THEME_DIRECTORY . '/includes/third-party-integrations/seo.php';
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/wpml.php';
require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/woocommerce.php';


/**
 * v10.5 update assist.
 */
 require_once NECTAR_THEME_DIRECTORY . '/nectar/helpers/update-assist.php';

// Add First Name and Last Name fields to WooCommerce registration form
add_action('woocommerce_register_form_start', 'add_name_fields_to_registration_form');
function add_name_fields_to_registration_form() {
    ?>
    <p class="form-row form-row-first">
        <label for="reg_first_name"><?php esc_html_e('First Name', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="first_name" id="reg_first_name" value="<?php if (!empty($_POST['first_name'])) echo esc_attr($_POST['first_name']); ?>" />
    </p>
    <p class="form-row form-row-last">
        <label for="reg_last_name"><?php esc_html_e('Last Name', 'woocommerce'); ?> <span class="required">*</span></label>
        <input type="text" class="input-text" name="last_name" id="reg_last_name" value="<?php if (!empty($_POST['last_name'])) echo esc_attr($_POST['last_name']); ?>" />
    </p>
    <div class="clear"></div>
    <?php
}

// Validate the new fields
add_action('woocommerce_register_post', 'validate_name_fields_on_registration', 10, 3);
function validate_name_fields_on_registration($username, $email, $validation_errors) {
    if (isset($_POST['first_name']) && empty($_POST['first_name'])) {
        $validation_errors->add('first_name_error', __('First name is required.', 'woocommerce'));
    }
    if (isset($_POST['last_name']) && empty($_POST['last_name'])) {
        $validation_errors->add('last_name_error', __('Last name is required.', 'woocommerce'));
    }
    return $validation_errors;
}

/* Save the new fields
add_action('woocommerce_created_customer', 'save_name_fields_on_registration');
function save_name_fields_on_registration($customer_id) {
    if (isset($_POST['first_name'])) {
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['first_name']));
        wp_update_user(array('ID' => $customer_id, 'first_name' => sanitize_text_field($_POST['first_name'])));
    }
    if (isset($_POST['last_name'])) {
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['last_name']));
        wp_update_user(array('ID' => $customer_id, 'last_name' => sanitize_text_field($_POST['last_name'])));
    }
}
add_action('user_register', 'send_user_to_klaviyo', 10, 1);
function send_user_to_klaviyo($user_id) {
    $user_info = get_userdata($user_id);
    $email = $user_info->user_email;
    $first_name = $user_info->first_name;
    $last_name = $user_info->last_name;

    $api_key = 'VV5pMv'; 

    $data = array(
        'profiles' => array(array(
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ))
    );

    $response = wp_remote_post('https://a.klaviyo.com/api/profiles/?api_key=VV5pMv' . $api_key, array(
        'headers' => array('Content-Type' => 'application/json'),
        'body' => wp_json_encode($data),
        'method' => 'POST'
    ));
}

// Add Google Login button on WooCommerce login + register forms
function custom_google_login_button() {
    if (function_exists('nsl_render_login_form_buttons')) {
        echo '<div class="google-login-wrapper" style="margin-bottom:20px; text-align:center;">';
        nsl_render_login_form_buttons(array('providers' => 'google'));
        echo '</div>';
    }
}*/
add_filter('the_content', 'custom_form_after_salient_content');

function custom_form_after_salient_content($content) {

    // Only apply on single blog posts
    if (!is_single()) return $content;

    // Detect the closing div of post-content
    $closing_div = '</div><!--/post-content-->';

    // If the structure exists
    if (strpos($content, $closing_div) !== false) {

        $form_html = '
        <div class="custom-blog-form" style="padding:30px; margin-top:40px; border:1px solid #eee;">
            <h3>Get Exclusive Updates</h3>
            <form method="post" action="">
                <input type="text" name="name" placeholder="Your Name" style="width:100%; padding:10px; margin-bottom:10px;">
                <input type="email" name="email" placeholder="Your Email" style="width:100%; padding:10px; margin-bottom:10px;">
                <button type="submit" style="padding:15px 30px; background:#000; color:#fff;">Subscribe</button>
            </form>
        </div>
        ';

        // Insert form BEFORE the closing div
        return str_replace($closing_div, $form_html . $closing_div, $content);
    }

    return $content;
}
add_filter( 'woocommerce_currency_symbol', 'custom_aed_currency_symbol', 10, 2 );
function custom_aed_currency_symbol( $currency_symbol, $currency ) {
    if ( $currency === 'AED' ) {
        $currency_symbol = 'AED ';
    }
    return $currency_symbol;
}

add_filter( 'woocommerce_price_format', 'custom_aed_price_format', 10, 2 );
function custom_aed_price_format( $format, $currency_pos ) {
    // Always show currency before amount: AED 129
    return '%1$s%2$s';
}
add_filter( 'woocommerce_order_button_text', 'change_place_order_text' );
function change_place_order_text() {
    return 'Continue to Payment'; // Or "Proceed to Pay"
}

