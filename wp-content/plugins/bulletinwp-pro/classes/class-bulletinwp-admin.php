<?php

defined( 'ABSPATH' ) or exit;

class BULLETINWP_Admin {
  private static $menu_page_base_slug = BULLETINWP_PLUGIN_SLUG . '-options';

  public function __construct() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

    if ( ! bulletinwp_fs()->is_activation_mode() ) {
      add_action( 'admin_init', array( $this, 'handle_welcome_page_redirect' ) );
    }

    add_action( 'admin_menu', array( $this, 'setup_admin_pages' ) );
    add_action( 'admin_head', array( $this, 'remove_submenu_pages' ) );

    add_filter( 'set-screen-option', array( $this, 'set_bulletins_page_screen_options' ), 10, 3 );

    add_action( 'admin_notices', array( $this, 'handle_admin_notices' ) );
  }

  /**
   * Enqueue admin assets
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function enqueue_admin_assets() {
    if ( BULLETINWP::instance()->api->is_page_in_plugin() ) {
      $plugin_slug = BULLETINWP_PLUGIN_SLUG;

      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'underscore' );

      // Google fonts font families
      wp_enqueue_style( "{$plugin_slug}-playfair-display-font", "https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap", false );

      if ( bulletinwp_fs()->is__premium_only() ) {
        wp_enqueue_media();

        // Google Fonts
        wp_enqueue_script( "{$plugin_slug}-google-fonts-api", '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', false );

        // Date Time Picker
        wp_enqueue_style( "{$plugin_slug}-datetimepicker-styles", plugin_dir_url( BULLETINWP__FILE__ ) . 'lib/datetimepicker/jquery.datetimepicker.min.css', array(), '2.5.20' );
        wp_enqueue_script( "{$plugin_slug}-datetimepicker-scripts", plugin_dir_url( BULLETINWP__FILE__ ) . 'lib/datetimepicker/jquery.datetimepicker.full.min.js', array( 'jquery' ), '2.5.20', true );

        // Select 2
        wp_enqueue_style( "{$plugin_slug}-select2-styles", plugin_dir_url( BULLETINWP__FILE__ ) . 'lib/select2/css/select2.min.css', array(), '4.0.13' );
        wp_enqueue_script( "{$plugin_slug}-select2-scripts", plugin_dir_url( BULLETINWP__FILE__ ) . 'lib/select2/js/select2.full.min.js', array( 'jquery' ), '4.0.13', true );

        wp_enqueue_style( "{$plugin_slug}-admin-styles", plugin_dir_url( BULLETINWP__FILE__ ) . 'admin/build/pro.css', array(), BULLETINWP_PLUGIN_VERSION );
        wp_enqueue_script( "{$plugin_slug}-admin-scripts", plugin_dir_url( BULLETINWP__FILE__ ) . 'admin/build/pro.js', array( 'jquery', 'wp-color-picker' ), BULLETINWP_PLUGIN_VERSION, true );
      } else {
        wp_enqueue_style( "{$plugin_slug}-admin-styles", plugin_dir_url( BULLETINWP__FILE__ ) . 'admin/build/free.css', array(), BULLETINWP_PLUGIN_VERSION );
        wp_enqueue_script( "{$plugin_slug}-admin-scripts", plugin_dir_url( BULLETINWP__FILE__ ) . 'admin/build/free.js', array( 'jquery', 'wp-color-picker' ), BULLETINWP_PLUGIN_VERSION, true );
      }

      wp_localize_script(
        "{$plugin_slug}-admin-scripts",
        'BULLETINWP',
        [
          'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
          'pluginSlug'     => $plugin_slug,
          'buildPath'      => parse_url( plugin_dir_url( BULLETINWP__FILE__ ) . 'admin/build', PHP_URL_PATH ),
          'timezoneString' => BULLETINWP::instance()->helpers->get_timezone_string(),
          'translations'   => [
            'editBulletin'              => __( 'Edit bulletin', $plugin_slug ),
            'emptyCSVFile'              => __( 'Empty csv file', $plugin_slug ),
            'fileIsRequired'            => __( 'File is required', $plugin_slug ),
            'fileIsInvalid'             => __( 'File is invalid', $plugin_slug ),
            'inactive'                  => __( 'Inactive', $plugin_slug ),
            'pleaseCheckRequiredFields' => __( 'Please check required fields', $plugin_slug ),
            'invalidScheduleTime'       => __( 'Invalid schedule time', $plugin_slug ),
            'preview'                   => __( 'Preview', $plugin_slug ),
            'saveBulletin'              => __( 'Save Bulletin', $plugin_slug ),
            'saving'                    => __( 'Saving', $plugin_slug ),
            'thisFieldIsRequired'       => __( 'This field is required', $plugin_slug ),
            'validationFailed'          => __( 'Validation failed.', $plugin_slug ),
            'view'                      => __( 'View', $plugin_slug ),
            'viewNow'                   => __( 'View now', $plugin_slug ),
          ],
        ]
      );
    }
  }

  /**
   * Handle welcome page redirect
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function handle_welcome_page_redirect() {
    if ( bulletinwp_fs()->is__premium_only() ) {
      // Do nothing
    } else {
      if ( ! get_transient( BULLETINWP_PLUGIN_WELCOME_PAGE_TRANSIENT_KEY ) ) {
        return;
      }

      delete_transient( BULLETINWP_PLUGIN_WELCOME_PAGE_TRANSIENT_KEY );

      if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
        return;
      }

      wp_safe_redirect( admin_url( 'admin.php?page=' . self::$menu_page_base_slug . '-welcome' ) );

      exit;
    }
  }

  /**
   * Setup admin welcome page
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function get_admin_welcome_page() {
    include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/pages/welcome.php' );
  }

  /**
   * Get admin bulletins page
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function get_admin_bulletins_page() {
    include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/pages/bulletins.php' );
  }

  /**
   * Get admin add new page
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function get_admin_add_new_page() {
    include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/pages/add-new.php' );
  }

  /**
   * Get admin edit page
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function get_admin_edit_page() {
    include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/pages/edit.php' );
  }

  /**
   * Get admin settings page
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function get_admin_settings_page() {
    include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/pages/settings.php' );
  }

  /**
   * Setup admin pages
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function setup_admin_pages() {
    if ( bulletinwp_fs()->is__premium_only() ) {
      if ( ! BULLETINWP::instance()->pro->check_page_access_permission() ) {
        return;
      }
    }

    $bulletins_page = add_menu_page(
      BULLETINWP_PLUGIN_NAME,
      BULLETINWP_PLUGIN_NAME,
      'edit_pages',
      self::$menu_page_base_slug,
      array( $this, 'get_admin_bulletins_page' ),
      plugin_dir_url( BULLETINWP__FILE__ ) . 'admin/images/dashicon.svg'
    );

    // Add Screen Options on bulletins page
    add_action( "load-$bulletins_page", array( $this, 'add_bulletins_page_screen_options' ) );

    $submenu_pages = [
      [
        'title' => __( 'Bulletins', BULLETINWP_PLUGIN_SLUG ),
        'slug'  => self::$menu_page_base_slug,
        'name'  => 'bulletins',
      ],
      [
        'title' => __( 'Add New', BULLETINWP_PLUGIN_SLUG ),
        'slug'  => self::$menu_page_base_slug . '-add-new',
        'name'  => 'add_new',
      ],
      [
        'title' => __( 'Edit Bulletin', BULLETINWP_PLUGIN_SLUG ),
        'slug'  => self::$menu_page_base_slug . '-edit',
        'name'  => 'edit',
      ],
      [
        'title' => __( 'Settings', BULLETINWP_PLUGIN_SLUG ),
        'slug'  => self::$menu_page_base_slug . '-settings',
        'name'  => 'settings',
      ],
    ];

    foreach ( $submenu_pages as $page ) {
      add_submenu_page(
        self::$menu_page_base_slug,
        $page['title'],
        $page['title'],
        'edit_pages',
        $page['slug'],
        array( $this, 'get_admin_' . $page['name'] . '_page' )
      );
    }

    add_submenu_page(
      self::$menu_page_base_slug,
      'Welcome to Bulletin',
      'Welcome to Bulletin',
      'edit_pages',
      self::$menu_page_base_slug . '-welcome',
      array( $this, 'get_admin_welcome_page' )
    );
  }

  /**
   * Remove submenu pages
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function remove_submenu_pages() {
    remove_submenu_page( self::$menu_page_base_slug, self::$menu_page_base_slug . '-welcome' );
    remove_submenu_page( self::$menu_page_base_slug, self::$menu_page_base_slug . '-edit' );
  }

  /**
   * Add screen options on bulletins page
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function add_bulletins_page_screen_options() {
    $plugin_slug = BULLETINWP_PLUGIN_SLUG;
    $option      = 'per_page';

    $args = [
      'label'   => __( 'Number of items per page:', $plugin_slug ),
      'default' => 20,
      'option'  => "{$plugin_slug}_bulletins_per_page",
    ];

    add_screen_option( $option, $args );
  }

  /**
   * Set screen options on bulletins page
   *
   * @since 1.0.0
   *
   * @param bool $status
   * @param string $option
   * @param int $value
   * @return string
   */
  public function set_bulletins_page_screen_options( $status, $option, $value ) {
    $plugin_slug = BULLETINWP_PLUGIN_SLUG;

    if ( "{$plugin_slug}_bulletins_per_page" === $option ) {
      return $value;
    }

    return $status;
  }

  /**
   * Handle admin notices
   *
   * @since 1.0.0
   *
   * @param void
   * @return void
   */
  public function handle_admin_notices() {
    if ( BULLETINWP::instance()->api->is_page_in_plugin()
         && isset( $_GET['page'], $_SERVER['HTTP_REFERER'] )
         && ( sanitize_text_field( $_GET['page'] ) === self::$menu_page_base_slug )
    ) :
      $referer        = filter_var( $_SERVER['HTTP_REFERER'], FILTER_SANITIZE_URL );
      $url_query      = parse_url( $referer, PHP_URL_QUERY );
      parse_str( $url_query, $url_query_array );

      if ( ! empty( $url_query_array['_wpnonce'] ) ) :
        $bulletins_count = 0;
        $action_label    = '';

        if ( isset( $url_query_array['bulletin'] ) ) {
          if ( is_array( $url_query_array['bulletin'] ) ) {
            $bulletins_count = count( $url_query_array['bulletin'] );
          } else {
            $bulletins_count = 1;
          }
        }

        if ( ( isset( $url_query_array['action'] ) && $url_query_array['action'] != -1 )
             || ( isset( $url_query_array['action2'] ) && $url_query_array['action2'] != -1 )
        ) {
          $action = '';

          if ( isset( $url_query_array['action'] ) && $url_query_array['action'] != -1 ) {
            $action = $url_query_array['action'];
          } elseif ( isset( $url_query_array['action2'] ) && $url_query_array['action2'] != -1 ) {
            $action = $url_query_array['action2'];
          }

          if ( ! empty( $action ) ) {
            switch ( $action ) {
              case 'activate':
                $action_label = 'activated';
                break;
              case 'deactivate':
                $action_label = 'deactivated';
                break;
              case 'delete':
                $action_label = 'deleted';
                break;
              default:
                break;
            }
          }
        }

        if ( ! empty( $bulletins_count ) && ! empty( $action_label ) ) :
          ?>
          <div class="notice notice-success">
            <p><?php echo esc_html( sprintf( "%d %s %s", $bulletins_count, _n( 'bulletin', 'bulletins', $bulletins_count, BULLETINWP_PLUGIN_SLUG ), $action_label ) ) ?></p>
          </div>
          <?php
        endif;
      endif;
    endif;
  }
}
