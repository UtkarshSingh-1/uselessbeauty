<?php

if ( isset( $_GET['bulletin'] ) && ! empty( $_GET['bulletin'] ) ) {
  $bulletin_id = sanitize_text_field( $_GET['bulletin'] );
  $bulletin    = BULLETINWP::instance()->sql->get_bulletin( $bulletin_id );

  if ( ! empty( $bulletin ) ) {
    // Set bulletin form data
    $id   = $bulletin_id;
    $link = BULLETINWP::instance()->helpers->get_bulletin_link( $bulletin_id );
    if ( isset( $bulletin['is_activated'] ) ) {
      $is_activated = $bulletin['is_activated'];
    }
    if ( isset( $bulletin['bulletin_title'] ) ) {
      $title = $bulletin['bulletin_title'];
    }
    if ( isset( $bulletin['content'] ) ) {
      $content = $bulletin['content'];
    }
    if ( isset( $bulletin['mobile_content'] ) ) {
      $mobile_content = $bulletin['mobile_content'];
    }
    if ( isset( $bulletin['background_color'] ) ) {
      $background_color = $bulletin['background_color'];
    }
    if ( isset( $bulletin['font_color'] ) ) {
      $font_color = $bulletin['font_color'];
    }
    if ( isset( $bulletin['placement'] ) ) {
      $placement = $bulletin['placement'];
    }
    if ( isset( $bulletin['header_banner_style'] ) ) {
      $header_banner_style = $bulletin['header_banner_style'];
    }
    if ( isset( $bulletin['header_banner_scroll'] ) ) {
      $header_banner_scroll = $bulletin['header_banner_scroll'];
    }
    if ( isset( $bulletin['content_max_width'] ) ) {
      $content_max_width = $bulletin['content_max_width'];
    }
    if ( isset( $bulletin['text_alignment'] ) ) {
      $text_alignment = $bulletin['text_alignment'];
    }
    if ( isset( $bulletin['font_size'] ) ) {
      $font_size = $bulletin['font_size'];
    }
    if ( isset( $bulletin['font_size_mobile'] ) ) {
      $font_size_mobile = $bulletin['font_size_mobile'];
    }
    if ( isset( $bulletin['text_vertical_padding'] ) ) {
      $text_vertical_padding = $bulletin['text_vertical_padding'];
    }

    if ( bulletinwp_fs()->is__premium_only() ) {
      if ( isset( $bulletin['placement_corner_options'] ) ) {
        $placement_corner_options = $bulletin['placement_corner_options'];
      }
      if ( isset( $bulletin['add_icon'] ) ) {
        $add_icon = $bulletin['add_icon'];
      }
      if ( isset( $bulletin['icon_name_from_set'] ) ) {
        $icon_name_from_set = $bulletin['icon_name_from_set'];
      }
      if ( isset( $bulletin['icon_attachment_id'] ) ) {
        $icon_attachment_id = $bulletin['icon_attachment_id'];
      }
      if ( isset( $bulletin['add_image'] ) ) {
        $add_image = $bulletin['add_image'];
      }
      if ( isset( $bulletin['image_attachment_id'] ) ) {
        $image_attachment_id = $bulletin['image_attachment_id'];
      }
      if ( isset( $bulletin['image_alignment'] ) ) {
        $image_alignment = $bulletin['image_alignment'];
      }
      if ( isset( $bulletin['image_max_width'] ) ) {
        $image_max_width = $bulletin['image_max_width'];
      }
      if ( isset( $bulletin['fonts'] ) ) {
        $fonts = $bulletin['fonts'];
      }
      if ( isset( $bulletin['google_fonts'] ) ) {
        $google_fonts = $bulletin['google_fonts'];
      }
      if ( isset( $bulletin['is_multiple_messages'] ) ) {
        $is_multiple_messages = $bulletin['is_multiple_messages'];
      }
      if ( isset( $bulletin['messages'] ) && is_serialized( $bulletin['messages'] ) ) {
        $messages = unserialize( $bulletin['messages'] );
      }
      if ( isset( $bulletin['rotation_style'] ) ) {
        $rotation_style = $bulletin['rotation_style'];
      }
      if ( isset( $bulletin['cycle_speed'] ) ) {
        $cycle_speed = $bulletin['cycle_speed'];
      }
      if ( isset( $bulletin['marquee_speed'] ) ) {
        $marquee_speed = $bulletin['marquee_speed'];
      }
      if ( isset( $bulletin['add_button'] ) ) {
        $add_button = $bulletin['add_button'];
      }
      if ( isset( $bulletin['button_label'] ) ) {
        $button_label = $bulletin['button_label'];
      }
      if ( isset( $bulletin['button_mobile_label'] ) ) {
        $button_mobile_label = $bulletin['button_mobile_label'];
      }
      if ( isset( $bulletin['button_background_color'] ) ) {
        $button_background_color = $bulletin['button_background_color'];
      }
      if ( isset( $bulletin['button_font_color'] ) ) {
        $button_font_color = $bulletin['button_font_color'];
      }
      if ( isset( $bulletin['button_hover_background_color'] ) ) {
        $button_hover_background_color = $bulletin['button_hover_background_color'];
      }
      if ( isset( $bulletin['button_hover_font_color'] ) ) {
        $button_hover_font_color = $bulletin['button_hover_font_color'];
      }
      if ( isset( $bulletin['button_action'] ) ) {
        $button_action = $bulletin['button_action'];
      }
      if ( isset( $bulletin['button_cookie_expiry'] ) ) {
        $button_cookie_expiry = $bulletin['button_cookie_expiry'];
      }
      if ( isset( $bulletin['button_link'] ) ) {
        $button_link = $bulletin['button_link'];
      }
      if ( isset( $bulletin['button_target'] ) ) {
        $button_target = $bulletin['button_target'];
      }
      if ( isset( $bulletin['button_align'] ) ) {
        $button_align = $bulletin['button_align'];
      }
      if ( isset( $bulletin['button_js_event'] ) ) {
        $button_js_event = $bulletin['button_js_event'];
      }
      if ( isset( $bulletin['button_easy_popup'] ) ) {
        $button_easy_popup = $bulletin['button_easy_popup'];
      }
      if ( isset( $bulletin['add_countdown'] ) ) {
        $add_countdown = $bulletin['add_countdown'];
      }
      if ( isset( $bulletin['countdown'] ) ) {
        $countdown = $bulletin['countdown'];
      }
      if ( isset( $bulletin['show_countdown'] ) ) {
        $show_countdown = $bulletin['show_countdown'];
      }
      if ( isset( $bulletin['countdown_background_color'] ) ) {
        $countdown_background_color = $bulletin['countdown_background_color'];
      }
      if ( isset( $bulletin['countdown_font_color'] ) ) {
        $countdown_font_color = $bulletin['countdown_font_color'];
      }
      if ( isset( $bulletin['countdown_days_label'] ) ) {
        $countdown_days_label = $bulletin['countdown_days_label'];
      }
      if ( isset( $bulletin['countdown_hours_label'] ) ) {
        $countdown_hours_label = $bulletin['countdown_hours_label'];
      }
      if ( isset( $bulletin['countdown_mins_label'] ) ) {
        $countdown_mins_label = $bulletin['countdown_mins_label'];
      }
      if ( isset( $bulletin['countdown_secs_label'] ) ) {
        $countdown_secs_label = $bulletin['countdown_secs_label'];
      }
      if ( isset( $bulletin['placement_by_content'] ) ) {
        $placement_by_content = $bulletin['placement_by_content'];
      }
      if ( isset( $bulletin['placement_selected_content_include'] ) ) {
        $placement_selected_content_include = unserialize( $bulletin['placement_selected_content_include'] );
      }
      if ( isset( $bulletin['placement_selected_content_exclude'] ) ) {
        $placement_selected_content_exclude = unserialize( $bulletin['placement_selected_content_exclude'] );
      }
      if ( isset( $bulletin['placement_by_user'] ) ) {
        $placement_by_user = $bulletin['placement_by_user'];
      }
      if ( isset( $bulletin['placement_user_cookie_value'] ) ) {
        $placement_user_cookie_value = $bulletin['placement_user_cookie_value'];
      }
      if ( isset( $bulletin['is_dismissable'] ) ) {
        $is_dismissable = $bulletin['is_dismissable'];
      }
      if ( isset( $bulletin['cookie_expiry'] ) ) {
        $cookie_expiry = $bulletin['cookie_expiry'];
      }

      if ( isset( $bulletin['additional_css'] ) ) {
        $additional_css = $bulletin['additional_css'];
      }

      if ( isset( $bulletin['add_schedule'] ) ) {
        $add_schedule = $bulletin['add_schedule'];
      }

      if ( isset( $bulletin['start_schedule'] ) ) {
        $start_schedule = $bulletin['start_schedule'];
      }

      if ( is_multisite() && is_main_site() && isset( $bulletin['apply_all_subsites'] ) ) {
        $apply_all_subsites = $bulletin['apply_all_subsites'];
      }
    }
  }
}
?>

<div id="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-admin' ) ?>">
  <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-admin-edit wrap' ) ?>">
    <h1 class="wp-heading-inline"><?php _e( 'Edit bulletin', BULLETINWP_PLUGIN_SLUG ) ?></h1>

    <hr class="wp-header-end">

    <div class="<?php echo esc_attr( BULLETINWP_PLUGIN_SLUG . '-admin-common-layout ' . BULLETINWP_PLUGIN_SLUG . '-admin-edit' ) ?>">
      <form class="bulletin-form" method="post">
        <div class="common-layout-wrapper edit">
          <?php include_once( BULLETINWP_PLUGIN_PATH . 'admin/views/common/bulletin-form.php' ); ?>
        </div>
      </form>
    </div>
  </div>
</div>
