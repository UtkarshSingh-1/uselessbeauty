<div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-close-button" ) ?>"
     data-cookie-expiry="<?php echo esc_attr( $bulletin['cookie_expiry'] ) ?>"
>
  <div class="<?php echo esc_attr( "{$plugin_slug}-close-button" ) ?>"></div>
</div>

<?php if ( ! empty( $font_color ) ) : ?>
  <style>
    <?php echo esc_html( "#{$plugin_slug}-bulletin-item-{$bulletin['id']} .{$plugin_slug}-bulletin-close-button .{$plugin_slug}-close-button::before" ) ?>,
    <?php echo esc_html( "#{$plugin_slug}-bulletin-item-{$bulletin['id']} .{$plugin_slug}-bulletin-close-button .{$plugin_slug}-close-button::after" ) ?> {
      background-color: <?php echo esc_html( $font_color ) ?>;
    }
  </style>
<?php endif; ?>
