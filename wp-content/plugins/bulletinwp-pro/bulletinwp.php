<?php

/**
 * Plugin Name: Bulletin Announcements PRO
 * Plugin URI: https://www.bulletin.rocks
 * Description: Publish a slick announcement banner notice across your website or Woocommerce shop. Extend with icons, countdowns, placement rules and more!
 * Version: 3.6.0
 * Update URI: https://api.freemius.com
 * Author: Bulletin
 * Author URI: https://www.bulletin.rocks
 * Text Domain: bulletinwp
 * Domain Path: /languages
 *
 * @fs_premium_only /lib/, /frontend/views/partials/pro/, /classes/class-bulletinwp-pro.php, /admin/assets/scripts/pro.js, /admin/assets/scripts/components/pro/, /admin/assets/styles/pro.scss, /admin/assets/styles/components/pro/, /admin/build/pro.css, /admin/build/pro.js, /frontend/assets/scripts/pro.js, /frontend/assets/scripts/features/pro/, /frontend/assets/styles/pro.scss, /frontend/build/pro.css, /frontend/build/pro.js
 */
defined( 'ABSPATH' ) or exit;

if ( function_exists( 'bulletinwp_fs' ) ) {
    bulletinwp_fs()->set_basename( true, __FILE__ );
} else {
    defined( 'BULLETINWP__FILE__' ) or define( 'BULLETINWP__FILE__', __FILE__ );
    require_once 'core/config.php';
    
    if ( !function_exists( 'bulletinwp_fs' ) ) {
        /**
         * bulletinwp_fs
         *
         * Bulletin freemius helper function for easy SDK access.
         *
         * @since	1.0.0
         *
         * @param	void
         * @return object $bulletinwp_fs
         */
        function bulletinwp_fs()
        {
            global  $bulletinwp_fs ;
            
            if ( !isset( $bulletinwp_fs ) ) {
				class BulletinwpFsNull{
					public function is__premium_only() {
						return true;
					}
					public function pricing_url() {
						return '';
					}
					public function is_activation_mode() {
						return true;
					}
					public function add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
						add_action( $tag, $function_to_add, $priority, $accepted_args );
					}
				}
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_5823_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_5823_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/modules/freemius/start.php';
                $bulletinwp_fs = new BulletinwpFsNull();
            }
            
            return $bulletinwp_fs;
        }
        
        // Init Freemius.
        bulletinwp_fs();
        // Signal that SDK was initiated.
        do_action( 'bulletinwp_fs_loaded' );
    }
    
    // Classes
    include_once 'classes/class-bulletinwp-activation.php';
    include_once 'classes/class-bulletinwp-admin.php';
    include_once 'classes/class-bulletinwp-ajax.php';
    include_once 'classes/class-bulletinwp-api.php';
    include_once 'classes/class-bulletinwp-bulletins-table.php';
    include_once 'classes/class-bulletinwp-customizer.php';
    include_once 'classes/class-bulletinwp-export.php';
    include_once 'classes/class-bulletinwp-helpers.php';
    include_once 'classes/class-bulletinwp-import.php';
    include_once 'classes/class-bulletinwp-language.php';
    include_once 'classes/class-bulletinwp-sql.php';
    if ( bulletinwp_fs()->is__premium_only() ) {
        include_once 'classes/class-bulletinwp-pro.php';
    }
    final class BULLETINWP
    {
        private static  $_instance = null ;
        public  $activation ;
        public  $admin ;
        public  $ajax ;
        public  $api ;
        public  $customizer ;
        public  $export ;
        public  $helpers ;
        public  $import ;
        public  $language ;
        public  $sql ;
        public  $pro ;
        public function __construct()
        {
            
            if ( is_admin() ) {
                $this->activation = new BULLETINWP_Activation();
                $this->admin = new BULLETINWP_Admin();
                $this->ajax = new BULLETINWP_Ajax();
                $this->api = new BULLETINWP_API();
                $this->export = new BULLETINWP_Export();
                $this->import = new BULLETINWP_Import();
            } else {
                add_action( 'plugins_loaded', array( $this, 'frontend_init' ) );
            }
            
            $this->customizer = new BULLETINWP_Customizer();
            $this->helpers = new BULLETINWP_Helpers();
            $this->language = new BULLETINWP_Language();
            $this->sql = new BULLETINWP_SQL();
            if ( bulletinwp_fs()->is__premium_only() ) {
                $this->pro = new BULLETINWP_Pro();
            }
        }
        
        /**
         * activate_plugin
         *
         * Run functions when plugin is activated
         *
         * @since	1.0.0
         *
         * @param	void
         * @return class BULLETINWP
         */
        public static function instance()
        {
            return ( is_null( self::$_instance ) ? self::$_instance = new BULLETINWP() : self::$_instance );
        }
        
        /**
         * activate_plugin
         *
         * Run functions when plugin is activated
         *
         * @since	1.0.0
         *
         * @param	void
         * @return void
         */
        public function frontend_init()
        {
            include_once 'classes/class-bulletinwp-frontend.php';
            new BULLETINWP_Frontend();
        }
    
    }
    BULLETINWP::instance();
}
