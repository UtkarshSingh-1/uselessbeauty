<?php
/**
 * Banner
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/components/banner.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced)) {
    return;
}
?>

<div class="uwpmc-banners"
     style="<?php echo (!empty($advanced['banner']['enabled']) && !empty($advanced['banner']['list'])) ? 'display: flex;' : 'display: none;'; ?>">
    <?php if (!empty($advanced['banner']['list'])) {
        foreach ($advanced['banner']['list'] as $uwpmc_banner_key => $uwpmc_banner) {
            if (!empty($uwpmc_banner['image']) && !empty(wp_get_attachment_image($uwpmc_banner['image']))) {
                $uwpmc_banner_display_content = wp_get_attachment_image($uwpmc_banner['image'], 'small');
            } else {
                //phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
                $uwpmc_banner_display_content = __($uwpmc_banner['content'] ?? '', 'upsellwp-mini-cart');
            }
            ?>
            <div id="<?php echo esc_attr($uwpmc_banner_key); ?>" class="uwpmc-banner" style="<?php echo esc_attr($uwpmc_banner['style']); ?>">
                <?php echo wp_kses_post($uwpmc_banner_display_content); ?>
            </div>
        <?php }
    } ?>
</div>
