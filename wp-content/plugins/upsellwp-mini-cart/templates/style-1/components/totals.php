<?php
/**
 * Totals
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/components/totals.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced) || empty($cart)) {
    return;
}

$uwpmc_total_discount = $cart->get_discount_total();
if ($cart->display_prices_including_tax()) {
    $uwpmc_total_discount += $cart->get_discount_tax();
}

$uwpmc_cart_text =	$data['actions']['cart']['text'] ?? '';
$uwpmc_checkout_text =	$data['actions']['checkout']['text'] ?? '';

?>

<div class="uwpmc-totals-section uwpmc-border"
     style="<?php echo esc_attr($style['totals']); ?><?php echo esc_attr($style['card']); ?>">
    <div class="uwpmc-total-lines"
         style="display: flex; flex-direction: column; justify-content: center; padding: 0 10px;">
        <div class="uwpmc-show-cart-subtotal" style="padding-bottom: 2px;
        <?php echo !empty($data['totals']['show']['subtotal']) ? 'display: block;' : 'display: none;' ?>">
            <span style="display: flex; gap: 6px; justify-content: space-between;">
                <?php esc_html_e('Subtotal', 'upsellwp-mini-cart'); ?>:
                <span style="display: flex; gap: 4px; flex-wrap: wrap; align-items: center;">
                    <?php echo wp_kses_post($cart->get_cart_subtotal()); ?>
                </span>
            </span>
        </div>
        <div class="uwpmc-show-cart-discount" style="padding-bottom: 2px;
        <?php echo !empty($data['totals']['show']['discount']) ? 'display: block;' : 'display: none;' ?> ">
            <?php if (!empty($cart->get_applied_coupons())) { ?>
                <span style="display: flex; gap: 6px; justify-content: space-between;">
                 <?php esc_html_e('Discount', 'upsellwp-mini-cart'); ?>:
                    <span style="display: flex; gap: 4px; flex-wrap: wrap; align-items: center;">
                        <span>-<?php echo wp_kses_post(wc_price($uwpmc_total_discount)); ?></span>
                    </span>
                </span>
            <?php } ?>
        </div>
        <div class="uwpmc-show-cart-total" style="border-top: 0.1px dashed #c4bebe; padding: 4px 0 0;
            <?php echo !empty($data['totals']['show']['total']) ? 'display: block;' : 'display: none;' ?>">
            <span style="display: flex; gap: 6px; justify-content: space-between;">
                <?php esc_html_e('Total', 'upsellwp-mini-cart'); ?>:
                <span style="display: flex; gap: 4px; flex-wrap: wrap; align-items: center;">
                    <?php echo wp_kses_post($cart->get_total()); ?>
                </span>
            </span>
        </div>
    </div>
    <div class="uwpmc-border uwpmc-actions-block" style="<?php echo esc_attr($style['card']); ?>">
        <a class="uwpmc-action uwpmc-cart-action"
           href="<?php echo !empty($data['actions']['cart']['url']) ? esc_url($data['actions']['cart']['url']) : ''; ?>"
           style="<?php echo esc_attr($style['action']); ?> <?php echo(!empty($data['actions']['cart']['enable']) ? 'display: block;' : 'display: none;'); ?>">
            <button class="uwpmc-cart-button"
                    style="border: none; width: 100%; height: 100%; background-color: inherit; color: inherit; display: flex; justify-content: center; align-items: center; gap: 4px; padding: 4px; border-radius: 4px;" <?php echo $cart->is_empty() ? 'disabled' : ''; ?>>
                <?php
                    //phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
                    echo wp_kses_post(__($uwpmc_cart_text, 'upsellwp-mini-cart'));
                ?>
            </button>
        </a>
        <a class="uwpmc-action uwpmc-checkout-action"
           href="<?php echo !empty($data['actions']['cart']['url']) ? esc_url($data['actions']['checkout']['url']) : ''; ?>"
           style="<?php echo esc_attr($style['action']); ?>">
            <button class="uwpmc-checkout-button"
                    style="border: none; width: 100%; height: 100%; background-color: inherit; color: inherit; display: flex; justify-content: center; align-items: center; gap: 4px; padding: 4px; border-radius: 4px; flex-wrap: wrap;" <?php echo $cart->is_empty() ? 'disabled' : ''; ?>>
                <span class="uwpmc-checkout-text"
                      style="font-weight: 600 !important;">
                    <?php
                        //phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
                        echo wp_kses_post(__($uwpmc_checkout_text, 'upsellwp-mini-cart'));
                    ?>
                </span>
                <span class="uwpmc-checkout-total"
                      style="<?php echo (!empty($data['actions']['checkout']['show_total'])) ? '' : 'display: none;' ?> font-weight: 600 !important;"> - <?php echo wp_kses_post($cart->get_total()); ?></span>
            </button>
        </a>
    </div>
</div>
