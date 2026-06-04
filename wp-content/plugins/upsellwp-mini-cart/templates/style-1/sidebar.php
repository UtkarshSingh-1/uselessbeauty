<?php
/**
 * Sidebar
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/sidebar.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced) || empty($cart)) {
    return;
}

do_action('uwpmc_before_cart_body');
?>
    <div id="uwpmc-cart-sidebar" class="upw-mc-<?php echo esc_attr($data['active_theme']); ?>" style="width: auto;">
        <div class="uwpmc-sidebar <?php echo esc_attr('sidebar-' . $data['slider']['position']); ?>"
             style="<?php echo esc_attr($style['slider']); ?>">

            <?php uwpmc_get_template('components/header', ['cart' => $cart]); ?>

            <div class="uwpmc-body">
                <?php uwpmc_get_template('contents/cart', ['cart' => $cart]); ?>
            </div>
            <?php uwpmc_get_template('components/footer', ['cart' => $cart]); ?>
        </div>
    </div>

<?php do_action('uwpmc_after_cart_body'); ?>