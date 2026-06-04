<?php
/**
 * Footer
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/components/footer.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced) || empty($cart)) {
    return;
}

$tabs = (!empty($advanced['tabs'])) ? $advanced['tabs'] : [];
$uwpmc_tab_style = (!empty($style['tabs']) ? $style['tabs'] : '') . $style['card']
    . (empty($tabs['cart']['enable']) ? 'border: none;' : '');
$uwpmc_tabs_count = count($tabs);
?>
<div class="uwpmc-footer">
    <div class="uwpmc-tabs"
         style="display: flex; <?php echo esc_attr($uwpmc_tab_style); ?>">
        <?php if (!empty($tabs)) {
            $uwpmc_tab_count = 1;
            foreach ($tabs as $uwpmc_tab_slug => $tab) { ?>
                <button id="uwpmc-<?php echo esc_attr($uwpmc_tab_slug); ?>-button" class="uwpmc-tab-button
                <?php echo ($uwpmc_tab_count == 1) ? 'uwpmc-active-page' : '' ?>"
                        style="<?php if (empty($tab['enable'])) echo 'display: none;' ?>
                        <?php echo ($uwpmc_tab_count == 1)
                            ? 'border-radius: 0 0 0 6px;'
                            : ($uwpmc_tab_count == $uwpmc_tabs_count ? 'border-radius: 0 0 6px 0;' : 'border-radius: 0;') ?>">
                    <?php echo esc_html($tab['title']); ?>
                </button>
                <?php $uwpmc_tab_count += 1; ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>
