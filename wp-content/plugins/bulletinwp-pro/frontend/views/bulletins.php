<?php

// isset( $placement ) or $placement               = 'top';
// isset( $buletins ) or $bulletins                 = [];
// isset( $active_countdown ) or $active_countdown = false;

$plugin_slug                       = BULLETINWP_PLUGIN_SLUG;
$bulletin_background_color_default = BULLETINWP::instance()->sql->get_option( 'bulletin_background_color_default' );
$bulletin_font_color_default       = BULLETINWP::instance()->sql->get_option( 'bulletin_font_color_default' );
$site_has_fixed_header             = $placement === 'top' ? BULLETINWP::instance()->sql->get_option( 'site_has_fixed_header' ) : false;
$fixed_header_selector             = $placement === 'top' ? BULLETINWP::instance()->sql->get_option( 'fixed_header_selector' ) : false;
$corner_id                         = '';
$corner_option                     = '';
$lang_attribute                    = '';
$user_permission                   = ( current_user_can( 'manage_options' ) ) ? true : false;
$header_banner_style               = '';
$header_banner_scroll              = '';
$header_banner_scroll_class        = '';

if ( bulletinwp_fs()->is__premium_only() ) {
  isset( $corner_position ) or $corner_position = 'top-left';

  if ( $placement === 'corner' ) {
    $corner_option = "{$plugin_slug}-corner-{$corner_position}";
    $corner_id     = "-{$corner_position}";
  }

  $user_permission = BULLETINWP::instance()->pro->check_user_permission();
}

if ( is_customize_preview() || ! empty( $bulletins ) ) :
  foreach ( $bulletins as $bulletin ) :
    if ( $placement === 'top' ) {
      $header_banner_style = $bulletin['header_banner_style'];
      $header_banner_scroll = $bulletin['header_banner_scroll'];

      if ( $header_banner_scroll === 'fixed' && $site_has_fixed_header ) {
        $header_banner_scroll_class = "{$plugin_slug}-top-fixed";
      }
    }

    $bulletin_id = $bulletin['id'];
    ?>

    <div id="<?php echo esc_attr( "{$plugin_slug}-bulletin-item-{$bulletin['id']}" ) ?>"
         class="<?php echo esc_attr( "{$plugin_slug}-bulletins {$plugin_slug}-placement-{$placement} {$corner_option} {$header_banner_scroll_class}" ) ?>"
         data-header-banner-style="<?php echo esc_attr( $header_banner_style ) ?>"
         data-header-banner-scroll="<?php echo esc_attr( $header_banner_scroll ) ?>"
         data-site-has-fixed-header="<?php echo esc_attr( $site_has_fixed_header ? 'true' : 'false' ) ?>"
         data-fixed-header-selector="<?php echo esc_attr( $fixed_header_selector ) ?>"
    >
      <?php
      $bulletin_title = ( isset( $bulletin['bulletin_title'] ) && ! empty( $bulletin['bulletin_title'] ) ) ? $bulletin['bulletin_title'] : '';

      if ( BULLETINWP::instance()->language->maybe_polylang_plugin_is_activated() && function_exists( 'pll__' ) ) {
        if ( isset( $bulletin['content'] ) && ! empty( $bulletin['content'] ) ) {
          $bulletin['content'] = pll__( $bulletin['content'] );
        }

        if ( isset( $bulletin['mobile_content'] ) && ! empty( $bulletin['mobile_content'] ) ) {
          $bulletin['mobile_content'] = pll__( $bulletin['mobile_content'] );
        }
      } elseif ( BULLETINWP::instance()->language->maybe_wpml_plugin_is_activated() ) {
        if ( isset( $bulletin['content'] ) && ! empty( $bulletin['content'] ) ) {
          $bulletin['content'] = apply_filters( 'wpml_translate_single_string', $bulletin['content'], $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Content" );
        }

        if ( isset( $bulletin['mobile_content'] ) && ! empty( $bulletin['mobile_content'] ) ) {
          $bulletin['mobile_content'] = apply_filters( 'wpml_translate_single_string', $bulletin['mobile_content'], $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Mobile Content" );
        }
      }

      $content               = ( isset( $bulletin['content'] ) && ! empty( $bulletin['content'] ) ) ? $bulletin['content'] : '';
      $mobile_content        = ( isset( $bulletin['mobile_content'] ) && ! empty( $bulletin['mobile_content'] ) ) ? $bulletin['mobile_content'] : $content;
      $background_color      = ( isset( $bulletin['background_color'] ) && ! empty( $bulletin['background_color'] ) ) ? $bulletin['background_color'] : $bulletin_background_color_default;
      $font_color            = ( isset( $bulletin['font_color'] ) && ! empty( $bulletin['font_color'] ) ) ? $bulletin['font_color'] : $bulletin_font_color_default;
      $text_align            = ( isset( $bulletin['text_alignment'] ) && ! empty( $bulletin['text_alignment'] ) ) ? $bulletin['text_alignment'] : '';
      $content_max_width     = ( isset( $bulletin['content_max_width'] ) && ! empty( $bulletin['content_max_width'] ) ) ? $bulletin['content_max_width'] . 'px' : '';
      $font_size             = ( isset( $bulletin['font_size'] ) && ! empty( $bulletin['font_size'] ) ) ? $bulletin['font_size'] . 'px' : '';
      $font_size_mobile      = ( isset( $bulletin['font_size_mobile'] ) && ! empty( $bulletin['font_size_mobile'] ) ) ? $bulletin['font_size_mobile'] . 'px' : '';
      $text_vertical_padding = ( isset( $bulletin['text_vertical_padding'] ) && ! empty( $bulletin['text_vertical_padding'] ) ) ? $bulletin['text_vertical_padding'] . 'px' : '';
      $style                 = '';
      $internal_style        = '';
      $additional_class      = '';

      if ( ! empty( $background_color ) ) {
        $style .= 'background-color: ' . $background_color . '; ';
      }

      if ( ! empty( $font_color ) ) {
        $style .= 'color: ' . $font_color . '; ';
      } else {
        $style .= 'color: transparent;';
      }

      if ( ! empty( $text_vertical_padding ) && $placement !== 'corner' ) {
        $style .= "padding: $text_vertical_padding 24px;";
      }

      if ( bulletinwp_fs()->is__premium_only() ) {
        $is_icon_set            = isset( $bulletin['add_icon'] ) && $bulletin['add_icon'] !== 'none';
        $is_font_google_fonts   = isset( $bulletin['fonts'] ) && ! empty( $bulletin['fonts'] ) && $bulletin['fonts'] === 'google-fonts';
        $is_multiple_messages   = isset( $bulletin['is_multiple_messages'] ) && $bulletin['is_multiple_messages'];
        $has_button             = isset( $bulletin['add_button'] ) && $bulletin['add_button'];
        $button_align           = ( isset( $bulletin['button_align'] ) && ! empty( $bulletin['button_align'] ) ) ? $bulletin['button_align'] : '';
        $has_countdown          = isset( $bulletin['add_countdown'] ) && $bulletin['add_countdown'];
        $show_countdown         = isset( $bulletin['show_countdown'] ) && $bulletin['show_countdown'];
        $is_dismissable         = isset( $bulletin['is_dismissable'] ) && $bulletin['is_dismissable'];
        $additional_css         = ( isset( $bulletin['additional_css'] ) && ! empty( $bulletin['additional_css'] ) ) ? $bulletin['additional_css'] : '';
        $center_container_class = '';

        if ( $is_dismissable
            && isset( $_COOKIE[ "{$plugin_slug}-dismiss-expiry" ] )
            && is_array( $_COOKIE[ "{$plugin_slug}-dismiss-expiry" ] )
            && array_key_exists( $bulletin['id'], $_COOKIE[ "{$plugin_slug}-dismiss-expiry" ] )
            && $_COOKIE[ "{$plugin_slug}-dismiss-expiry" ][ $bulletin['id'] ]
        ) {
          $style .= 'display: none; ';
        }

        if ( ( ! $is_multiple_messages && $has_countdown ) || ( $has_button && $button_align === 'content' ) ) {
          $center_container_class = "{$plugin_slug}-content-alignment-{$text_align}";
        }

        if ( ( $has_countdown && $show_countdown ) && $placement !== 'corner' ) {
          $additional_class = 'has-countdown';
        }

        if ( $is_font_google_fonts && strpos( $bulletin['google_fonts'], ' - Arabic' ) ) {
          $lang_attribute = 'dir="rtl" lang="ar"';
        } elseif ( $is_font_google_fonts && strpos( $bulletin['google_fonts'], ' - Hebrew' ) ) {
          $lang_attribute = 'dir="rtl" lang="he"';
        }
      }

      $bulletin_item_style              = BULLETINWP::instance()->helpers->get_compressed_css_string( $style );
      $bulletin_item_float_bottom_style = BULLETINWP::instance()->helpers->get_compressed_css_string( 'max-width:' . ( ( $placement === 'float-bottom' && ! empty( $content_max_width ) ) ? $content_max_width : 'none' ) );
      ?>

      <?php if ( isset( $bulletin['placement'] ) && $bulletin['placement'] === $placement ) : ?>
        <?php if ( $placement === 'corner' ) : ?>
          <style>
            <?php echo esc_html( "#{$plugin_slug}-bulletin-item-{$bulletin['id']}" ); ?> {
              max-width: <?php echo esc_html( ( $placement === 'corner' && ! empty( $content_max_width ) ) ? $content_max_width : '300px' ); ?>;
            }
            @media screen and ( max-width: 768px ) {
              <?php echo esc_html( "#{$plugin_slug}-bulletin-item-{$bulletin['id']}" ); ?> {
                max-width: 100%;
              }
            }
          </style>
        <?php endif; ?>
        <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-item {$additional_class}" ); ?>"
            style="<?php echo esc_attr( $bulletin_item_style ); ?>
              <?php if ( $placement === 'float-bottom' ) : ?>
                <?php echo esc_attr( $bulletin_item_float_bottom_style ); ?>
              <?php endif; ?>
            "
            data-id="<?php echo esc_attr( $bulletin['id'] ); ?>"
            <?php echo wp_kses( $lang_attribute, [] ); ?>
        >
          <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
            <?php if ( ! empty( $additional_css ) ) : ?>
              <style><?php echo esc_html( BULLETINWP::instance()->helpers->get_compressed_css_string( $additional_css ) ); ?></style>
            <?php endif; ?>
          <?php endif; ?>

          <div class="<?php echo esc_attr( "{$plugin_slug}-main-container" ) ?>" style="max-width: <?php echo esc_attr( ( $placement !== 'float-bottom' && $placement !== 'corner' && ! empty( $content_max_width ) ) ? $content_max_width : 'none' ) ?>;">

            <!-- Countdown for corner option -->
            <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
              <?php if ( $has_countdown ) : ?>
                <div id="<?php echo esc_attr( "{$plugin_slug}-countdown-timer-corner-wrapper" ) ?>">
                  <?php if ( $placement === 'corner' ) : ?>
                    <div class="<?php echo esc_attr( "{$plugin_slug}-placement-corner-timer" ) ?>">
                      <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/countdown.php' ); ?>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>

            <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
              <div class="<?php echo esc_attr( "{$plugin_slug}-top-container" ) ?>" style="<?php echo $has_button ? '' : 'margin-bottom: 0;'; ?>">
            <?php else : ?>
              <div class="<?php echo esc_attr( "{$plugin_slug}-top-container" ) ?>" style="margin-bottom: 0;">
            <?php endif; ?>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <?php if ( $is_icon_set ) : ?>
                  <!-- LEFT -->
                  <div class="<?php echo esc_attr( "{$plugin_slug}-left-container" ) ?>">
                    <!-- Icon -->
                    <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/icon.php' ); ?>
                  </div>
                <?php endif; ?>
              <?php endif; ?>

              <!-- CENTER -->
              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <div class="<?php echo esc_attr( "{$plugin_slug}-center-container {$center_container_class}" ) ?>">
              <?php else : ?>
                <div class="<?php echo esc_attr( "{$plugin_slug}-center-container" ) ?>">
              <?php endif; ?>

                <!-- IMAGE (left alignment) -->
                <?php if ( ( isset( $bulletin['add_image'] ) && $bulletin['add_image'] ) && ( isset( $bulletin['image_alignment'] ) && $bulletin['image_alignment'] == 'left' ) && ( $placement !== 'corner' ) ) : ?>
                  <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/image.php' ); ?>
                <?php endif; ?>

                <!-- Countdown -->
                <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                  <?php if ( $has_countdown ) : ?>
                    <div id="<?php echo esc_attr( "{$plugin_slug}-countdown-timer-default-wrapper" ) ?>">
                      <?php if ( $placement !== 'corner' ) : ?>
                        <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/countdown.php' ); ?>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>

                <!-- Message -->
                <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                  <?php if ( $is_multiple_messages ) : ?>
                    <?php
                    $messages       = ( isset( $bulletin['messages'] ) && is_serialized( $bulletin['messages'] ) ) ? array_values( unserialize( $bulletin['messages'] ) ) : [];
                    $rotation_style = ( isset( $bulletin['rotation_style'] ) && ! empty( $bulletin['rotation_style'] ) ) ? $bulletin['rotation_style'] : '';

                    if ( BULLETINWP::instance()->language->maybe_polylang_plugin_is_activated() && function_exists( 'pll__' ) ) {
                      foreach ( $messages as $key => $message ) {
                        if ( isset( $message['content'] ) && ! empty( $message['content'] ) ) {
                          $messages[ $key ]['content'] = pll__( $message['content'] );
                        }

                        if ( isset( $message['mobile_content'] ) && ! empty( $message['mobile_content'] ) ) {
                          $messages[ $key ]['mobile_content'] = pll__( $message['mobile_content'] );
                        }
                      }
                    } elseif ( BULLETINWP::instance()->language->maybe_wpml_plugin_is_activated() ) {
                      foreach ( $messages as $key => $message ) {
                        $message_index = $key + 1;

                        if ( isset( $message['content'] ) && ! empty( $message['content'] ) ) {
                          $messages[ $key ]['content'] = apply_filters( 'wpml_translate_single_string', $message['content'], $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Multiple Messages - ({$message_index})" );
                        }

                        if ( isset( $message['mobile_content'] ) && ! empty( $message['mobile_content'] ) ) {
                          $messages[ $key ]['mobile_content'] = apply_filters( 'wpml_translate_single_string', $message['mobile_content'], $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Multiple Mobile Messages - ({$message_index})" );
                        }
                      }
                    }

                    $messages = array_merge( [
                      [
                        'content'        => $content,
                        'mobile_content' => $mobile_content,
                      ],
                    ], $messages );
                    $messages = array_values( $messages );
                    ?>
                    <?php if ( $rotation_style === 'cycle' ) : ?>
                      <?php
                      $cycle_speed  = ( isset( $bulletin['cycle_speed'] ) && ! empty( $bulletin['cycle_speed'] ) ) ? $bulletin['cycle_speed'] : '';
                      ?>
                      <div class="swiper-container"
                           data-cycle-speed="<?php echo esc_attr( $cycle_speed ) ?>">
                        <div class="swiper-wrapper">
                          <?php if ( ! empty( $messages ) ) : ?>
                            <?php foreach ( $messages as $key => $message ) : ?>
                              <?php
                              $message_content        = $message['content'];
                              $message_mobile_content = $message['content'];

                              if ( ! empty( $message['mobile_content'] ) ) {
                                $message_mobile_content = $message['mobile_content'];
                              }
                              ?>
                              <div class="swiper-slide">
                                <div class="<?php echo esc_attr( "{$plugin_slug}-slide-item" ) ?>">
                                  <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-content-wrapper" ) ?>" style="text-align: <?php echo esc_attr( $text_align ) ?>;">
                                    <?php if ( ! empty( $message_content ) ) : ?>
                                      <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-content" ) ?> <?php echo esc_attr( $key === 0 ? "{$plugin_slug}-bulletin-content-main" : '' ) ?>">
                                        <?php echo wp_kses_post( nl2br( $message_content ) ) ?>
                                      </div>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $message_mobile_content ) ) : ?>
                                      <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-mobile-content" ) ?> <?php echo esc_attr( $key === 0 ? esc_attr( "{$plugin_slug}-bulletin-mobile-content-main" ) : '' ) ?>">
                                        <?php echo wp_kses_post( nl2br( $message_mobile_content ) ) ?>
                                      </div>
                                    <?php endif; ?>
                                  </div>
                                </div>
                              </div>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </div>
                      </div>
                    <?php elseif ( $rotation_style === 'marquee' ) : ?>
                      <?php
                      $marquee_speed = ( isset( $bulletin['marquee_speed'] ) && ! empty( $bulletin['marquee_speed'] ) ) ? $bulletin['marquee_speed'] : '';
                      ?>
                      <div class="<?php echo esc_attr( "{$plugin_slug}-marquee-text-wrapper" ) ?>"
                           data-marquee-speed="<?php echo esc_attr( $marquee_speed ) ?>">
                        <?php if ( ! empty( $messages ) ) : ?>
                          <?php for ( $i = 0; $i < 2; $i++ ) : ?>
                            <?php $part = $i + 1; ?>
                            <div class="<?php echo esc_attr( "{$plugin_slug}-marquee-part {$plugin_slug}-marquee-part-{$part}" ) ?>">
                              <?php for ( $j = 0; $j < 4; $j++ ) : ?>
                                <?php foreach ( $messages as $key => $message ) : ?>
                                  <?php
                                  $message_content        = $message['content'];
                                  $message_mobile_content = $message['content'];

                                  if ( ! empty( $message['mobile_content'] ) ) {
                                    $message_mobile_content = $message['mobile_content'];
                                  }
                                  ?>
                                  <div class="<?php echo esc_attr( "{$plugin_slug}-marquee-text-item" ) ?>">
                                    <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-content-wrapper" ) ?>" style="text-align: <?php echo esc_attr( $text_align ) ?>;">
                                      <?php if ( ! empty( $message_content ) ) : ?>
                                        <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-content" ) ?> <?php echo esc_attr( $key === 0 ? esc_attr( "{$plugin_slug}-bulletin-content-main" ) : '' ) ?>">
                                          <?php echo wp_kses_post( nl2br( $message_content ) ) ?>
                                        </div>
                                      <?php endif; ?>

                                      <?php if ( ! empty( $message_mobile_content ) ) : ?>
                                        <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-mobile-content" ) ?> <?php echo esc_attr( $key === 0 ? esc_attr( "{$plugin_slug}-bulletin-mobile-content-main" ) : '' ) ?>">
                                          <?php echo wp_kses_post( nl2br( $message_mobile_content ) ) ?>
                                        </div>
                                      <?php endif; ?>
                                    </div>
                                  </div>
                                <?php endforeach; ?>
                              <?php endfor; ?>
                            </div>
                          <?php endfor; ?>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>
                  <?php else : ?>
                    <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/simple-content.php' ); ?>
                  <?php endif; ?>
                <?php else : ?>
                  <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/simple-content.php' ); ?>
                <?php endif; ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                  <?php if ( $has_button && $placement !== 'corner' && $button_align === 'content' ) : ?>
                    <!-- Button (Aligned with content) -->
                    <?php
                    $is_mobile = false;
                    include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/button.php' );
                    ?>
                  <?php endif; ?>
                <?php endif; ?>

                <!-- IMAGE (right alignment)  -->
                <?php if ( ( isset( $bulletin['add_image'] ) && $bulletin['add_image'] ) && ( isset( $bulletin['image_alignment'] ) && $bulletin['image_alignment'] == 'right' ) && ( $placement !== 'corner' ) ) : ?>
                  <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/image.php' ); ?>
                <?php endif; ?>

              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <?php if ( $has_button || $is_dismissable ) : ?>
                  <!-- RIGHT -->
                  <div class="<?php echo esc_attr( "{$plugin_slug}-right-container" ) ?>">
                    <?php if ( $has_button && $placement !== 'corner' && $button_align === 'right' ) : ?>
                      <!-- Button (Aligned right) -->
                      <?php
                      $is_mobile = false;
                      include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/button.php' );
                      ?>
                    <?php endif; ?>

                    <!-- Close -->
                    <?php if ( $is_dismissable && isset( $bulletin['cookie_expiry'] ) && strlen( $bulletin['cookie_expiry'] ) > 0 ) : ?>
                      <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/close.php' ); ?>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>

            <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
              <?php if ( $has_button ) : ?>
                <div class="<?php echo esc_attr( "{$plugin_slug}-bottom-container" ) ?>">
                  <?php
                  $is_mobile = true;
                  include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/pro/button.php' );
                  ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>

          <?php if ( ! bulletinwp_fs()->is__premium_only() ) : ?>
            <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-powered-by-label" ) ?>">
              <span><?php _e( 'powered by', $plugin_slug ); ?></span> <a href="<?= esc_url( 'https://www.bulletin.rocks/' ) ?>" target="_blank"><?php _e( 'bulletin', $plugin_slug ) ?></a>
            </div>
          <?php endif; ?>

          <?php if ( is_user_logged_in() && $user_permission ) : ?>
            <?php include( BULLETINWP_PLUGIN_PATH . 'frontend/views/partials/edit-link.php' ); ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <style>
      <?php
      // Internal Style
      if ( ! empty( $font_size ) ) {
        $internal_style .= "
        #{$plugin_slug}-bulletin-item-{$bulletin['id']} {
          font-size: {$font_size} !important;
        }
        #{$plugin_slug}-bulletin-item-{$bulletin['id']} p {
          font-size: {$font_size} !important;
        }
        ";
      } else {
        $internal_style .= "
        #{$plugin_slug}-bulletin-item-{$bulletin['id']} {
          font-size: 16px !important;
        }
        #{$plugin_slug}-bulletin-item-{$bulletin['id']} p {
          font-size: 16px !important;
        }
        ";
      }

      if ( ! empty( $font_size_mobile ) ) {
        $internal_style .= "
        @media (max-width: 767px) {
          #{$plugin_slug}-bulletin-item-{$bulletin['id']} {
            font-size: {$font_size_mobile} !important;
          }
          #{$plugin_slug}-bulletin-item-{$bulletin['id']} p {
            font-size: {$font_size_mobile} !important;
          }
        }
        ";
      } else {
        $internal_style .= "
        @media (max-width: 767px) {
          #{$plugin_slug}-bulletin-item-{$bulletin['id']} {
            font-size: 16px !important;
          }
          #{$plugin_slug}-bulletin-item-{$bulletin['id']} p {
            font-size: 16px !important;
          }
        }
        ";
      }

      if ( bulletinwp_fs()->is__premium_only() ) {
        if ( $is_font_google_fonts && isset( $bulletin['google_fonts'] ) && ! empty( $bulletin['google_fonts'] ) ) {
          $google_font = '';
          if ( strpos( $bulletin['google_fonts'], ' - Arabic' ) ) {
            $google_font = str_replace( ' - Arabic', '', $bulletin['google_fonts'] );
          } elseif ( strpos( $bulletin['google_fonts'], ' - Hebrew' ) ) {
            $google_font = str_replace( ' - Hebrew', '', $bulletin['google_fonts'] );
          } else {
            $google_font = $bulletin['google_fonts'];
          }

          $internal_style .= "
          #{$plugin_slug}-bulletin-item-{$bulletin['id']} {
            font-family:'{$google_font}', Arial, sans-serif;
          }
          #{$plugin_slug}-bulletin-item-{$bulletin['id']} p {
            font-family:'{$google_font}', Arial, sans-serif;
          }
          ";
        }
      }

      echo BULLETINWP::instance()->helpers->get_compressed_css_string( $internal_style );
      ?>
      </style>
    </div>
    <?php
  endforeach;
endif;
