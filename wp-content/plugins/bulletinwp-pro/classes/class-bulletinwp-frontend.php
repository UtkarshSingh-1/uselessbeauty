<?php

defined( 'ABSPATH' ) or exit;

class BULLETINWP_Frontend {
  private static $bulletin_html_comment = '<!-- this cool-looking banner is made using bulletin. https://bulletin.rocks -->';

  public function __construct() {
    if ( $this->maybe_load_bulletins() ) {
      add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );

      // Placement top bulletins
      add_action( 'wp_head', array( $this, 'insert_placement_top_bulletins' ), 100 );

      // Placement sticky footer bulletins
      add_action( 'wp_footer', array( $this, 'insert_placement_sticky_footer_bulletins' ) );

      // Placement float bottom bulletins
      add_action( 'wp_footer', array( $this, 'insert_placement_float_bottom_bulletins' ) );

      if ( bulletinwp_fs()->is__premium_only() ) {
        // Placement corner top left bulletins
        add_action( 'wp_footer', array( $this, 'insert_placement_corner_top_left_bulletins' ) );

        // Placement corner top right bulletins
        add_action( 'wp_footer', array( $this, 'insert_placement_corner_top_right_bulletins' ) );

        // Placement corner bottom left bulletins
        add_action( 'wp_footer', array( $this, 'insert_placement_corner_bottom_left_bulletins' ) );

        // Placement corner bottom right bulletins
        add_action( 'wp_footer', array( $this, 'insert_placement_corner_bottom_right_bulletins' ) );
      }

      add_filter( 'safe_style_css', function ( $allowed_styles ) {
        $custom_styles = [ 'position', 'top', 'bottom', 'left', 'right' ];
        return array_merge( $allowed_styles, $custom_styles );
      }, 10, 2 );
    }
  }

  /**
   * Check if need to load bulletins
   *
   * @param void
   *
   * @return bool
   * @since 1.0.0
   *
   */
  private function maybe_load_bulletins() {
    if ( BULLETINWP::instance()->helpers->maybe_in_preview_mode() ) {
      return true;
    }

    if ( bulletinwp_fs()->is__premium_only() ) {
      return ! empty( BULLETINWP::instance()->pro->get_all_allowed_active_bulletins() );
    } else {
      return ! empty( BULLETINWP::instance()->sql->get_all_active_bulletins_count() );
    }
  }

  /**
   * Enqueue frontend assets
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function enqueue_frontend_assets() {
    $plugin_slug = BULLETINWP_PLUGIN_SLUG;

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'underscore' );

    if ( bulletinwp_fs()->is__premium_only() ) {
      if ( BULLETINWP::instance()->pro->is_google_fonts_api_needed() ) {
        wp_enqueue_script( "{$plugin_slug}-google-fonts-api", '//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js', false );

        add_action( 'wp_head', function () {
          $google_fonts = BULLETINWP::instance()->pro->get_all_displayed_bulletins_google_fonts();

          if ( ! empty( $google_fonts ) && is_array( $google_fonts ) ) {
            $font_families = '"' . implode( '", "', $google_fonts ) . '"';
            $font_families =  str_replace( ' - Arabic', '', $font_families );

            echo wp_kses( '<script>WebFont.load({google: {families: [' . $font_families . ']}});</script>', ['script' => []] );
          }
        } );
      }

      if ( BULLETINWP::instance()->pro->is_swiper_lib_needed() ) {
        wp_enqueue_style( "{$plugin_slug}-swiper-styles", plugin_dir_url( BULLETINWP__FILE__ ) . 'lib/swiper/swiper-bundle.min.css', array(), '5.4.0' );
        wp_enqueue_script( "{$plugin_slug}-swiper-scripts", plugin_dir_url( BULLETINWP__FILE__ ) . 'lib/swiper/swiper-bundle.min.js', array(), '5.4.0', true );
      }

      wp_enqueue_style( "{$plugin_slug}-frontend-styles", plugin_dir_url( BULLETINWP__FILE__ ) . 'frontend/build/pro.css', array(), BULLETINWP_PLUGIN_VERSION );
      wp_enqueue_script( "{$plugin_slug}-frontend-scripts", plugin_dir_url( BULLETINWP__FILE__ ) . 'frontend/build/pro.js', array( 'jquery' ), BULLETINWP_PLUGIN_VERSION, true );
    } else {
      wp_enqueue_style( "{$plugin_slug}-frontend-styles", plugin_dir_url( BULLETINWP__FILE__ ) . 'frontend/build/free.css', array(), BULLETINWP_PLUGIN_VERSION );
      wp_enqueue_script( "{$plugin_slug}-frontend-scripts", plugin_dir_url( BULLETINWP__FILE__ ) . 'frontend/build/free.js', array( 'jquery' ), BULLETINWP_PLUGIN_VERSION, true );
    }

    wp_localize_script(
      "{$plugin_slug}-frontend-scripts",
      'BULLETINWP',
      [
        'pluginSlug' => $plugin_slug,
      ]
    );
  }

  /**
   * Insert bulletins by placement
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function insert_bulletins_by_placement( $placement, $corner_position = '' ) {
    $bulletins = [];

    if ( BULLETINWP::instance()->helpers->maybe_in_preview_mode() ) {
      if ( ! empty( $bulletin_id = BULLETINWP::instance()->helpers->get_preview_mode_bulletin_id() )
           && ( BULLETINWP::instance()->sql->get_bulletin_data( $bulletin_id, 'placement' ) === $placement )
      ) {
        $bulletin  = BULLETINWP::instance()->sql->get_bulletin( $bulletin_id );
        $bulletins = [ $bulletin ];
      }
    } else {
      $bulletins = BULLETINWP::instance()->sql->get_all_active_bulletins_by_placement( $placement, $corner_position );
    }

    if ( is_customize_preview() || ! empty( $bulletins ) ) {
      ob_start();
      ?>
        <!-- only use generator for top placements -->
        <?php if ( $placement === 'top' ) : ?>
          <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG ) ?>-generator" style="display: none;">
            <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/bulletins.php' );?>
          </div>
        <?php else : ?>
          <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/bulletins.php' );?>
        <?php endif; ?>
      <?php
      $html = ob_get_clean();

      echo wp_kses( self::$bulletin_html_comment, [] );
      echo wp_kses( BULLETINWP::instance()->helpers->get_compressed_html_string( $html ), $this->get_allowed_tags() );
    }
  }


  /**
   * Returns allowed kses tags for echoing bulletins content
   *
   * @param void
   *
   * @return Array
   * @since 3.5.2
   *
   */
  public function get_allowed_tags() {
    $post_allowed_tags = wp_kses_allowed_html( 'post' );

    $data_attribs = [
      'onclick'                    => [],
      'data-show-elements'         => [],
      'data-hide-elements'         => [],
      'data-tab'                   => [],
      'data-checked-label'         => [],
      'data-unchecked-label'       => [],
      'data-hide-show-elements'    => [],
      'data-show-elements'         => [],
      'data-default-color'         => [],
      'data-font-target'           => [],
      'data-end-date-element'      => [],
      'data-button-status'         => [],
      'data-loading-label'         => [],
      'data-default-label'         => [],
      'data-status-action'         => [],
      'data-bulletin-id'           => [],
      'data-header-banner-style'   => [],
      'data-header-banner-scroll'  => [],
      'data-site-has-fixed-header' => [],
      'data-fixed-header-selector' => [],
      'data-id'                    => [],
      'data-cycle-speed'           => [],
      'data-marquee-speed'         => [],
      'data-button-cookie-expiry'  => [],
      'data-cookie-expiry'         => [],
      'data-countdown-expiry'      => [],
      'data-show-countdown'        => [],
      'data-plugin'                => [],
      'data-type'                  => [],
      'data-module-id'             => [],
      'data-plugin-id'             => [],
      'data-slug'                  => [],
      'data-plan-id'               => [],
      'data-plan'                  => [],
      'data-billing-cycle'         => [],
      'data-pricing'               => [],
      'data-install-id'            => [],
      'data-title'                 => [],
      'data-manager-id'            => [],
      'data-require-license'       => [],
      'data-public-url'            => [],
      'data-val'                   => [],
      'data-blog-id'               => [],
      'data-is-disconnected'       => [],
      'data-left'                  => [],
      'data-license-key'           => [],
      'data-license-id'            => [],
      'data-input-type'            => [],
      'data-input-placeholder'     => [],
      'data-panel-id'              => [],
      'data-action-type'           => [],
    ];

    $svg_kses = BULLETINWP::instance()->helpers->get_kses_allowed_protocols_for_svg();

    $allowed_tags = [];

    foreach ( array_keys( $post_allowed_tags ) as $tag ) {
      $attributes     = $post_allowed_tags[ $tag ];
      $new_attributes = [];

      foreach ( array_keys( $attributes ) as $attribute ) {
        $new_attributes[ $attribute ] = [];
      }

      $allowed_tags[ $tag ] = array_merge( $new_attributes, $data_attribs );
    }

    $allowed_tags['style'] = [];
    $allowed_tags          = array_merge( $allowed_tags, $svg_kses );

    return $allowed_tags;
  }

  /**
   * Insert header bulletins
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function insert_placement_top_bulletins() {
    $this->insert_bulletins_by_placement( 'top' );
  }

  /**
   * Insert footer bulletins
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function insert_placement_sticky_footer_bulletins() {
    $this->insert_bulletins_by_placement( 'sticky-footer' );
  }

  /**
   * Insert footer bulletins
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function insert_placement_float_bottom_bulletins() {
    $this->insert_bulletins_by_placement( 'float-bottom' );
  }

  /**
   * Insert corner top left bulletins
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function insert_placement_corner_top_left_bulletins() {
    $this->insert_bulletins_by_placement( 'corner', 'top-left' );
  }

  /**
   * Insert corner top right bulletins
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function insert_placement_corner_top_right_bulletins() {
    $this->insert_bulletins_by_placement( 'corner', 'top-right' );
  }

  /**
   * Insert corner bottom left bulletins
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function insert_placement_corner_bottom_left_bulletins() {
    $this->insert_bulletins_by_placement( 'corner', 'bottom-left' );
  }

  /**
   * Insert corner bottom right bulletins
   *
   * @param void
   *
   * @return void
   * @since 1.0.0
   *
   */
  public function insert_placement_corner_bottom_right_bulletins() {
    $this->insert_bulletins_by_placement( 'corner', 'bottom-right' );
  }
}
