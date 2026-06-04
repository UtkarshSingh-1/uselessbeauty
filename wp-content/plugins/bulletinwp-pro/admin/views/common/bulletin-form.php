<?php

// settings values
$site_has_fixed_header = BULLETINWP::instance()->sql->get_option( 'site_has_fixed_header' );

// Default values
isset( $id ) or $id                                       = '';
isset( $link ) or $link                                   = '';
isset( $is_activated ) or $is_activated                   = false;
isset( $title ) or $title                                 = '';
isset( $content ) or $content                             = '';
isset( $mobile_content ) or $mobile_content               = '';
isset( $background_color ) or $background_color           = '';
isset( $font_color ) or $font_color                       = '';
isset( $placement ) or $placement                         = 'top';
isset( $header_banner_style ) or $header_banner_style     = 'above-header';
isset( $header_banner_scroll ) or $header_banner_scroll   = 'static';
isset( $content_max_width ) or $content_max_width         = '';
isset( $text_alignment ) or $text_alignment               = 'center';
isset( $font_size ) or $font_size                         = '';
isset( $font_size_mobile ) or $font_size_mobile           = '';
isset( $text_vertical_padding ) or $text_vertical_padding = '';

// Visible but disabled
isset( $is_multiple_messages ) or $is_multiple_messages = false;
isset( $add_button ) or $add_button                     = false;
isset( $is_dismissable ) or $is_dismissable             = false;
isset( $add_icon ) or $add_icon                         = 'none';
isset( $add_image ) or $add_image                       = false;
isset( $fonts ) or $fonts                               = 'inherit';
isset( $placement_by_content ) or $placement_by_content = 'everywhere';
isset( $placement_by_user ) or $placement_by_user       = 'everyone';
isset( $add_countdown ) or $add_countdown               = false;
isset( $additional_css ) or $additional_css             = '';

if ( bulletinwp_fs()->is__premium_only() ) {
  // Actual hidden on premium
  isset( $placement_corner_options ) or $placement_corner_options = 'top-left';
  isset( $icon_name_from_set ) or $icon_name_from_set             = '';
  isset( $icon_attachment_id ) or $icon_attachment_id             = '';
  isset( $google_fonts ) or $google_fonts                         = '';
  isset( $image_attachment_id ) or $image_attachment_id           = '';
  isset( $image_alignment ) or $image_alignment                   = 'left';
  isset( $image_max_width ) or $image_max_width                   = '';

  isset( $messages ) or $messages = [
    [
      'content'        => '',
      'mobile_content' => '',
    ],
  ];

  isset( $rotation_style ) or $rotation_style                                         = 'cycle';
  isset( $cycle_speed ) or $cycle_speed                                               = '';
  isset( $marquee_speed ) or $marquee_speed                                           = '';
  isset( $button_label ) or $button_label                                             = '';
  isset( $button_mobile_label ) or $button_mobile_label                               = '';
  isset( $button_background_color ) or $button_background_color                       = '#ffffff';
  isset( $button_font_color ) or $button_font_color                                   = '#000000';
  isset( $button_hover_background_color ) or $button_hover_background_color           = '';
  isset( $button_hover_font_color ) or $button_hover_font_color                       = '';
  isset( $button_action ) or $button_action                                           = 'link';
  isset( $button_cookie_expiry ) or $button_cookie_expiry                             = '';
  isset( $button_link ) or $button_link                                               = '';
  isset( $button_target ) or $button_target                                           = 'same-tab';
  isset( $button_align ) or $button_align                                             = 'right';
  isset( $button_js_event ) or $button_js_event                                       = '';
  isset( $button_easy_popup ) or $button_easy_popup                                   = '';
  isset( $countdown ) or $countdown                                                   = '';
  isset( $show_countdown ) or $show_countdown                                         = false;
  isset( $countdown_background_color ) or $countdown_background_color                 = '';
  isset( $countdown_font_color ) or $countdown_font_color                             = '';
  isset( $countdown_days_label ) or $countdown_days_label                             = '';
  isset( $countdown_hours_label ) or $countdown_hours_label                           = '';
  isset( $countdown_mins_label ) or $countdown_mins_label                             = '';
  isset( $countdown_secs_label ) or $countdown_secs_label                             = '';
  isset( $placement_selected_content_include ) or $placement_selected_content_include = [];
  isset( $placement_selected_content_exclude ) or $placement_selected_content_exclude = [];
  isset( $placement_user_cookie_value ) or $placement_user_cookie_value               = '';
  isset( $cookie_expiry ) or $cookie_expiry                                           = '';
  isset( $apply_all_subsites ) or $apply_all_subsites                                 = false;
  isset( $add_schedule ) or $add_schedule                                             = false;
  isset( $start_schedule ) or $start_schedule                                         = '';

  if ( BULLETINWP::instance()->pro->maybe_easy_popups_plugin_is_activated() ) {
    $popups             = EASY_POPUPS::instance()->sql->get_all_active_popups();
    $easy_popup_options = [];

    if ( ! empty( $popups ) ) {
      foreach ( $popups as $popup ) {
        $popup_id    = $popup['id'];
        $popup_title = ( isset( $popup['popup_title'] ) && ! empty( $popup['popup_title'] ) ) ? $popup['popup_title'] : '';

        $easy_popup_options[] = [
          'value' => $popup_id,
          'label' => $popup_title,
        ];
      }
    }
  }

  $google_font_options = [
    '----- Serif -----',
    'Libre Baskerville',
    'Bree Serif',
    'Cinzel',
    'Josefin Slab',
    'Ultra',
    'ZCOOL XiaoWei',
    'Lobster',
    '----- San Serif -----',
    'Titillium Web',
    'Abel',
    'Notable',
    'Rubik Mono One',
    'Orbitron',
    'Pathway Gothic One',
    'Noto Sans KR',
    '----- Cursive / Special -----',
    'Faster One',
    'Abril Fatface',
    'Bangers',
    'Concert One',
    'Press Start 2P',
    'Monoton',
    'Knewave',
    'Bungee Inline',
    'Norican',
    'Barrio',
    'Butcherman',
    '----- Arabic -----',
    'Almarai - Arabic',
    'Aref Ruqaa - Arabic',
    '----- Hebrew -----',
    'Arimo - Hebrew',
  ];

  $icon_options = [
    [
      'value' => 'announcement',
      'label' => __( 'Announcement', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'book',
      'label' => __( 'Book', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'briefcase',
      'label' => __( 'Briefcase', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'calander',
      'label' => __( 'Calendar', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'chat',
      'label' => __( 'Chat', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'check-circle',
      'label' => __( 'Check circle', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'clip',
      'label' => __( 'Clip', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'clock',
      'label' => __( 'Clock', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'cookie-black',
      'label' => __( 'Cookie black', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'emotion-happy',
      'label' => __( 'Emotion happy', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'emotion-sad',
      'label' => __( 'Emotion sad', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'exclamation',
      'label' => __( 'Exclamation', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'heart',
      'label' => __( 'Heart', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'help',
      'label' => __( 'Help', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'information',
      'label' => __( 'Information', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'key',
      'label' => __( 'Key', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'location',
      'label' => __( 'Location', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'moon',
      'label' => __( 'Moon', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'music',
      'label' => __( 'Music', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'notification',
      'label' => __( 'Notification', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'rocket',
      'label' => __( 'Rocket', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'star',
      'label' => __( 'Star', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'thumb-up',
      'label' => __( 'Thumb up', BULLETINWP_PLUGIN_SLUG ),
    ],
    [
      'value' => 'trophy',
      'label' => __( 'Trophy', BULLETINWP_PLUGIN_SLUG ),
    ],
  ];
}

// Images directory
$images_dir = plugin_dir_url( BULLETINWP__FILE__ ) . 'admin/images';

// Button label
$button_status = empty( $id ) ? 'publish' : 'edit';
$default_label = empty( $id ) ? __( 'Publish Bulletin', BULLETINWP_PLUGIN_SLUG ) : __( 'Save Bulletin', BULLETINWP_PLUGIN_SLUG );
$loading_label = empty( $id ) ? __( 'Publishing...', BULLETINWP_PLUGIN_SLUG ) : __( 'Saving...', BULLETINWP_PLUGIN_SLUG );
?>

<div class="mb-8">
  <div class="mb-4">
    <label class="heading mb-0 mr-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-title' ) ?>"><?php _e( 'Title', BULLETINWP_PLUGIN_SLUG ) ?></label>
    <span class="text-xs"><?php _e( '(only visible for you)', BULLETINWP_PLUGIN_SLUG ) ?></span>
  </div>

  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-title' ) ?>"
         class="w-full"
         type="text"
         name="bulletinTitle"
         value="<?php echo esc_attr( $title ) ?>"
         placeholder="<?php echo esc_attr( __( 'Add title', BULLETINWP_PLUGIN_SLUG ) ) ?>"
  />
</div>

<div class="content">
  <div class="left-content">

    <!-- Choose bulletin type -->
    <div class="box-container p-4 md:p-8">
      <h3 class="mb-4">Choose bulletin type</h3>


      <div class="radio-group-wrapper flex flex-wrap -mx-4">
        <div class="w-1/2 lg:w-auto px-4">
          <div class="bulletin-type-wrapper">
            <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-top' ) ?>"
                   type="radio"
                   name="placement"
                   value="top"
                   data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-display-type-header-note-element, #' . BULLETINWP_PLUGIN_SLUG . '-display-header-top-option, #' . BULLETINWP_PLUGIN_SLUG . '-display-header-top-scroll-type' ) ?>"
                   <?php checked( $placement === 'top' ) ?>
            />

            <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-top' ) ?>">
              <div class="type-name">Header</div>

              <div class="bulletin-type-image">
                <div class="border"></div>
                <div class="checked-icon">
                  <img src="<?php echo esc_url( $images_dir . '/checked.svg' ) ?>" alt="">
                </div>
                <img src="<?php echo esc_url( $images_dir . '/tooltips/tooltip-header.svg' ) ?>" alt="">
              </div>
            </label>
          </div>
        </div>

        <div class="w-1/2 lg:w-auto px-4">
          <div class="bulletin-type-wrapper">
            <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-float-bottom' ) ?>"
                   type="radio"
                   name="placement"
                   value="float-bottom"
                   data-show-elements=""
                   data-hide-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-display-type-header-note-element, #' . BULLETINWP_PLUGIN_SLUG . '-display-header-top-option, #' . BULLETINWP_PLUGIN_SLUG . '-display-header-top-scroll-type' ) ?>"
                   <?php checked( $placement === 'float-bottom' ) ?>
            />

            <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-float-bottom' ) ?>">
              <div class="type-name">Floating at bottom</div>

              <div class="bulletin-type-image">
                <div class="border"></div>
                <div class="checked-icon">
                  <img src="<?php echo esc_url( $images_dir . '/checked.svg' ) ?>" alt="">
                </div>
                <img src="<?php echo esc_url( $images_dir . '/tooltips/tooltip-floating.svg' ) ?>" alt="">
              </div>
            </label>
          </div>
        </div>

        <div class="w-1/2 lg:w-auto px-4">
          <div class="bulletin-type-wrapper">
            <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-sticky-footer' ) ?>"
                   type="radio"
                   name="placement"
                   value="sticky-footer"
                   data-show-elements=""
                   data-hide-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-display-type-header-note-element, #' . BULLETINWP_PLUGIN_SLUG . '-display-header-top-option, #' . BULLETINWP_PLUGIN_SLUG . '-display-header-top-scroll-type' ) ?>"
                   <?php checked( $placement === 'sticky-footer' ) ?>
            />

            <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-sticky-footer' ) ?>">
              <div class="type-name">Sticky footer</div>

              <div class="bulletin-type-image">
                <div class="border"></div>
                <div class="checked-icon">
                  <img src="<?php echo esc_url( $images_dir . '/checked.svg' ) ?>" alt="">
                </div>
                <img src="<?php echo esc_url( $images_dir . '/tooltips/tooltip-sticky.svg' ) ?>" alt="">
              </div>
            </label>
          </div>
        </div>

        <div class="w-1/2 lg:w-auto px-4 <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>">
          <div class="bulletin-type-wrapper">
            <input id="<?php echo BULLETINWP_PLUGIN_SLUG . '-placement-corner' ?>"
                   type="radio"
                   name="placement"
                   value="<?php echo bulletinwp_fs()->is__premium_only() ? 'corner' : ''; ?>"
                   data-show-elements="<?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '#' . BULLETINWP_PLUGIN_SLUG . '-display-corner-option,' : '' ); ?> <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '#' . BULLETINWP_PLUGIN_SLUG . '-placement-corner-note' : '' ); ?>"
                   data-hide-elements="<?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '#' . BULLETINWP_PLUGIN_SLUG . '-text-alignment,' : '' ); ?> <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '#' . BULLETINWP_PLUGIN_SLUG . '-default-placement-note,' : '' ); ?> <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '#' . BULLETINWP_PLUGIN_SLUG . '-text-vertical-padding-wrapper,' : '' ) ?> <?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-display-type-header-note-element' ) ?> <?php echo esc_attr( ', #' . BULLETINWP_PLUGIN_SLUG . '-display-header-top-option' ) ?> <?php echo esc_attr( ', #' . BULLETINWP_PLUGIN_SLUG . '-display-header-top-scroll-type' ) ?>, <?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-add-image-wrapper' ) ?>"
                   <?php checked( $placement === 'corner' ) ?>
                   <?php echo esc_html( bulletinwp_fs()->is__premium_only() ? '' : 'disabled' ); ?>
            />

            <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-corner' ) ?>">
              <div class="type-name flex items-center justify-center">
                Corner

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <div class="bulletin-type-image">
                <div class="border"></div>
                <div class="checked-icon">
                  <img src="<?php echo esc_url( $images_dir . '/checked.svg' ) ?>" alt="">
                </div>
                <img src="<?php echo esc_url( $images_dir . '/tooltips/tooltip-corner.svg' ) ?>" alt="">
              </div>
            </label>
          </div>
        </div>

      </div>

      <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-display-type-header-note-element' ) ?>"
           class="mt-4"
           style="display: <?php echo esc_attr( ( $placement === 'top' ) ? 'block' : 'none' ); ?>"
      >
        <?php _e( 'NOTE:', BULLETINWP_PLUGIN_SLUG ) ?>&nbsp;<?php _e( 'if this site uses a fixed header, ', BULLETINWP_PLUGIN_SLUG ) ?>
        <a href="<?php echo esc_url( add_query_arg( [ 'page' =>  BULLETINWP_PLUGIN_SLUG . '-options-settings' ], 'admin.php' ) ) ?>" class="text-orange-100"><?php _e( 'please add the html tag in settings', BULLETINWP_PLUGIN_SLUG ) ?></a>
      </div>
    </div>

    <!-- Tabs options -->
    <div class="mt-16">
      <div class="tabs-wrapper">
        <div class="tabs">

          <div class="tab-item active"
               data-tab="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-message-tab' ) ?>">
            <div class="text-base md:text-lg uppercase font-bold">
              <?php _e( 'Message', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
            <div class="hidden md:block">
              <?php _e( 'What you want to say', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
          </div>

          <div class="tab-item"
               data-tab="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-display-tab' ) ?>">
            <div class="text-base md:text-lg uppercase font-bold">
              <?php _e( 'Display Options', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
            <div class="hidden md:block">
              <?php _e( 'How you want it to display', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
          </div>

          <div class="tab-item"
               data-tab="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-design-tab' ) ?>">
            <div class="text-base md:text-lg uppercase font-bold">
              <?php _e( 'Design', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
            <div class="hidden md:block">
              <?php _e( 'Customize your bulletin', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
          </div>

          <div class="tab-item"
               data-tab="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-placement-tab' ) ?>">
            <div class="text-base md:text-lg uppercase font-bold">
              <?php _e( 'Placement', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
            <div class="hidden md:block">
              <?php _e( 'Where you want to show it', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
          </div>

          <div class="tab-item"
               data-tab="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-expiry-tab' ) ?>">
            <div class="text-base md:text-lg uppercase font-bold">
              <?php _e( 'Expiry', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
            <div class="hidden md:block">
              <?php _e( 'Set expiry and countdown', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
          </div>

          <div class="tab-item"
               data-tab="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-advanced-tab' ) ?>">
            <div class="text-base md:text-lg uppercase font-bold">
              <?php _e( 'Advanced', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
            <div class="hidden md:block">
              <?php _e( 'Add custom CSS & more', BULLETINWP_PLUGIN_SLUG ) ?>
            </div>
          </div>

          <?php if ( is_multisite() && is_main_site() ) : ?>
            <div class="tab-item"
                 data-tab="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-network-tab' ) ?>">
              <div class="text-base md:text-lg uppercase font-bold">
                <?php _e( 'WP Network', BULLETINWP_PLUGIN_SLUG ) ?>
              </div>
              <div class="hidden md:block">
                <?php _e( 'Configure bulletins on networks', BULLETINWP_PLUGIN_SLUG ) ?>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="tabs-content">

          <!-- TAB - Message -->
          <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-message-tab' ) ?>" class="tab-pane active">
            <div class="flex items-center mb-8 md:mb-12">
              <div class="heading-icon mr-4">
                <img src="<?php echo esc_url( $images_dir . '/tab-icon/message.svg' ) ?>" alt="">
              </div>

              <div class="tab-heading">Message</div>
            </div>

            <div class="flex items-end justify-between">
              <div class="heading">Message</div>

              <div class="text-right modal-button-wrapper relative" data-overlay=".modal-overlay" data-id-modal="#support-markdown-modal">
                <a class="modal-button cursor-pointer"><?php _e( 'supports markdown, emojis & links', BULLETINWP_PLUGIN_SLUG ) ?></a>

                <div id="support-markdown-modal" class="modal opacity-0 pointer-events-none relative">
                  <div class="modal-overlay tooltip-overlay"></div>

                  <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG ) ?>-support-markdown box-container">
                    <div class="line-indicator"></div>

                    <div class="flex my-4 border-b-2">
                      <div class="w-1/2 px-4">
                        <div class="heading"><?php _e( 'Enter this', BULLETINWP_PLUGIN_SLUG ); ?></div>
                      </div>
                      <div class="w-1/2 px-4">
                        <div class="heading"><?php _e( 'To see this', BULLETINWP_PLUGIN_SLUG ); ?></div>
                      </div>
                    </div>

                    <div class="flex my-4">
                      <div class="w-1/2 px-4">
                        <p class="font-couriernew"><?php _e( '**This is bold**', BULLETINWP_PLUGIN_SLUG ); ?></p>
                      </div>
                      <div class="w-1/2 px-4">
                        <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG ) ?>-markdown-items"><?php _e( '**This is bold**', BULLETINWP_PLUGIN_SLUG ); ?></div>
                      </div>
                    </div>

                    <div class="flex my-4">
                      <div class="w-1/2 px-4">
                        <p class="font-couriernew"><?php _e( '*This is italic*', BULLETINWP_PLUGIN_SLUG ); ?></p>
                      </div>
                      <div class="w-1/2 px-4">
                        <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG ) ?>-markdown-items"><?php _e( '*This is italic*', BULLETINWP_PLUGIN_SLUG ); ?></div>
                      </div>
                    </div>

                    <div class="flex my-4">
                      <div class="w-1/2 px-4">
                        <p class="font-couriernew"><?php _e( '[link text](https://google.com)', BULLETINWP_PLUGIN_SLUG ); ?></p>
                      </div>
                      <div class="w-1/2 px-4">
                        <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG ) ?>-markdown-items"><?php _e( '[link text](https://google.com)', BULLETINWP_PLUGIN_SLUG ); ?></div>
                      </div>
                    </div>

                    <div class="flex my-4">
                      <div class="w-1/2 px-4">
                        <p class="font-couriernew"><?php echo htmlspecialchars( '<a href="https://google.com" target="_blank">link text (open in new tab)</a>' ); ?></p>
                      </div>
                      <div class="w-1/2 px-4">
                        <div><p><?php echo wp_kses( '<a href="https://google.com" target="_blank">link text (open in new tab)</a>', ['a' => ['href' => [], 'target' => []]] ); ?></p></div>
                      </div>
                    </div>

                    <div class="flex my-4">
                      <div class="w-1/2 px-4">
                        <p class="font-couriernew"><?php _e( 'paste codes `with backticks`', BULLETINWP_PLUGIN_SLUG ); ?></p>
                      </div>
                      <div class="w-1/2 px-4">
                        <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG ) ?>-markdown-items"><?php _e( 'paste codes `with backticks`', BULLETINWP_PLUGIN_SLUG ); ?></div>
                      </div>
                    </div>

                    <div class="flex my-4">
                      <div class="w-1/2 px-4">
                        <p class="font-couriernew"><?php _e( ':grin:', BULLETINWP_PLUGIN_SLUG ); ?></p>
                        <div class="text-left">
                          <a href="https://gist.github.com/rxaviers/7360908" target="_blank"><?php _e( 'view full list of emoji commands', BULLETINWP_PLUGIN_SLUG ); ?></a>
                        </div>
                      </div>
                      <div class="w-1/2 px-4">
                        <img src="<?php echo esc_url( $images_dir . '/sample-emoji.svg' ) ?>" alt="logo-text">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <div class="flex flex-wrap -mx-4">
              <div class="form-field form-field-text w-full lg:w-1/2 mb-4 lg:mb-0 px-4 is-required">
                <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-content' ) ?>">
                  <?php _e( 'Tablet and up', BULLETINWP_PLUGIN_SLUG ) ?>
                </label>
                <textarea id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-content' ) ?>"
                          class="form-input textarea-input w-full"
                          type="text"
                          name="content"
                          placeholder=""
                ><?php echo esc_textarea( $content ) ?></textarea>
              </div>

              <div class="form-field form-field-text w-full lg:w-1/2 mb-4 lg:mb-0 px-4">
                <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-mobile-content' ) ?>">
                  <?php _e( 'Mobile only (optional)', BULLETINWP_PLUGIN_SLUG ) ?>
                </label>
                <textarea id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-mobile-content' ) ?>"
                          class="form-input textarea-input w-full"
                          type="text"
                          name="mobileContent"
                          placeholder=""
                ><?php echo esc_textarea( $mobile_content ) ?></textarea>
              </div>
            </div>

            <div class="mt-8">
              <div class="heading flex items-center">
                <?php _e( 'Add multiple messages', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <hr class="my-4">

              <!-- Toggle -->
              <div class="checkbox-wrapper toggle-switch <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>"
                   data-checked-label="<?php echo esc_attr( __( 'Yes', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                   data-unchecked-label="<?php echo esc_attr( __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                   data-hide-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-messages-element, #' . BULLETINWP_PLUGIN_SLUG . '-rotation-style-element' ) ?>"
              >
                <input type="checkbox" name="isMultipleMessages" <?php checked( $is_multiple_messages ) ?> />
                <span class="label"><?php echo esc_html( $is_multiple_messages ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <!-- Repeater Messages -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-messages-element' ) ?>"
                     class="mt-4"
                     style="display: <?php echo esc_attr( $is_multiple_messages ? 'block' : 'none' ) ?>;"
                >

                  <?php if ( ! empty( $messages ) ) : ?>
                    <div class="repeater-container">
                      <?php foreach ( $messages as $key => $message ) : ?>
                        <div class="repeater-item mb-4">

                          <!-- Message -->
                          <div class="message -mx-4">
                            <div class="form-field form-field-text w-1/2 px-4 is-required">
                              <label><?php _e( 'Tablet and up', BULLETINWP_PLUGIN_SLUG ) ?></label>
                              <textarea class="form-input textarea-input w-full"
                                        type="text"
                                        name="messageContent[]"
                                        placeholder=""
                              ><?php echo esc_textarea( $message['content'] ) ?></textarea>
                            </div>

                            <div class="form-field form-field-text w-1/2 px-4">
                              <label><?php _e( 'Mobile only (optional)', BULLETINWP_PLUGIN_SLUG ) ?></label>
                              <textarea class="form-input textarea-input w-full"
                                        type="text"
                                        name="messageMobileContent[]"
                                        placeholder=""
                              ><?php echo esc_textarea( $message['mobile_content'] ) ?></textarea>
                            </div>
                          </div>

                          <!-- Controls -->
                          <div class="controls">
                            <div class="control-button add-button mr-2" title="Add Item">
                              <div class="control-button-icon"></div>
                            </div>

                            <?php if ( $key !== 0 ) : ?>
                              <div class="control-button delete-button" title="Remove Item">
                                <div class="control-button-icon"></div>
                              </div>
                            <?php else : ?>
                              <div class="control-button placeholder-button">
                                <div class="control-button-icon"></div>
                              </div>
                            <?php endif; ?>
                          </div>

                        </div>
                      <?php endforeach; ?>

                      <!-- For cloning more repeater fields -->
                      <div class="repeater-item mb-4 cloner">
                        <!-- Message -->
                        <div class="message -mx-4">
                          <div class="form-field form-field-text w-1/2 px-4 is-required">
                            <label><?php _e( 'Tablet and up', BULLETINWP_PLUGIN_SLUG ) ?></label>
                            <textarea class="form-input textarea-input w-full"
                                      type="text"
                                      name="messageContent[]"
                                      placeholder=""
                            ></textarea>
                          </div>

                          <div class="form-field form-field-text w-1/2 px-4">
                            <label><?php _e( 'Mobile only (optional)', BULLETINWP_PLUGIN_SLUG ) ?></label>
                            <textarea class="form-input textarea-input w-full"
                                      type="text"
                                      name="messageMobileContent[]"
                                      placeholder=""
                            ></textarea>
                          </div>
                        </div>

                        <!-- Controls -->
                        <div class="controls">
                          <div class="control-button add-button mr-2" title="Add Item">
                            <div class="control-button-icon"></div>
                          </div>

                          <div class="control-button delete-button" title="Remove Item">
                            <div class="control-button-icon"></div>
                          </div>
                        </div>
                      </div>

                    </div>
                  <?php endif; ?>
                </div>


                <!-- Rotation -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-rotation-style-element' ) ?>"
                     class="mt-4"
                     style="display: <?php echo esc_attr( $is_multiple_messages ? 'block' : 'none' ) ?>;"
                >
                  <div class="mb-4"><?php _e( 'Rotation style', BULLETINWP_PLUGIN_SLUG ) ?></div>

                  <!-- Radio group -->
                  <div class="radio-group-wrapper flex mb-4">
                    <div class="mr-4">
                      <label class="radio-wrapper">
                        <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-rotation-style-cycle' ) ?>"
                               type="radio"
                               name="rotationStyle"
                               value="cycle"
                               data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-cycle-speed-label' ) ?>"
                               <?php checked( $rotation_style === 'cycle' ) ?>
                        />
                        <span class="thumb"></span>
                        <span><?php _e( 'Cycle through', BULLETINWP_PLUGIN_SLUG ) ?></span>
                      </label>
                    </div>

                    <div>
                      <label class="radio-wrapper">
                        <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-rotation-style-marquee' ) ?>"
                               type="radio"
                               name="rotationStyle"
                               value="marquee"
                               data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-marquee-speed-label' ) ?>"
                               <?php checked( $rotation_style === 'marquee' ) ?>
                        />
                        <span class="thumb"></span>
                        <span><?php _e( 'Marquee style', BULLETINWP_PLUGIN_SLUG ) ?></span>
                      </label>
                    </div>
                  </div>

                  <!-- Speed option -->

                  <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-marquee-speed-element' ) ?>">
                    <div class="flex flex-wrap -mx-4 mb-2">
                      <div class="w-full lg:w-auto mb-4 lg:mb-0 px-4">
                        <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-cycle-speed-label' ) ?>"
                          style="display: <?php echo esc_attr( ( $rotation_style === 'cycle' ) ? 'block' : 'none' ); ?>">
                          <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-cycle-speed' ) ?>"><?php _e( 'Cycle speed', BULLETINWP_PLUGIN_SLUG ) ?></label>
                          <span class="text-xs"><?php _e( '(in ms, leave blank for default cycle speed)', BULLETINWP_PLUGIN_SLUG ) ?></span>
                          <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-cycle-speed' ) ?>"
                                 class="w-full"
                                 type="number"
                                 name="cycleSpeed"
                                 value="<?php echo esc_attr( $cycle_speed ) ?>"
                                 placeholder="300"
                          />
                        </div>
                        <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-marquee-speed-label' ) ?>"
                          style="display: <?php echo esc_attr( ( $rotation_style === 'marquee' ) ? 'block' : 'none' ); ?>">
                          <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-marquee-speed' ) ?>"><?php _e( 'Marquee speed', BULLETINWP_PLUGIN_SLUG ) ?></label>
                          <span class="text-xs"><?php _e( '(in ms, leave blank for default marquee speed)', BULLETINWP_PLUGIN_SLUG ) ?></span>
                          <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-marquee-speed' ) ?>"
                                 class="w-full"
                                 type="number"
                                 name="marqueeSpeed"
                                 value="<?php echo esc_attr( $marquee_speed ) ?>"
                                 placeholder="50000"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <div class="mt-8">
              <div class="heading flex items-center">
                <?php _e( 'Add button', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <hr class="my-4">

              <div class="checkbox-wrapper toggle-switch <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>"
                   data-checked-label="<?php _e( 'Yes', BULLETINWP_PLUGIN_SLUG ) ?>"
                   data-unchecked-label="<?php _e( 'No', BULLETINWP_PLUGIN_SLUG ) ?>"
                   data-hide-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-button-elements' ) ?>"
              >
                <input type="checkbox" name="addButton" <?php checked( $add_button ) ?> />
                <span class="label"><?php echo esc_html( $add_button ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-elements' ) ?>"
                     class="mt-4"
                     style="display: <?php echo esc_attr( $add_button ? 'block' : 'none' ) ?>;"
                >
                  <div class="flex flex-wrap -mx-4 mb-4">
                    <div class="form-field form-field-text w-full lg:w-1/2 mb-4 lg:mb-0 px-4 is-required">
                      <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-label' ) ?>"><?php _e( 'Button label', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-label' ) ?>"
                             class="form-input w-full"
                             type="text"
                             name="buttonLabel"
                             value="<?php echo esc_attr( $button_label ) ?>"
                             placeholder=""
                      />
                    </div>

                    <div class="form-field form-field-text w-full lg:w-1/2 mb-4 lg:mb-0 px-4">
                      <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-mobile-label' ) ?>"><?php _e( 'Button mobile label', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-mobile-label' ) ?>"
                             class="form-input w-full"
                             type="text"
                             name="buttonMobileLabel"
                             value="<?php echo esc_attr( $button_mobile_label ) ?>"
                             placeholder=""
                      />
                    </div>
                  </div>

                  <div class="flex flex-col md:flex-row mb-4">
                    <div class="form-field form-field-color-picker flex flex-col mr-0 md:mr-4 mb-4 md:mb-0 is-required">
                      <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-background-color' ) ?>"><?php _e( 'Background color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-background-color' ) ?>"
                             class="form-input color-picker-input"
                             type="text"
                             name="buttonBackgroundColor"
                             value="<?php echo esc_attr( $button_background_color ) ?>"
                             placeholder=""
                             data-default-color=""
                      />
                    </div>

                    <div class="form-field form-field-color-picker flex flex-col mr-0 md:mr-4 mb-4 md:mb-0 is-required">
                      <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-font-color' ) ?>"><?php _e( 'Font color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-font-color' ) ?>"
                             class="form-input color-picker-input"
                             type="text"
                             name="buttonFontColor"
                             value="<?php echo esc_attr( $button_font_color ) ?>"
                             placeholder=""
                             data-default-color=""
                      />
                    </div>

                    <div class="form-field form-field-color-picker flex flex-col mr-0 md:mr-4 mb-4 md:mb-0">
                      <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-hover-background-color' ) ?>"><?php _e( 'Hover background color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-hover-background-color' ) ?>"
                             class="form-input color-picker-input"
                             type="text"
                             name="buttonHoverBackgroundColor"
                             value="<?php echo esc_attr( ! empty( $button_hover_background_color ) ? $button_hover_background_color : $button_font_color ) ?>"
                             placeholder=""
                             data-default-color=""
                      />
                    </div>

                    <div class="form-field form-field-color-picker flex flex-col mr-0 md:mr-4 mb-4 md:mb-0">
                      <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-hover-font-color' ) ?>"><?php _e( 'Hover font color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-hover-font-color' ) ?>"
                             class="form-input color-picker-input"
                             type="text"
                             name="buttonHoverFontColor"
                             value="<?php echo esc_attr( ! empty( $button_hover_font_color ) ? $button_hover_font_color : $button_background_color ) ?>"
                             placeholder=""
                             data-default-color=""
                      />
                    </div>
                  </div>

                  <div class="mb-2"><?php _e( 'Button action', BULLETINWP_PLUGIN_SLUG ) ?></div>
                  <div class="radio-group-wrapper flex mb-4">
                    <div class="mr-4">
                      <label class="radio-wrapper">
                        <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-action-link' ) ?>"
                               type="radio"
                               name="buttonAction"
                               value="link"
                               data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-button-link-element, #' . BULLETINWP_PLUGIN_SLUG . '-button-target-element' ) ?>"
                               <?php checked( $button_action === 'link' ) ?>
                        />
                        <span class="thumb"></span>
                        <span><?php _e( 'Link', BULLETINWP_PLUGIN_SLUG ) ?></span>
                      </label>
                    </div>

                    <div class="mr-4">
                      <label class="radio-wrapper">
                        <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-action-custom-js-event' ) ?>"
                               type="radio"
                               name="buttonAction"
                               value="custom-js-event"
                               data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-button-js-event-element' ) ?>"
                               <?php checked( $button_action === 'custom-js-event' ) ?>
                        />
                        <span class="thumb"></span>
                        <span><?php _e( 'Custom JS Event', BULLETINWP_PLUGIN_SLUG ) ?></span>
                      </label>
                    </div>

                    <div class="mr-4">
                      <label class="radio-wrapper">
                        <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-action-dismiss-bulletin' ) ?>"
                               type="radio"
                               name="buttonAction"
                               value="dismiss-bulletin"
                               data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-button-cookie-expiry-element' ) ?>"
                               <?php checked( $button_action === 'dismiss-bulletin' ) ?>
                        />
                        <span class="thumb"></span>
                        <span><?php _e( 'Dismiss Bulletin', BULLETINWP_PLUGIN_SLUG ) ?></span>
                      </label>
                    </div>

                    <?php if ( BULLETINWP::instance()->pro->maybe_easy_popups_plugin_is_activated() ) : ?>
                      <div>
                        <label class="radio-wrapper">
                          <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-action-trigger-easy-popup' ) ?>"
                                 type="radio"
                                 name="buttonAction"
                                 value="trigger-easy-popup"
                                 data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-button-easy-popup-element' ) ?>"
                                 <?php checked( $button_action === 'trigger-easy-popup' ) ?>
                          />
                          <span class="thumb"></span>
                          <span><?php _e( 'Trigger Easy Popup', BULLETINWP_PLUGIN_SLUG ) ?></span>
                        </label>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-link-element' ) ?>"
                    class="form-field form-field-text mb-4 is-required"
                    style="display: <?php echo esc_attr( $button_action === 'link' ? 'block' : 'none' ); ?>;"
                  >
                    <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-link' ) ?>"><?php _e( 'Button link', BULLETINWP_PLUGIN_SLUG ) ?></label>
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-link' ) ?>"
                           class="form-input w-full"
                           type="text"
                           name="buttonLink"
                           value="<?php echo esc_attr( $button_link ) ?>"
                           placeholder="https://example.com"
                    />
                  </div>

                  <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-target-element' ) ?>"
                       class="mb-4"
                       style="display: <?php echo esc_attr( $button_action === 'link' ? 'block' : 'none' ); ?>;"
                  >
                    <div class="mb-2"><?php _e( 'Button target', BULLETINWP_PLUGIN_SLUG ) ?></div>

                    <div class="radio-group-wrapper flex">

                      <div class="mr-4">
                        <label class="radio-wrapper">
                          <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-target-same-tab' ) ?>"
                                 type="radio"
                                 name="buttonTarget"
                                 value="same-tab"
                                 <?php checked( $button_target === 'same-tab' ) ?>
                          />
                          <span class="thumb"></span>
                          <span><?php _e( 'Same tab', BULLETINWP_PLUGIN_SLUG ) ?></span>
                        </label>
                      </div>
                      <div>
                        <label class="radio-wrapper">
                          <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-target-new-tab' ) ?>"
                                 type="radio"
                                 name="buttonTarget"
                                 value="new-tab"
                                 <?php checked( $button_target === 'new-tab' ) ?>
                          />
                          <span class="thumb"></span>
                          <span><?php _e( 'New tab', BULLETINWP_PLUGIN_SLUG ) ?></span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-js-event-element' ) ?>"
                       class="form-field form-field-text mb-4 is-required"
                       style="display: <?php echo esc_attr( $button_action === 'custom-js-event' ? 'block' : 'none' ); ?>;"
                  >
                    <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-js-event' ) ?>"><?php _e( 'Button JS Event', BULLETINWP_PLUGIN_SLUG ) ?></label>
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-js-event' ) ?>"
                           class="form-input w-full"
                           type="text"
                           name="buttonJSEvent"
                           value="<?php echo esc_attr( $button_js_event ) ?>"
                           placeholder="ShowMyModal();"
                    />
                  </div>

                  <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-cookie-expiry-element' ) ?>"
                       class="form-field form-field-text mb-4 is-required"
                       style="display: <?php echo esc_attr( $button_action === 'dismiss-bulletin' ? 'block' : 'none' ); ?>;"
                  >
                    <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . 'button-cookie-expiry' ) ?>"><?php _e( 'Cookie expiry (in hours). Use -1 to dismiss forever', BULLETINWP_PLUGIN_SLUG ) ?></label>
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . 'button-cookie-expiry' ) ?>"
                           class="form-input w-full"
                           type="number"
                           name="buttonCookieExpiry"
                           value="<?php echo esc_attr( $button_cookie_expiry ) ?>"
                           placeholder=""
                    />
                  </div>

                  <?php if ( BULLETINWP::instance()->pro->maybe_easy_popups_plugin_is_activated() ) : ?>
                    <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-easy-popup-element' ) ?>"
                         class="form-field form-field-select easy-popups-select-wrapper mb-4 is-required"
                         style="display: <?php echo esc_attr( $button_action === 'trigger-easy-popup' ? 'block' : 'none' ); ?>"
                    >
                      <div><?php _e( 'Select easy popup', BULLETINWP_PLUGIN_SLUG ) ?></div>

                      <select id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-select-easy-popup' ) ?>"
                              class="form-input select2 infinity easy-popup-select"
                              name="buttonEasyPopup"
                      >
                        <option value="" <?php echo selected( $button_easy_popup === '' ) ?>>
                          <?php _e( 'Select easy popup', BULLETINWP_PLUGIN_SLUG ) ?>
                        </option>

                        <?php foreach ( $easy_popup_options as $option ) : ?>
                          <option value="<?php echo esc_attr( $option['value'] ) ?>"
                            <?php echo selected( $button_easy_popup === $option['value'] ); ?>
                          >
                            <?php echo esc_html( $option['label'] ) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  <?php endif; ?>

                  <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-align-element' ) ?>"
                      class="mb-4"
                  >
                    <div class="mb-2"><?php _e( 'Button alignment', BULLETINWP_PLUGIN_SLUG ) ?></div>
                    <div class="radio-group-wrapper flex">
                      <div class="mr-4">
                        <label class="radio-wrapper">
                          <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-align-right' ) ?>"
                                 type="radio"
                                 name="buttonAlign"
                                 value="right"
                                 <?php checked( $button_align === 'right' ) ?>
                          />
                          <span class="thumb"></span>
                          <span><?php _e( 'Align right', BULLETINWP_PLUGIN_SLUG ) ?></span>
                        </label>
                      </div>

                      <div>
                        <label class="radio-wrapper">
                          <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-button-align-content' ) ?>"
                                 type="radio"
                                 name="buttonAlign"
                                 value="content"
                                 <?php checked( $button_align === 'content' ) ?>
                          />
                          <span class="thumb"></span>
                          <span><?php _e( 'Aligned with content', BULLETINWP_PLUGIN_SLUG ) ?></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <!-- Allow user to dismiss -->
            <div class="mt-8">
              <div class="heading flex items-center">
                <?php _e( 'Allow user to dismiss bulletin?', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <hr class="my-4">

              <div class="checkbox-wrapper toggle-switch <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>"
                   data-checked-label="<?php _e( 'Yes', BULLETINWP_PLUGIN_SLUG ) ?>"
                   data-unchecked-label="<?php _e( 'No', BULLETINWP_PLUGIN_SLUG ) ?>"

                   data-hide-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-cookie-expiry-element' ) ?>"
              >
                <input type="checkbox" name="isDismissable" <?php checked( $is_dismissable ) ?> />
                <span class="label"><?php echo esc_html( $is_dismissable ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-cookie-expiry-element' ) ?>"
                     class="form-field form-field-text mt-4 is-required"
                     style="display: <?php echo esc_attr( $is_dismissable ? 'block' : 'none' ) ?>;"
                >
                  <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-cookie-expiry' ) ?>"><?php _e( 'Cookie expiry (in hours). Use -1 to dismiss forever', BULLETINWP_PLUGIN_SLUG ) ?></label>
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-cookie-expiry' ) ?>"
                         class="form-input w-full"
                         type="number"
                         name="cookieExpiry"
                         value="<?php echo esc_attr( $cookie_expiry ) ?>"
                         placeholder=""
                  />
                </div>
              <?php endif; ?>
            </div>

          </div>

          <!-- TAB - Display -->
          <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-display-tab' ) ?>" class="tab-pane">
            <div class="flex items-center mb-8 md:mb-12">
              <div class="heading-icon mr-4">
                <img src="<?php echo esc_url( $images_dir . '/tab-icon/message.svg' ) ?>" alt="">
              </div>

              <div class="tab-heading">Display options</div>
            </div>

            <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-display-header-top-option' ) ?>"
                 class="mt-4 "
                 style="display: <?php echo esc_attr( ( $placement === 'top' ) ? 'block' : 'none' ); ?>"
            >

              <div class="heading"><?php _e( 'Header banner display', BULLETINWP_PLUGIN_SLUG ) ?></div>

              <hr class="my-4">

              <div class="radio-group-wrapper flex">
                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-header-banner-above' ) ?>"
                           type="radio"
                           name="headerBannerStyle"
                           value="above-header"
                           <?php checked( $header_banner_style === 'above-header' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Above header', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>

                <div>
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-header-banner-below' ) ?>"
                           type="radio"
                           name="headerBannerStyle"
                           value="below-header"
                           <?php checked( $header_banner_style === 'below-header' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Below header', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>
              </div>
            </div>

            <?php if ( $site_has_fixed_header ) : ?>
              <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-display-header-top-scroll-type' ) ?>"
                   class="mt-8"
                   style="display: <?php echo esc_attr( ( $placement === 'top' ) ? 'block' : 'none' ); ?>">

                <div class="heading mb-2"><?php _e( 'Header banner scroll type', BULLETINWP_PLUGIN_SLUG ) ?></div>

                <div class="radio-group-wrapper flex">
                  <div class="mr-4">
                    <label class="radio-wrapper">
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-header-banner-scroll-static' ) ?>"
                             type="radio"
                             name="headerBannerScroll"
                             value="static"
                             <?php checked( $header_banner_scroll === 'static' ) ?>
                      />
                      <span class="thumb"></span>
                      <span><?php _e( 'Static', BULLETINWP_PLUGIN_SLUG ) ?></span>
                    </label>
                  </div>

                  <div>
                    <label class="radio-wrapper">
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-header-banner-scroll-fixed' ) ?>"
                             type="radio"
                             name="headerBannerScroll"
                             value="fixed"
                             <?php checked( $header_banner_scroll === 'fixed' ) ?>
                      />
                      <span class="thumb"></span>
                      <span><?php _e( 'Fixed', BULLETINWP_PLUGIN_SLUG ) ?></span>
                    </label>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
              <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-display-corner-option' ) ?>"
                   class="mt-8"
                   style="display: <?php echo esc_attr( ( $placement === 'corner' ) ? 'block' : 'none' ); ?>"
              >

                <div class="heading"><?php _e( 'Corner options', BULLETINWP_PLUGIN_SLUG ) ?></div>

                <hr class="my-4">

                <div class="flex">
                  <div class="mr-4">
                    <label class="radio-wrapper">
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-corner-top-left' ) ?>"
                             type="radio"
                             name="placementCornerPosition"
                             value="top-left"
                             <?php checked( $placement_corner_options === 'top-left' ) ?>
                      />

                      <span class="thumb"></span>
                      <span><?php _e( 'Top left', BULLETINWP_PLUGIN_SLUG ) ?></span>
                    </label>
                  </div>

                  <div class="mr-4">
                    <label class="radio-wrapper">
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-corner-top-right' ) ?>"
                             type="radio"
                             name="placementCornerPosition"
                             value="top-right"
                             <?php checked( $placement_corner_options === 'top-right' ) ?>
                      />

                      <span class="thumb"></span>
                      <span><?php _e( 'Top right', BULLETINWP_PLUGIN_SLUG ) ?></span>
                    </label>
                  </div>

                  <div class="mr-4">
                    <label class="radio-wrapper">
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-corner-bottom-left' ) ?>"
                             type="radio"
                             name="placementCornerPosition"
                             value="bottom-left"
                             <?php checked( $placement_corner_options === 'bottom-left' ) ?>
                      />

                      <span class="thumb"></span>
                      <span><?php _e( 'Bottom left', BULLETINWP_PLUGIN_SLUG ) ?></span>
                    </label>
                  </div>

                  <div>
                    <label class="radio-wrapper">
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-corner-bottom-right' ) ?>"
                             type="radio"
                             name="placementCornerPosition"
                             value="bottom-right"
                             <?php checked( $placement_corner_options === 'bottom-right' ) ?>
                      />

                      <span class="thumb"></span>
                      <span><?php _e( 'Bottom right', BULLETINWP_PLUGIN_SLUG ) ?></span>
                    </label>
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <div class="mt-8">
              <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-default-placement-note' ) ?>"
                   style="display: <?php echo esc_attr( ( $placement !== 'corner' ) ? 'block' : 'none' ); ?>">
                <span class="heading mr-2"><?php _e( 'Content max-width', BULLETINWP_PLUGIN_SLUG ) ?></span>
                <span class="text-xs"><?php _e( '(in px, leave blank for 100% width)', BULLETINWP_PLUGIN_SLUG ) ?></span>
              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-corner-note' ) ?>"
                     style="display: <?php echo esc_attr( ( $placement === 'corner' ) ? 'block' : 'none' ); ?>">
                  <span class="heading mr-2"><?php _e( 'Element max-width', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  <span class="text-xs"><?php _e( '(in px, leave blank for max-width of 300px)', BULLETINWP_PLUGIN_SLUG ) ?></span>
                </div>
              <?php endif; ?>

              <hr class="my-4">

              <div>
                <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-content-max-width' ) ?>"
                       type="number"
                       name="contentMaxWidth"
                       value="<?php echo esc_attr( $content_max_width ) ?>"
                       placeholder=""
                />
              </div>
            </div>

            <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-text-alignment' ) ?>"
                 class="mt-8"
                 style="display: <?php echo esc_attr( ( $placement === 'corner' ) ? 'none' : 'block' ); ?>"
            >
              <div class="heading mb-2"><?php _e( 'Text alignment', BULLETINWP_PLUGIN_SLUG ) ?></div>

              <hr class="my-4">

              <div class="flex">
                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-text-alignment-center' ) ?>"
                           type="radio"
                           name="textAlignment"
                           value="center"
                           <?php checked( $text_alignment === 'center' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Center', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>

                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-text-alignment-left' ) ?>"
                           type="radio"
                           name="textAlignment"
                           value="left"
                           <?php checked( $text_alignment === 'left' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Left', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>

                <div>
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-text-alignment-right' ) ?>"
                           type="radio"
                           name="textAlignment"
                           value="right"
                           <?php checked( $text_alignment === 'right' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Right', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB - Design -->
          <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-design-tab' ) ?>" class="tab-pane">
            <div class="flex items-center mb-8 md:mb-12">
              <div class="heading-icon mr-4">
                <img src="<?php echo esc_url( $images_dir . '/tab-icon/message.svg' ) ?>" alt="">
              </div>

              <div class="tab-heading">Design</div>
            </div>

            <div>
              <div class="heading">Colors</div>

              <hr class="my-4">

              <div class="flex flex-col md:flex-row mb-4">
                <div class="form-field form-field-color-picker flex flex-col mr-0 md:mr-4 mb-4 md:mb-0 is-required">
                  <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-background-color' ) ?>"><?php _e( 'Background color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-background-color' ) ?>"
                         class="form-input color-picker-input"
                         type="text"
                         name="backgroundColor"
                         value="<?php echo esc_attr( $background_color ) ?>"
                         placeholder=""
                         data-default-color=""
                  />
                </div>

                <div class="form-field form-field-color-picker flex flex-col is-required">
                  <label class="mb-2" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-font-color' ) ?>"><?php _e( 'Font color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-font-color' ) ?>"
                         class="form-input color-picker-input"
                         type="text"
                         name="fontColor"
                         value="<?php echo esc_attr( $font_color ) ?>"
                         placeholder=""
                         data-default-color=""
                  />
                </div>
              </div>
            </div>

            <div class="mt-8">
              <div>
                <span class="heading mr-2"> <?php _e( 'Font Size', BULLETINWP_PLUGIN_SLUG ) ?> </span>
                <span class="text-xs"><?php _e( '(in px, leave blank for default font-size)', BULLETINWP_PLUGIN_SLUG ) ?></span>
              </div>

              <hr class="my-4">

              <div class="flex flex-wrap -mx-4 mb-8">
                <div class="w-full lg:w-auto mb-4 lg:mb-0 px-4">
                  <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-font-size' ) ?>"><?php _e( 'Desktop', BULLETINWP_PLUGIN_SLUG ) ?></label>
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-font-size' ) ?>"
                         class="w-full"
                         type="number"
                         name="fontSize"
                         value="<?php echo esc_attr( $font_size ) ?>"
                         placeholder="16"
                  />
                </div>

                <div class="w-full lg:w-auto mb-4 lg:mb-0 px-4">
                  <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-mobile-font-size' ) ?>"><?php _e( 'Mobile', BULLETINWP_PLUGIN_SLUG ) ?></label>
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-mobile-font-size' ) ?>"
                         class="w-full"
                         type="number"
                         name="fontSizeMobile"
                         value="<?php echo esc_attr( $font_size_mobile ) ?>"
                         placeholder="16"
                  />
                </div>
              </div>
            </div>

            <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-text-vertical-padding-wrapper' ) ?>"
              class="mt-8">
              <div>
                <span class="heading mr-2"> <?php _e( 'Text Vertical Padding', BULLETINWP_PLUGIN_SLUG ) ?> </span>
                <span class="text-xs"><?php _e( '(in px, leave blank for default vertical padding)', BULLETINWP_PLUGIN_SLUG ) ?></span>
              </div>

              <hr class="my-4">

              <div class="flex flex-wrap -mx-4">
                <div class="w-full lg:w-auto mb-4 lg:mb-0 px-4">
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-text-vertical-padding' ) ?>"
                         class="w-full"
                         type="number"
                         name="textVerticalPadding"
                         value="<?php echo esc_attr( $text_vertical_padding ) ?>"
                         placeholder="12"
                  />
                </div>
              </div>
            </div>

            <!-- ADD ICON -->
            <div class="mt-8">
              <div class="heading flex items-center">
                <?php _e( 'Add icon', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <hr class="my-4">

              <!-- Radio Group -->
              <div class="radio-group-wrapper flex mb-4 <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>">
                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-add-icon-none' ) ?>"
                           type="radio"
                           name="addIcon"
                           value="none"
                           data-show-elements=""
                           <?php checked( $add_icon === 'none' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'None', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>

                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-add-icon-from-set' ) ?>"
                           type="radio"
                           name="addIcon"
                           value="from-set"
                           data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-icon-from-set-element' ) ?>"
                           <?php checked( $add_icon === 'from-set' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'From a set', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>

                <div>
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-add-icon-upload-own' ) ?>"
                           type="radio"
                           name="addIcon"
                           value="upload-own"
                           data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-upload-icon-element' ) ?>"
                           <?php checked( $add_icon === 'upload-own' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Upload my own', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>
              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <!-- Icon from set -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-icon-from-set-element' ) ?>"
                     class="form-field form-field-select icons-select-wrapper is-required"
                     style="display: <?php echo esc_attr( ( $add_icon === 'from-set' ) ? 'block' : 'none' ); ?>"
                >
                  <div><?php _e( 'Select icon from set', BULLETINWP_PLUGIN_SLUG ) ?></div>

                  <select id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-select-icon-set' ) ?>"
                          class="form-input select2 infinity icons-select" name="iconNameFromSet"
                  >
                    <option value="" <?php echo selected( $icon_name_from_set === '' ) ?>>
                      <?php _e( 'Select icons', BULLETINWP_PLUGIN_SLUG ) ?>
                    </option>

                    <?php foreach ( $icon_options as $option ) : ?>
                      <option value="<?php echo esc_attr( $option['value'] ) ?>"
                        <?php echo selected( $icon_name_from_set === $option['value'] ); ?>
                      >
                        <?php echo esc_html( $option['label'] ) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Upload own icon -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-upload-icon-element' ) ?>"
                     style="display: <?php echo esc_attr( ( $add_icon === 'upload-own' ) ? 'block' : 'none' ); ?>"
                >
                  <div><?php _e( 'Upload icon', BULLETINWP_PLUGIN_SLUG ) ?></div>
                  <div class="form-field form-field-upload upload-image-button-wrapper is-required">
                    <input type="hidden" name="iconAttachmentID" value="<?php echo esc_attr( $icon_attachment_id ) ?>" class="form-input image-attachment-id">

                    <button class="upload-image-button btn-default" type="button">
                      <?php _e( 'Upload', BULLETINWP_PLUGIN_SLUG ) ?>
                    </button>

                    <div class="image-preview-wrapper mt-4"
                         style="display:<?php echo esc_attr( ( ! empty( $icon_attachment_id ) ) ? 'block': 'none' ); ?>"
                    >
                      <img class="image-preview" width="100"
                           src="<?php echo esc_url( ( ! empty( $icon_attachment_id ) ) ? wp_get_attachment_image_src( $icon_attachment_id )[0] : '' ) ?>"
                      />
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <!-- ADD IMAGE -->
            <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-add-image-wrapper' ) ?>"
                 class="mt-8"
                 style="display: <?php echo esc_attr( ( $placement !== 'corner' ) ? 'block' : 'none' ) ?>;"
            >
              <div class="heading flex items-center">
                <?php _e( 'Add image', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <hr class="my-4">

              <!-- Toggle -->
              <div class="checkbox-wrapper toggle-switch <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>"
                   data-checked-label="<?php echo esc_attr( __( 'Yes', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                   data-unchecked-label="<?php echo esc_attr( __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                   data-hide-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-upload-image-element' ) ?>, #<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-image-alignment-element' ) ?>, #<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-image-max-width-element' ) ?>"
              >
                <input type="checkbox" name="addImage" <?php checked( $add_image ) ?> />
                <span class="label"><?php echo esc_html( $add_image ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <!-- Upload image -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-upload-image-element' ) ?>"
                     class="mt-4"
                     style="display: <?php echo esc_attr( $add_image ? 'block' : 'none' ); ?>"
                >
                  <div><?php _e( 'Upload image', BULLETINWP_PLUGIN_SLUG ) ?></div>

                  <div class="form-field form-field-upload upload-image-button-wrapper is-required">
                    <input type="hidden" name="imageAttachmentID" value="<?php echo esc_attr( $image_attachment_id ) ?>" class="form-input image-attachment-id">

                    <button class="upload-image-button btn-default" type="button">
                      <?php _e( 'Upload', BULLETINWP_PLUGIN_SLUG ) ?>
                    </button>

                    <div class="image-preview-wrapper mt-4"
                        style="display:<?php echo esc_attr( ( ! empty( $image_attachment_id ) ) ? 'block': 'none' ); ?>"
                    >
                      <img class="image-preview" width="100"
                        src="<?php echo esc_url( ( ! empty( $image_attachment_id ) ) ? wp_get_attachment_image_src( $image_attachment_id )[0] : '' ) ?>" />
                    </div>
                  </div>
                </div>

                <!-- Image alignment -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-image-alignment-element' ) ?>"
                     class="mt-4"
                     style="display: <?php echo esc_attr( $add_image ? 'block' : 'none' ); ?>"
                >
                  <div class="mb-4"><?php _e( 'Image alignment', BULLETINWP_PLUGIN_SLUG ) ?></div>

                  <!-- Radio group -->
                  <div class="radio-group-wrapper flex mb-4">
                    <div class="mr-4">
                      <label class="radio-wrapper">
                        <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-image-left-alignment' ) ?>"
                              type="radio"
                              name="imageAlignment"
                              value="left"
                              <?php checked( $image_alignment === 'left' ) ?>
                        />
                        <span class="thumb"></span>
                        <span><?php _e( 'Left', BULLETINWP_PLUGIN_SLUG ) ?></span>
                      </label>
                    </div>

                    <div>
                      <label class="radio-wrapper">
                        <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-image-left-alignment' ) ?>"
                              type="radio"
                              name="imageAlignment"
                              value="right"
                              <?php checked( $image_alignment === 'right' ) ?>
                        />
                        <span class="thumb"></span>
                        <span><?php _e( 'Right', BULLETINWP_PLUGIN_SLUG ) ?></span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Image max-width -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-image-max-width-element' ) ?>"
                     class="mt-4"
                     style="display: <?php echo esc_attr( $add_image ? 'block' : 'none' ); ?>"
                >
                  <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-image-max-width' ) ?>">
                    <span><?php _e( 'Max width', BULLETINWP_PLUGIN_SLUG ) ?></span>
                    <span class="text-xs"><?php _e( '(in px, leave blank for max-width of 225px)', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-image-max-width' ) ?>"
                         class="w-full"
                         type="number"
                         name="imageMaxWidth"
                         value="<?php echo esc_attr( $image_max_width ) ?>"
                         placeholder="225"
                  />
                </div>
              <?php endif; ?>
            </div>

            <!-- FONTS -->
            <div class="mt-8">
              <div class="heading flex items-center">
                <?php _e( 'Fonts', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <hr class="my-4">

              <!-- Radio group -->
              <div class="radio-group-wrapper flex mb-4 <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>">
                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-fonts-inherit' ) ?>"
                           type="radio"
                           name="fonts"
                           value="inherit"
                           <?php checked( $fonts === 'inherit' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Inherit from site', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>

                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-fonts-google-fonts' ) ?>"
                           type="radio"
                           name="fonts"
                           value="google-fonts"
                           data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-google-fonts-element' ) ?>"
                           <?php checked( $fonts === 'google-fonts' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Google fonts', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>
              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <!-- Google fonts -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-google-fonts-element' ) ?>"
                     style="display: <?php echo esc_attr( ( $fonts === 'google-fonts' ) ? 'block' : 'none' ); ?>"
                >
                  <div class="form-field form-field-select google-fonts-select-wrapper is-required"
                       data-font-target="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-google-font-display' ) ?>"
                  >
                    <select class="form-input select2 google-fonts-select" name="googleFonts">
                      <option value="" <?php echo selected( $google_fonts === '' ) ?> >
                        <?php _e( 'Select Google Fonts', BULLETINWP_PLUGIN_SLUG ) ?>
                      </option>

                      <?php foreach ( $google_font_options as $option ) : ?>
                        <option value="<?php echo esc_attr( $option ) ?>"
                          <?php echo esc_html( ( stripos( $option, '-----' ) !== false ) ? 'disabled' : '' ); ?>
                          <?php echo selected( $google_fonts === $option ); ?>
                        >
                        <?php
                        if ( strpos( $option, ' - Arabic' ) ) {
                          echo esc_html( str_replace( ' - Arabic', '', $option ) );
                        } elseif ( strpos( $option, ' - Hebrew' ) ) {
                          echo esc_html( str_replace( ' - Hebrew', '', $option ) );
                        } else {
                          echo esc_html( $option );
                        }
                        ?>
                        </option>
                      <?php endforeach; ?>
                    </select>

                    <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-google-font-display' ) ?>"
                         class="mt-4 google-font-display"
                    >
                      <h3 class="regular-font" style="display: <?php echo esc_attr( ( strpos( $google_fonts, ' - Arabic' ) ) ? 'none' : 'block' ); ?>; font-family: inherit;"><?php _e( 'This is how the content font is going to look!', BULLETINWP_PLUGIN_SLUG ) ?></h3>
                      <h3 class="arabic-font" dir="rtl" lang="ar" style="display: <?php echo esc_attr( ( strpos( $google_fonts, ' - Arabic' ) ) ? 'block' : 'none' ); ?>; font-family: inherit;"><?php _e( 'هذه هي الطريقة التي سيبدو بها خط المحتوى!', BULLETINWP_PLUGIN_SLUG ) ?></h3>
                      <h3 class="hebrew-font" dir="rtl" lang="he" style="display: <?php echo esc_attr( ( strpos( $google_fonts, ' - Hebrew' ) ) ? 'block' : 'none' ); ?>; font-family: inherit;"><?php _e( 'כך גופן התוכן הולך להיראות!', BULLETINWP_PLUGIN_SLUG ) ?></h3>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>

          </div>

          <!-- TAB - Placement -->
          <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-tab' ) ?>" class="tab-pane">
            <div class="flex items-center mb-8 md:mb-12">
              <div class="heading-icon mr-4">
                <img src="<?php echo esc_url( $images_dir . '/tab-icon/message.svg' ) ?>" alt="">
              </div>

              <div class="tab-heading">Placement</div>
            </div>

            <!-- Actual Pro Feat -->
            <div class="heading flex items-center">
              <?php _e( 'By content', BULLETINWP_PLUGIN_SLUG ) ?>

              <?php if ( bulletinwp_fs()->is__premium_only() ) :
              else :?>
                <div class="pro-pill">PRO</div>
              <?php endif; ?>
            </div>

            <hr class="my-4">

            <div class="radio-group-wrapper flex <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>">
              <div class="mr-4">
                <label class="radio-wrapper">
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-by-content-everywhere' ) ?>"
                         type="radio"
                         name="placementByContent"
                         value="everywhere"
                         <?php checked( $placement_by_content === 'everywhere' ) ?>
                  />
                  <span class="thumb"></span>
                  <span><?php _e( 'Show everywhere', BULLETINWP_PLUGIN_SLUG ) ?></span>
                </label>
              </div>

              <div>
                <label class="radio-wrapper">
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-by-content-selected-content' ) ?>"
                         type="radio"
                         name="placementByContent"
                         value="selected-content"
                         data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-placement-selected-content-include-element' ) ?>, #<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-selected-content-exclude-element' ) ?>"
                         <?php checked( $placement_by_content === 'selected-content' ) ?>
                  />
                  <span class="thumb"></span>
                  <span><?php _e( 'Show only on certain content', BULLETINWP_PLUGIN_SLUG ) ?></span>
                </label>
              </div>
            </div>

            <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
              <!-- Include -->
              <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-selected-content-include-element' ) ?>"
                   class="mt-4"
                   style="display: <?php echo esc_attr( ( $placement_by_content === 'selected-content' ? 'block' : 'none' ) ); ?>;"
              >
                <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-selected-content-include' ) ?>">
                  <strong><?php _e( 'Include', BULLETINWP_PLUGIN_SLUG ) ?></strong> <?php _e( 'this bulletin on the following pages or posts (one per line, leave empty to include everywhere)', BULLETINWP_PLUGIN_SLUG ) ?>
                </label>

                <div>
                  <textarea id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-selected-content-include' ) ?>"
                            class="form-input textarea-input w-full"
                            name="placementSelectedContentInclude"
                            placeholder="/products/(.*)"
                  ><?php echo esc_textarea( is_array( $placement_selected_content_include ) ? implode( "\n", $placement_selected_content_include ) : '' ); ?></textarea>
                </div>

                <div class="text-sm">
                  <i><?php _e( 'The domain part of the URL will be stripped automatically.', BULLETINWP_PLUGIN_SLUG ) ?></i>
                  <br />
                  <i><?php _e( 'Use (.*) wildcards to address multiple URLs under a given path.', BULLETINWP_PLUGIN_SLUG ) ?></i>
                </div>
              </div>

              <!-- Exclude -->
              <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-selected-content-exclude-element' ) ?>"
                   class="mt-4"
                   style="display: <?php echo esc_attr( $placement_by_content === 'selected-content' ? 'block' : 'none' ); ?>;"
              >
                <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-selected-content-exclude' ) ?>">
                  <strong><?php _e( 'Exclude', BULLETINWP_PLUGIN_SLUG ) ?></strong> <?php _e( 'this bulletin on the following pages or posts (one per line, leave empty to exclude nowhere)', BULLETINWP_PLUGIN_SLUG ) ?>
                </label>

                <div>
                  <textarea id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-selected-content-exclude' ) ?>"
                            class="form-input textarea-input w-full"
                            name="placementSelectedContentExclude"
                            placeholder="/products/(.*)"
                  ><?php echo esc_textarea( is_array( $placement_selected_content_exclude ) ? implode( "\n", $placement_selected_content_exclude ) : '' ); ?></textarea>
                </div>

                <div class="text-sm">
                  <i><?php _e( 'The domain part of the URL will be stripped automatically.', BULLETINWP_PLUGIN_SLUG ) ?></i>
                  <br />
                  <i><?php _e( 'Use (.*) wildcards to address multiple URLs under a given path.', BULLETINWP_PLUGIN_SLUG ) ?></i>
                </div>
              </div>
            <?php endif; ?>

            <div class="mt-8">
              <div class="heading flex items-center">
                <?php _e( 'By user', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <hr class="my-4">

              <div class="radio-group-wrapper flex <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>">
                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-by-user-everyone' ) ?>"
                           type="radio"
                           name="placementByUser"
                           value="everyone"
                           <?php checked( $placement_by_user === 'everyone' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Show for everyone', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>

                <div class="mr-4">
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-by-user-logged-in-users' ) ?>"
                           type="radio"
                           name="placementByUser"
                           value="logged-in-users"
                           <?php checked( $placement_by_user === 'logged-in-users' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Only logged-in users', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>

                <div>
                  <label class="radio-wrapper">
                    <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-by-user-cookie-value' ) ?>"
                           type="radio"
                           name="placementByUser"
                           value="cookie-value"
                           data-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-placement-user-cookie-value-element' ) ?>"
                           <?php checked( $placement_by_user === 'cookie-value' ) ?>
                    />
                    <span class="thumb"></span>
                    <span><?php _e( 'Based on cookie value', BULLETINWP_PLUGIN_SLUG ) ?></span>
                  </label>
                </div>
              </div>

              <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
                <!-- Cookie value -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-user-cookie-value-element' ) ?>"
                     class="form-field form-field-text mt-4 is-required"
                     style="display: <?php echo esc_attr( $placement_by_user === 'cookie-value' ? 'block' : 'none' ); ?>;"
                >
                  <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-user-cookie-value' ) ?>"><?php _e( 'User cookie value', BULLETINWP_PLUGIN_SLUG ) ?></label>
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-placement-user-cookie-value' ) ?>"
                         class="form-input w-full"
                         type="text"
                         name="placementUserCookieValue"
                         value="<?php echo esc_attr( $placement_user_cookie_value ) ?>"
                  />

                  <div class="text-sm">
                    <i><?php _e( 'Enter the key of the cookie. If this is set on the browser, the bulletin will show.', BULLETINWP_PLUGIN_SLUG ) ?></i>
                  </div>
                </div>
              <?php endif; ?>
            </div>

          </div>

          <!-- TAB - Expiry -->
          <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-expiry-tab' ) ?>" class="tab-pane">
            <div class="flex items-center mb-8 md:mb-12">
              <div class="heading-icon mr-4">
                <img src="<?php echo esc_url( $images_dir . '/tab-icon/message.svg' ) ?>" alt="">
              </div>

              <div class="tab-heading">Expiry</div>
            </div>

            <div class="heading flex items-center">
              <?php _e( 'Expire bulletin', BULLETINWP_PLUGIN_SLUG ) ?>

              <?php if ( bulletinwp_fs()->is__premium_only() ) :
              else :?>
                <div class="pro-pill">PRO</div>
              <?php endif; ?>
            </div>

            <hr class="my-4">

            <div class="checkbox-wrapper toggle-switch add-countdown-data-label <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>"
                 data-checked-label="<?php echo esc_attr( __( 'Yes', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                 data-unchecked-label="<?php echo esc_attr( __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                 data-hide-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-countdown-element' ) ?>"
            >
              <input type="checkbox" name="addCountdown" <?php checked( $add_countdown ) ?> />
              <span class="label add-countdown-label"><?php echo esc_html( $add_countdown ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
            </div>

            <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
              <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-element' ) ?>"
                    style="display: <?php echo esc_attr( $add_countdown ? 'block' : 'none' ) ?>"
              >
                <div class="form-field form-field-text is-required mt-4">
                  <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown' ) ?>"><?php _e( 'Expiry date/time', BULLETINWP_PLUGIN_SLUG ) ?></label>
                  <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-note-schedule' ) ?>">
                    <i><?php _e( 'Note: Bulletin will not show after this time and will be set to inactive' ) ?></i>
                  </div>
                  <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown' ) ?>"
                         class="form-input w-full date-time-picker-input"
                         type="text"
                         name="countdown"
                         value="<?php echo esc_attr( $countdown ) ?>"
                         placeholder=""
                         autocomplete="off"
                  />
                </div>

                <div class="heading mt-8"><?php _e( 'Show countdown', BULLETINWP_PLUGIN_SLUG ) ?></div>

                <hr class="my-4">

                <div class="checkbox-wrapper toggle-switch"
                     data-checked-label="<?php _e( 'Yes', BULLETINWP_PLUGIN_SLUG ) ?>"
                     data-unchecked-label="<?php _e( 'No', BULLETINWP_PLUGIN_SLUG ) ?>"
                     data-hide-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-countdown-labels, #' . BULLETINWP_PLUGIN_SLUG . '-countdown-style' ) ?>"
                >
                  <input type="checkbox" name="showCountdown" <?php checked( $show_countdown ) ?> />
                  <span class="label"><?php echo esc_html( $show_countdown ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
                </div>

                <!-- Countdown BG / Font Color -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-style' ) ?>"
                  style="display: <?php echo esc_attr( $show_countdown ? 'block' : 'none' ) ?>"
                >
                  <div class="flex flex-col md:flex-row my-4">
                    <div class="form-field form-field-color-picker flex flex-col mr-0 md:mr-4 mb-4 md:mb-0 is-required">
                      <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-background-color' ) ?>"><?php _e( 'Background color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-background-color' ) ?>"
                             class="form-input color-picker-input"
                             type="text"
                             name="countdownBackgroundColor"
                             value="<?php echo esc_attr( $countdown_background_color ) ?>"
                             placeholder=""
                             data-default-color=""
                      />
                    </div>

                    <div class="form-field form-field-color-picker flex flex-col mr-0 md:mr-4 mb-4 md:mb-0 is-required">
                      <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-font-color' ) ?>"><?php _e( 'Font color', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-font-color' ) ?>"
                             class="form-input color-picker-input"
                             type="text"
                             name="countdownFontColor"
                             value="<?php echo esc_attr( $countdown_font_color ) ?>"
                             placeholder=""
                             data-default-color=""
                      />
                    </div>
                  </div>
                </div>

                <!-- Countdown Override Labels -->
                <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-labels' ) ?>"
                     class="mt-8"
                     style="display: <?php echo esc_attr( $show_countdown ? 'block' : 'none' ) ?>"
                >

                  <div class="heading"><?php _e( 'Override labels', BULLETINWP_PLUGIN_SLUG ) ?></div>

                  <hr class="my-4">

                  <div class="flex flex-col md:flex-row">
                    <div class="form-field flex flex-col mr-0 md:mr-4 mb-4 md:mb-0">
                      <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-days-label' ) ?>"><?php _e( 'Days label', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-days-label' ) ?>"
                             class="form-input"
                             type="text"
                             name="countdownDaysLabel"
                             value="<?php echo esc_attr( $countdown_days_label ) ?>"
                             placeholder="<?php echo esc_attr( __( 'days', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                      />
                    </div>

                    <div class="form-field flex flex-col mr-0 md:mr-4 mb-4 md:mb-0">
                      <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-hours-label' ) ?>"><?php _e( 'Hours label', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-hours-label' ) ?>"
                             class="form-input"
                             type="text"
                             name="countdownHoursLabel"
                             value="<?php echo esc_attr( $countdown_hours_label ) ?>"
                             placeholder="<?php echo esc_attr( __( 'hours', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                      />
                    </div>

                    <div class="form-field flex flex-col mr-0 md:mr-4 mb-4 md:mb-0">
                      <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-mins-label' ) ?>"><?php _e( 'Minutes label', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-mins-label' ) ?>"
                             class="form-input"
                             type="text"
                             name="countdownMinsLabel"
                             value="<?php echo esc_attr( $countdown_mins_label ) ?>"
                             placeholder="<?php echo esc_attr( __( 'minutes', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                      />
                    </div>

                    <div class="form-field flex flex-col mr-0 md:mr-4 mb-4 md:mb-0">
                      <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-secs-label' ) ?>"><?php _e( 'Seconds label', BULLETINWP_PLUGIN_SLUG ) ?></label>
                      <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-countdown-secs-label' ) ?>"
                             class="form-input"
                             type="text"
                             name="countdownSecsLabel"
                             value="<?php echo esc_attr( $countdown_secs_label ) ?>"
                             placeholder="<?php echo esc_attr( __( 'seconds', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                      />
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <!-- TAB - Advanced -->
          <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-advanced-tab' ) ?>" class="tab-pane">
            <div class="flex items-center mb-8 md:mb-12">
              <div class="heading-icon mr-4">
                <img src="<?php echo esc_url( $images_dir . '/tab-icon/message.svg' ) ?>" alt="">
              </div>

              <div class="tab-heading">Advanced</div>
            </div>

            <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-additional-css-element' ) ?>">
              <div class="heading flex items-center">
                <?php _e( 'Additional CSS to render with this bulletin', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </div>

              <hr class="my-4">

              <div class="<?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>">
                <textarea id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-additional-css' ) ?>"
                          class="form-input textarea-input w-full"
                          name="additionalCss"
                          placeholder=""
                ><?php echo esc_textarea( $additional_css ) ?></textarea>

                <div class="text-sm">
                  <?php echo esc_html( 'i.e. #' . BULLETINWP_PLUGIN_SLUG . '-bulletin-item-' . ( ! empty( $id ) ? $id : '1' ) . ' { ... }' ) ?>
                  <br />
                  <?php echo esc_html( is_multisite() && is_main_site() ? 'for subsites: #' . BULLETINWP_PLUGIN_SLUG . '-bulletin-item-global-' . ( ! empty( $id ) ? $id : '1' ) . ' { ... }' : '' ) ?>
                </div>
              </div>
            </div>
          </div>

          <!-- TAB - WP Network -->
          <?php if ( is_multisite() && is_main_site() ) : ?>
            <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-network-tab' ) ?>" class="tab-pane">
              <div class="flex items-center mb-8 md:mb-12">
                <div class="heading-icon mr-4">
                  <img src="<?php echo esc_url( $images_dir . '/tab-icon/message.svg' ) ?>" alt="">
                </div>

                <div class="tab-heading">WP Network</div>
              </div>

              <label class="heading flex items-center" for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-apply-all-subsites' ) ?>">
                <?php _e( 'Apply to all subsites', BULLETINWP_PLUGIN_SLUG ) ?>

                <?php if ( bulletinwp_fs()->is__premium_only() ) :
                else :?>
                  <div class="pro-pill">PRO</div>
                <?php endif; ?>
              </label>

              <hr class="my-4">

              <div class="checkbox-wrapper toggle-switch <?php echo esc_attr( bulletinwp_fs()->is__premium_only() ? '' : 'pro-disabled' ) ?>"
                   data-checked-label="<?php echo esc_attr( __( 'Yes', BULLETINWP_PLUGIN_SLUG ) ) ?>"
                   data-unchecked-label="<?php echo esc_attr( __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?>"
              >
                <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-apply-all-subsites' ) ?>" type="checkbox" name="applyAllSubsites" <?php checked( $apply_all_subsites ) ?> />
                <span class="label"><?php echo esc_html( $apply_all_subsites ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php if ( bulletinwp_fs()->is__premium_only() ) :
    else : ?>
      <?php include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/common/upgrade-panel.php' ); ?>
    <?php endif; ?>
  </div>

  <div class="right-content">
    <div class="box-container py-8 px-4">
      <!-- Preview -->
      <a href="<?php echo esc_url( $link ) ?>"
         class="btn view-button mb-8"
         target="_blank"
         style="display: <?php echo esc_attr( ! empty( $link ) ? 'inline-flex' : 'none' ) ?>; width: 80%;"
      >
        <?php echo esc_html( $is_activated ? __( 'View', BULLETINWP_PLUGIN_SLUG ) : __( 'Preview', BULLETINWP_PLUGIN_SLUG ) ) ?>
        <img src="<?php echo esc_url( $images_dir . '/angle.svg' ) ?>" alt="">
      </a>

      <!-- Active Switch -->
      <div class="mb-8">
        <div class="checkbox-wrapper toggle-switch active-data-label"
             data-checked-label="<?php echo esc_attr( __( 'Active', BULLETINWP_PLUGIN_SLUG ) ) ?>"
             data-unchecked-label="<?php echo esc_attr( __( 'Inactive', BULLETINWP_PLUGIN_SLUG ) ) ?>"
        >
          <input type="checkbox" name="isActivated" <?php checked( $is_activated ) ?>  <?php echo esc_html( $add_schedule ? 'disabled' : '' ) ?> />
          <span class="label active-switch-label"><?php echo esc_html( $is_activated ? __( 'Active', BULLETINWP_PLUGIN_SLUG ) : __( 'Inactive', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
        </div>
      </div>

      <?php if ( bulletinwp_fs()->is__premium_only() ) : ?>
        <!-- Bulletin Scheduling -->
        <div class="mb-8">
          <div class="heading mb-4"><?php _e( 'Schedule', BULLETINWP_PLUGIN_SLUG ) ?></div>

          <div class="checkbox-wrapper toggle-switch"
               data-checked-label="<?php esc_attr( __( 'Yes', BULLETINWP_PLUGIN_SLUG ) ) ?>"
               data-unchecked-label="<?php esc_attr( __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?>"
               data-hide-show-elements="<?php echo esc_attr( '#' . BULLETINWP_PLUGIN_SLUG . '-schedule-elements, #' . BULLETINWP_PLUGIN_SLUG . '-note-schedule' ) ?>"
          >
            <input type="checkbox" name="addSchedule" <?php checked( $add_schedule ) ?> />
            <span class="label"><?php echo esc_html( $add_schedule ? __( 'Yes', BULLETINWP_PLUGIN_SLUG ) : __( 'No', BULLETINWP_PLUGIN_SLUG ) ) ?></span>
          </div>

          <div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-schedule-elements' ) ?>" style="display: <?php echo esc_attr( $add_schedule ? 'block' : 'none' ) ?>;" class="mt-4">
            <div class="form-field form-field-date mb-4 is-required">
              <label for="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-start-schedule' ) ?>"><?php _e( 'Publish from', BULLETINWP_PLUGIN_SLUG ) ?></label>
              <input id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-start-schedule' ) ?>"
                     class="form-input w-full date-time-picker-input date-time-picker-range-input"
                     type="text"
                     name="startSchedule"
                     value="<?php echo esc_attr( $start_schedule ) ?>"
                     placeholder="Select date/time"
                     autocomplete="off"
                     data-end-date-element="countdown"
              />
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Submit -->
      <button class="btn-fill text-lg"
              type="submit"
              data-button-status="<?php echo esc_attr( $button_status ) ?>"
              data-default-label="<?php echo esc_attr( $default_label ) ?>"
              data-loading-label="<?php echo esc_attr( $loading_label ) ?>"
      >
        <?php echo esc_html( $default_label ) ?>
      </button>
      <div class="form-message mt-8" style="display: none;"></div>
    </div>

    <!-- Right panel content -->
    <?php include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/common/right-panel.php' ); ?>

  </div>
</div>
