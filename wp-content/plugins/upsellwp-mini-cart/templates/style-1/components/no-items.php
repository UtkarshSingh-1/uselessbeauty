<?php
/**
 * No items
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/components/no-items.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced)) {
    return;
}

$uwpmc_block_styles = $style['item'] . $style['card'];

?>
<div style="height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 0 12px 6px 12px; border-radius: 6px; <?php echo esc_attr($uwpmc_block_styles); ?>">
    <div style="display: flex; justify-content: center; align-items: center;">
        <?php esc_html_e('Your cart is empty.', 'upsellwp-mini-cart'); ?>
    </div>
    <div class="uwpmc-actions" style="justify-content: center; padding: 2px 8px; max-width: 274px;">
        <a class="uwpmc-action" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"
           style="text-decoration: none; <?php echo esc_attr($style['action']); ?>">
            <button style="width: 100%; height: 100%; background-color: inherit; color: inherit; border-radius: 6px;">
                <?php esc_html_e('Return to shop', 'upsellwp-mini-cart'); ?>
            </button>
        </a>
    </div>
</div>