<?php
if ( BULLETINWP::instance()->language->maybe_polylang_plugin_is_activated() && function_exists( 'pll__' ) ) {
  if ( isset( $bulletin['countdown_days_label'] ) && ! empty( $bulletin['countdown_days_label'] ) ) {
    $bulletin['countdown_days_label'] = pll__( $bulletin['countdown_days_label'] );
  }

  if ( isset( $bulletin['countdown_hours_label'] ) && ! empty( $bulletin['countdown_hours_label'] ) ) {
    $bulletin['countdown_hours_label'] = pll__( $bulletin['countdown_hours_label'] );
  }

  if ( isset( $bulletin['countdown_mins_label'] ) && ! empty( $bulletin['countdown_mins_label'] ) ) {
    $bulletin['countdown_mins_label'] = pll__( $bulletin['countdown_mins_label'] );
  }

  if ( isset( $bulletin['countdown_secs_label'] ) && ! empty( $bulletin['countdown_secs_label'] ) ) {
    $bulletin['countdown_secs_label'] = pll__( $bulletin['countdown_secs_label'] );
  }
} elseif ( BULLETINWP::instance()->language->maybe_wpml_plugin_is_activated() ) {
  if ( isset( $bulletin['countdown_days_label'] ) && ! empty( $bulletin['countdown_days_label'] ) ) {
    $bulletin['countdown_days_label'] = apply_filters( 'wpml_translate_single_string', $bulletin['countdown_days_label'], $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Content" );
  }

  if ( isset( $bulletin['countdown_hours_label'] ) && ! empty( $bulletin['countdown_hours_label'] ) ) {
    $bulletin['countdown_hours_label'] = apply_filters( 'wpml_translate_single_string', $bulletin['countdown_hours_label'], $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Mobile Content" );
  }

  if ( isset( $bulletin['countdown_mins_label'] ) && ! empty( $bulletin['countdown_mins_label'] ) ) {
    $bulletin['countdown_mins_label'] = apply_filters( 'wpml_translate_single_string', $bulletin['countdown_mins_label'], $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Mobile Content" );
  }

  if ( isset( $bulletin['countdown_secs_label'] ) && ! empty( $bulletin['countdown_secs_label'] ) ) {
    $bulletin['countdown_secs_label'] = apply_filters( 'wpml_translate_single_string', $bulletin['countdown_secs_label'], $plugin_slug, "{$bulletin_title} ({$bulletin_id}) - Mobile Content" );
  }
}

$countdown                  = ( isset( $bulletin['countdown'] ) && ! empty( $bulletin['countdown'] ) ) ? $bulletin['countdown'] : '';
$countdown_background_color = ( isset( $bulletin['countdown_background_color'] ) && ! empty( $bulletin['countdown_background_color'] ) ) ? $bulletin['countdown_background_color'] : '';
$countdown_font_color       = ( isset( $bulletin['countdown_font_color'] ) && ! empty( $bulletin['countdown_font_color'] ) ) ? $bulletin['countdown_font_color'] : '';
$countdown_style            = '';
$countdown_days_label       = ( isset( $bulletin['countdown_days_label'] ) && ! empty( $bulletin['countdown_days_label'] ) ) ? $bulletin['countdown_days_label'] : __( 'days', $plugin_slug );
$countdown_hours_label      = ( isset( $bulletin['countdown_hours_label'] ) && ! empty( $bulletin['countdown_hours_label'] ) ) ? $bulletin['countdown_hours_label'] : __( 'hours', $plugin_slug );
$countdown_mins_label       = ( isset( $bulletin['countdown_mins_label'] ) && ! empty( $bulletin['countdown_mins_label'] ) ) ? $bulletin['countdown_mins_label'] : __( 'mins', $plugin_slug );
$countdown_secs_label       = ( isset( $bulletin['countdown_secs_label'] ) && ! empty( $bulletin['countdown_secs_label'] ) ) ? $bulletin['countdown_secs_label'] : __( 'secs', $plugin_slug );
$countdown_font_size        = ( isset( $bulletin['font_size'] ) && ! empty( $bulletin['font_size'] ) ) ? $bulletin['font_size'] - 5 : '';
$countdown_font_size_mobile = ( isset( $bulletin['font_size_mobile'] ) && ! empty( $bulletin['font_size_mobile'] ) ) ? $bulletin['font_size_mobile'] - 5 : '';

if ( ! empty( $countdown_background_color ) && $show_countdown ) {
  $countdown_style .= 'background-color: ' . $countdown_background_color . '; ';
}

if ( ! empty( $countdown_font_color && $show_countdown ) ) {
  $countdown_style .= 'color: ' . $countdown_font_color . '; ';
}

if ( $placement === 'corner' ) {
  $countdown_style .= 'text-align: left; ';
}

if ( ! empty( $countdown_font_size ) ) {
  $internal_style .= "
  #{$plugin_slug}-bulletin-item-{$bulletin['id']} .{$plugin_slug}-countdown-label {
    font-size: {$countdown_font_size}px !important;
  }
  ";
} else {
  $internal_style .= "
  #{$plugin_slug}-bulletin-item-{$bulletin['id']} .{$plugin_slug}-countdown-label {
    font-size: 11px !important;
  }
  ";
}

if ( ! empty( $countdown_font_size_mobile ) ) {
  $internal_style .= "
  @media (max-width: 767px) {
    #{$plugin_slug}-bulletin-item-{$bulletin['id']} .{$plugin_slug}-countdown-label {
      font-size: {$countdown_font_size_mobile}px !important;
    }
  }
  ";
} else {
  $internal_style .= "
  @media (max-width: 767px) {
    #{$plugin_slug}-bulletin-item-{$bulletin['id']} .{$plugin_slug}-countdown-label {
      font-size: 11px !important;
    }
  }
  ";
}
?>

<?php if ( ! empty( $countdown ) && $show_countdown ) : ?>
  <div class="<?php echo esc_attr( "{$plugin_slug}-countdown-timer" ) ?>"
       style="<?php echo esc_attr( $countdown_style ) ?>"
       data-countdown-expiry="<?php echo esc_attr( $bulletin['countdown'] ) ?>"
       data-show-countdown="<?php echo esc_attr( $show_countdown ) ?>"
  >
  <div class="<?php echo esc_attr( "{$plugin_slug}-time-wrapper" ) ?>">
    <div class="<?php echo esc_attr( "{$plugin_slug}-days-countdown" ) ?>"></div>
    <div class="<?php echo esc_attr( "{$plugin_slug}-countdown-label" ) ?>">
      <?php echo esc_html( $countdown_days_label ); ?>
    </div>
  </div>
  <div class="<?php echo esc_attr( "{$plugin_slug}-time-wrapper" ) ?>">
    <div class="<?php echo esc_attr( "{$plugin_slug}-hours-countdown" ) ?>"></div>
    <div class="<?php echo esc_attr( "{$plugin_slug}-countdown-label" ) ?>">
      <?php echo esc_html( $countdown_hours_label ); ?>
    </div>
  </div>
  <div class="<?php echo esc_attr( "{$plugin_slug}-time-wrapper" ) ?>">
    <div class="<?php echo esc_attr( "{$plugin_slug}-mins-countdown" ) ?>"></div>
    <div class="<?php echo esc_attr( "{$plugin_slug}-countdown-label" ) ?>">
      <?php echo esc_html( $countdown_mins_label ); ?>
    </div>
  </div>
  <div class="<?php echo esc_attr( "{$plugin_slug}-time-wrapper" ) ?>">
    <div class="<?php echo esc_attr( "{$plugin_slug}-secs-countdown" ) ?>"></div>
    <div class="<?php echo esc_attr( "{$plugin_slug}-countdown-label" ) ?>">
      <?php echo esc_html( $countdown_secs_label ); ?>
    </div>
  </div>
  </div>
<?php endif; ?>
