<?php
/**
 * Header
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/templates/style-1/components/header.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($data) || empty($style) || empty($advanced)) {
    return;
}
?>
<div id="uwpmc-notification" style="display: none; position: relative; width: 100%;">
    <div id="uwpmc-message" class="uwpmc-border" style="<?php echo esc_attr($style['card']); ?>"></div>
</div>
<div class="uwpmc-header" style="<?php echo esc_attr($style['header']); ?>">
    <div style="width: 100%; display: flex; justify-content: space-between; align-items: center; line-height: 1; gap: 8px;">
        <h2 class="uwpmc-header-title"
            style="color: inherit; font-weight: 600; font-size: inherit; margin: 0; text-align: center; padding: 0 10px;">
            <?php
                 //phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
                 echo wp_kses_post(__($data['header']['title'], 'upsellwp-mini-cart') ?? '');
            ?>
        </h2>
        <span id="uwpmc-close-cart"
              style="min-width: 24px; width: auto; display: flex; gap: 8px; align-items: center; padding: 4px; border-radius: 40px; font-size: 18px;">
        <svg width="24px" height="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <g>
                <path d="M13.0588 11.646L12.704 11.9999L13.0588 12.3539L17.3587 16.6439L17.3598 16.6449C17.4067 16.6914 17.4439 16.7467 17.4692 16.8077C17.4946 16.8686 17.5077 16.9339 17.5077 16.9999C17.5077 17.0659 17.4946 17.1313 17.4692 17.1922C17.4439 17.2532 17.4067 17.3085 17.3598 17.3549L17.3569 17.3578C17.3104 17.4047 17.2551 17.4419 17.1942 17.4673C17.1333 17.4927 17.0679 17.5057 17.0019 17.5057C16.9359 17.5057 16.8705 17.4927 16.8096 17.4673C16.7487 17.4419 16.6934 17.4047 16.6469 17.3578L16.6459 17.3568L12.3559 13.0568L12.0019 12.702L11.6479 13.0568L7.35793 17.3568L7.35689 17.3578C7.31041 17.4047 7.25511 17.4419 7.19418 17.4673C7.13325 17.4927 7.0679 17.5057 7.00189 17.5057C6.93588 17.5057 6.87053 17.4927 6.8096 17.4673C6.74868 17.4419 6.69338 17.4047 6.64689 17.3578L6.64399 17.3549C6.59713 17.3085 6.55993 17.2532 6.53455 17.1922C6.50916 17.1313 6.49609 17.0659 6.49609 16.9999C6.49609 16.9339 6.50916 16.8686 6.53455 16.8077C6.55993 16.7467 6.59713 16.6914 6.64399 16.6449L6.64503 16.6439L10.945 12.3539L11.2998 11.9999L10.945 11.646L6.64545 7.35639C6.64539 7.35633 6.64533 7.35627 6.64527 7.35622C6.55085 7.2617 6.4978 7.13355 6.4978 6.99994C6.4978 6.86625 6.55091 6.73803 6.64545 6.64349C6.73998 6.54896 6.8682 6.49585 7.00189 6.49585C7.1355 6.49585 7.26365 6.54889 7.35817 6.64332C7.35822 6.64338 7.35828 6.64344 7.35834 6.64349L11.6479 10.9431L12.0019 11.2979L12.3559 10.9431L16.6454 6.64349C16.6455 6.64347 16.6455 6.64345 16.6455 6.64342C16.74 6.54893 16.8682 6.49585 17.0019 6.49585C17.1356 6.49585 17.2638 6.54896 17.3583 6.64349C17.4529 6.73803 17.506 6.86625 17.506 6.99994C17.506 7.13359 17.4529 7.26177 17.3584 7.35629C17.3584 7.35633 17.3584 7.35636 17.3583 7.35639L13.0588 11.646Z"
                      fill="currentColor" stroke="currentColor"/>
            </g>
        </svg>
    </span>
    </div>
</div>
