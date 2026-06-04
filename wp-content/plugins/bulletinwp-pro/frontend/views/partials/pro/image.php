<?php

$add_image = ( isset( $bulletin['add_image'] ) && $bulletin['add_image'] ) ? $bulletin['add_image'] : '';

$image_position_adjustment = ( $placement == 'top' ) ? 'top:-32px;' : 'bottom:-32px;';

if ( $add_image ) :
    $image_src = wp_get_attachment_image_src( $bulletin['image_attachment_id'], 'full' )[0];
    $image_max_width = ( isset( $bulletin['image_max_width'] ) && $bulletin['image_max_width'] ) ? $bulletin['image_max_width'] : '225';

  if ( ! empty( $image_src ) ) : ?>
    <div class="<?php echo esc_attr( "{$plugin_slug}-bulletin-image-wrapper" ) ?>"
         style="width:<?php echo esc_attr( $image_max_width + 20 )  ?>px;"
    >
        <img class="<?php echo esc_attr( "{$plugin_slug}-bulletin-image" ) ?>"
             style="max-width:<?php echo esc_attr( $image_max_width ) ?>px;position:absolute;<?php echo esc_attr( $image_position_adjustment ) ?>"
             src="<?php echo esc_url( $image_src ) ?>" />
    </div>
  <?php endif;
endif; // $add_image
