<?php
/**
 * Coupon
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/components/coupon.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced) || empty($cart)) {
    return;
}

$uwpmc_coupon_badge_style = $style['component_style'] . $style['card'] . $style['item'];
?>
<div class="uwpmc-coupon-section uwpmc-border" style="<?php echo esc_attr($style['coupon']); ?>">
    <div id="uwpmc-add-coupon" style="background-color: inherit; color: inherit;">
        <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
            <input id="uwpmc-coupon-input" class="uwpmc-component-layout" type="text"
                   style="<?php echo esc_attr($style['component_style']); ?>"
                   placeholder="<?php esc_html_e('Coupon code', 'upsellwp-mini-cart'); ?>">
            <button type="button" class="uwpmc-action uwpmc-coupon-apply" id="uwpmc-apply-coupon"
                    style="padding: 2px; width: 36%; border: none; <?php echo esc_attr($style['action']); ?>">
                <?php esc_html_e('Apply', 'upsellwp-mini-cart'); ?>
            </button>
        </div>
    </div>
    <div class="uwpmc-coupons" style="display: flex; align-items: center; width: 100%; padding: 5px 0;">
        <?php if (!empty($cart->get_applied_coupons())) { ?>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div id="uwpmc-coupon-list" style="display: flex; align-items: center;">
                    <?php foreach ($cart->get_applied_coupons() as $uwpmc_applied_coupon) : ?>
                        <div id="uwpmc-coupon-badge" class="uwpmc-component-layout uwpmc-coupon"
                             style="display: flex; <?php echo esc_attr($uwpmc_coupon_badge_style); ?>">
                            <span style="display: flex; align-items: center; padding: 0 0 0 4px;">
                                <svg width="14px" height="14px" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.4919 9.36639L14.6286 3.50306C13.6786 2.55306 12.3686 2.04306 11.0286 2.11306L6.02859 2.35306C4.02859 2.44306 2.43859 4.03306 2.33859 6.02306L2.09859 11.0231C2.03859 12.3631 2.53859 13.6731 3.48859 14.6231L9.35192 20.4864C11.2119 22.3464 14.2319 22.3464 16.1019 20.4864L20.4919 16.0964C22.3619 14.2464 22.3619 11.2264 20.4919 9.36639ZM8.82859 11.7131C7.23859 11.7131 5.94859 10.4231 5.94859 8.83306C5.94859 7.24306 7.23859 5.95306 8.82859 5.95306C10.4186 5.95306 11.7086 7.24306 11.7086 8.83306C11.7086 10.4231 10.4186 11.7131 8.82859 11.7131Z"
                                          fill="currentColor"/>
                                </svg>
                            </span>
                            <span type="text" id="uwpmc-coupon"
                                  style="color: inherit; background-color: inherit; font-size: 14px !important; padding: 4px 2px;">
                                <?php echo esc_attr($uwpmc_applied_coupon); ?>
                            </span>
                            <span id="uwpmc-remove-coupon" data-coupon="<?php echo esc_attr($uwpmc_applied_coupon); ?>"
                                  class="uwpmc-coupon"
                                  style="cursor: pointer; display: flex; padding: 4px; border: none !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; <?php echo esc_attr($style['item']); ?> <?php echo esc_attr($style['card']); ?>">
                                <svg width="14px" height="14px" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_2397_54998)">
                                        <path d="M13.0588 11.646L12.704 11.9999L13.0588 12.3539L17.3587 16.6439L17.3598 16.6449C17.4067 16.6914 17.4439 16.7467 17.4692 16.8077C17.4946 16.8686 17.5077 16.9339 17.5077 16.9999C17.5077 17.0659 17.4946 17.1313 17.4692 17.1922C17.4439 17.2532 17.4067 17.3085 17.3598 17.3549L17.3569 17.3578C17.3104 17.4047 17.2551 17.4419 17.1942 17.4673C17.1333 17.4927 17.0679 17.5057 17.0019 17.5057C16.9359 17.5057 16.8705 17.4927 16.8096 17.4673C16.7487 17.4419 16.6934 17.4047 16.6469 17.3578L16.6459 17.3568L12.3559 13.0568L12.0019 12.702L11.6479 13.0568L7.35793 17.3568L7.35689 17.3578C7.31041 17.4047 7.25511 17.4419 7.19418 17.4673C7.13325 17.4927 7.0679 17.5057 7.00189 17.5057C6.93588 17.5057 6.87053 17.4927 6.8096 17.4673C6.74868 17.4419 6.69338 17.4047 6.64689 17.3578L6.64399 17.3549C6.59713 17.3085 6.55993 17.2532 6.53455 17.1922C6.50916 17.1313 6.49609 17.0659 6.49609 16.9999C6.49609 16.9339 6.50916 16.8686 6.53455 16.8077C6.55993 16.7467 6.59713 16.6914 6.64399 16.6449L6.64503 16.6439L10.945 12.3539L11.2998 11.9999L10.945 11.646L6.64545 7.35639C6.64539 7.35633 6.64533 7.35627 6.64527 7.35622C6.55085 7.2617 6.4978 7.13355 6.4978 6.99994C6.4978 6.86625 6.55091 6.73803 6.64545 6.64349C6.73998 6.54896 6.8682 6.49585 7.00189 6.49585C7.1355 6.49585 7.26365 6.54889 7.35817 6.64332C7.35822 6.64338 7.35828 6.64344 7.35834 6.64349L11.6479 10.9431L12.0019 11.2979L12.3559 10.9431L16.6454 6.64349C16.6455 6.64347 16.6455 6.64345 16.6455 6.64342C16.74 6.54893 16.8682 6.49585 17.0019 6.49585C17.1356 6.49585 17.2638 6.54896 17.3583 6.64349C17.4529 6.73803 17.506 6.86625 17.506 6.99994C17.506 7.13359 17.4529 7.26177 17.3584 7.35629C17.3584 7.35633 17.3584 7.35636 17.3583 7.35639L13.0588 11.646Z"
                                              fill="currentColor" stroke="currentColor"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2397_54998">
                                            <rect width="24" height="24" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>