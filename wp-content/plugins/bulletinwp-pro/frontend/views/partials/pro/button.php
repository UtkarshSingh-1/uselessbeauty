<?php
isset( $is_mobile ) or $is_mobile = false;

$button_label                  = ( isset( $bulletin['button_label'] ) && ! empty( $bulletin['button_label'] ) ) ? $bulletin['button_label'] : '';
$button_mobile_label           = ( isset( $bulletin['button_mobile_label'] ) && ! empty( $bulletin['button_mobile_label'] ) ) ? $bulletin['button_mobile_label'] : $button_label;
$button_background_color       = ( isset( $bulletin['button_background_color'] ) && ! empty( $bulletin['button_background_color'] ) ) ? $bulletin['button_background_color'] : '';
$button_font_color             = ( isset( $bulletin['button_font_color'] ) && ! empty( $bulletin['button_font_color'] ) ) ? $bulletin['button_font_color'] : '';
$button_hover_background_color = ( isset( $bulletin['button_hover_background_color'] ) && ! empty( $bulletin['button_hover_background_color'] ) ) ? $bulletin['button_hover_background_color'] : $button_font_color;
$button_hover_font_color       = ( isset( $bulletin['button_hover_font_color'] ) && ! empty( $bulletin['button_hover_font_color'] ) ) ? $bulletin['button_hover_font_color'] : $button_background_color;
$button_action                 = ( isset( $bulletin['button_action'] ) && ! empty( $bulletin['button_action'] ) ) ? $bulletin['button_action'] : '';
$button_style                  = '';
$button_attribs                = '';
$button_class                  = '';

if ( BULLETINWP::instance()->language->maybe_polylang_plugin_is_activated() && function_exists( 'pll__' ) ) {
  $button_label        = pll__( $button_label );
  $button_mobile_label = pll__( $button_mobile_label );
} elseif ( BULLETINWP::instance()->language->maybe_wpml_plugin_is_activated() ) {
  $button_label        = apply_filters( 'wpml_translate_single_string', $button_label, $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Button Label" );
  $button_mobile_label = apply_filters( 'wpml_translate_single_string', $button_mobile_label, $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Button Mobile Label" );
}

if ( ! empty( $button_background_color ) ) {
  $button_style .= 'background-color: ' . $button_background_color . '; ';
}

if ( ! empty( $button_font_color ) ) {
  $button_style .= 'color: ' . $button_font_color . '; ';
}

if ( $button_action === 'link' ) {
  $button_link   = ( isset( $bulletin['button_link'] ) && ! empty( $bulletin['button_link'] ) ) ? $bulletin['button_link'] : '';
  $button_target = ( isset( $bulletin['button_target'] ) && ! empty( $bulletin['button_target'] ) ) ? $bulletin['button_target'] : '';
  $target        = '_self';

  if ( BULLETINWP::instance()->language->maybe_polylang_plugin_is_activated() && function_exists( 'pll__' ) ) {
    $button_link = pll__( $button_link );
  } elseif ( BULLETINWP::instance()->language->maybe_wpml_plugin_is_activated() ) {
    $button_link = apply_filters( 'wpml_translate_single_string', $button_link, $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Button Link" );
  }

  if ( ! empty( $button_target ) ) {
    switch ( $button_target ) {
      case 'same-tab':
        $target = '_self';
        break;
      case 'new-tab':
        $target = '_blank';
        break;
    }
  }

  $button_attribs = 'href="' . $button_link . '" target="' . $target . '"';
} elseif ( $button_action === 'custom-js-event' ) {
  $button_js_event = ( isset( $bulletin['button_js_event'] ) && ! empty( $bulletin['button_js_event'] ) ) ? $bulletin['button_js_event'] : '';

  $button_attribs = 'href="#" onclick="' . stripslashes( $button_js_event ) . ' return false;"';
} elseif ( $button_action === 'dismiss-bulletin' ) {
  $button_class = "{$plugin_slug}-bulletin-close-button {$plugin_slug}-bulletin-dismiss-button";

  $button_attribs = 'href= "#" data-button-cookie-expiry="' . $bulletin['button_cookie_expiry'] . '"';
} elseif ( $button_action === 'trigger-easy-popup' ) {
  $button_easy_popup = ( isset( $bulletin['button_easy_popup'] ) && ! empty( $bulletin['button_easy_popup'] ) ) ? $bulletin['button_easy_popup'] : '';

  $button_attribs = 'href= "#" onclick="document.dispatchEvent(new CustomEvent(\'' . $plugin_slug . '-easy-popup-' . $button_easy_popup . '\')); return false;"';
}
?>
<a class="<?php echo esc_attr( "{$plugin_slug}-button {$button_class}" ) ?> <?php echo esc_attr( ( $is_mobile || $placement === 'corner' ) ? "{$plugin_slug}-button-mobile" : '' ) ?>" <?php echo wp_kses( $button_attribs, [] ) ?>>
  <?php if ( ! empty( $button_label ) ) : ?>
    <span class="<?php echo esc_attr( "{$plugin_slug}-button-label" ) ?>">
      <?php echo esc_html( $button_label ) ?>
    </span>
  <?php endif; ?>

  <?php if ( ! empty( $button_mobile_label ) ) : ?>
    <span class="<?php echo esc_attr( "{$plugin_slug}-button-mobile-label" ) ?>">
      <?php echo esc_html( $button_mobile_label ) ?>
    </span>
  <?php endif; ?>
</a>
<style>
  <?php if ( ! empty( $button_style ) ) : ?>
    <?php echo esc_html( "#{$plugin_slug}-bulletin-item-{$bulletin['id']} .{$plugin_slug}-button" ) ?> {
      <?php echo esc_html( $button_style ); ?>
    }
  <?php endif; ?>

  <?php echo esc_html( "#{$plugin_slug}-bulletin-item-{$bulletin['id']} .{$plugin_slug}-button:hover" ) ?> {
    background-color: <?php echo esc_html( $button_hover_background_color ); ?>;
    color: <?php echo esc_html( $button_hover_font_color ); ?>;
    border-color: unset;
  }
</style>
