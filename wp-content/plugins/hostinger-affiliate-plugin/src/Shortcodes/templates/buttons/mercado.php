<?php

if ( empty( $product_type ) ) {
    $product_type = '';
}

?>
<a href="<?php echo $this->shortcode_manager->render_product_url( $product, $product_type ); ?>" class="<?php echo ! empty( $button_class ) ? $button_class : ''; ?>" target="_blank" rel="sponsored noopener noreferrer">
    <?php echo $this->shortcode_manager->render_buy_now_button_label( $product ); ?>
</a>
