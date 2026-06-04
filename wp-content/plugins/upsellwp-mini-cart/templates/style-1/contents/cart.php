<?php
/**
 * Cart
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/contents/cart.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced) || empty($cart)) {
    return;
}

?>
<div class="uwpmc-cart-block">
    <?php if (!$cart->is_empty()) { ?>
        <div class="uwpmc-cart-contents uwpmc-border" style="<?php echo esc_attr($style['card']); ?>">
            <?php
            uwpmc_get_template('components/banner', ['cart' => $cart]);
            uwpmc_get_template('components/goals', ['cart' => $cart]);
            uwpmc_get_template('components/items', ['cart' => $cart]);
            uwpmc_get_template('components/recommendations', ['cart' => $cart]);
            ?>
        </div>
        <div class="uwpmc-cart-totals uwpmc-border" style="<?php echo esc_attr($style['card']); ?>">
            <?php
            uwpmc_get_template('components/coupon', ['cart' => $cart]);
            uwpmc_get_template('components/totals', ['cart' => $cart]);
            ?>
        </div>
    <?php } else {
        uwpmc_get_template('components/no-items', ['cart' => $cart]);
    } ?>
</div>
<?php uwpmc_get_template('contents/offers', ['cart' => $cart]); ?>

