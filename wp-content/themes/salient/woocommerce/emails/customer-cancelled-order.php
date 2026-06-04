<?php
/**
 * Customer cancelled order email
 *
 * @see https://woocommerce.com/document/template-structure/
 * @version 10.0.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php
echo $email_improvements_enabled ? '<div class="email-introduction">' : '';

/* translators: %1$s: Order number */
$text = __( 'We’re sorry to let you know that your order #%1$s has been cancelled.', 'woocommerce' );

if ( $email_improvements_enabled ) {
	$text = __( 'We’re getting in touch to let you know that your order #%1$s has been cancelled.', 'woocommerce' );
}
?>

<p><?php printf( esc_html( $text ), esc_html( $order->get_order_number() ) ); ?></p>

<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
?>

<?php
if ( $additional_content ) {
	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="email-additional-content">' : '';
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	echo $email_improvements_enabled ? '</td></tr></table>' : '';
}
?>

<!-- ================= SOCIAL MEDIA ICONS START ================= -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 25px 0 10px 0;">
    <tr>
        <td align="center" style="padding: 10px 0;">

	  <!-- Facebook-->
            <a href="https://www.facebook.com/people/Use-Less-Beauty/61584242297385/" target="_blank" style="margin: 0 12px; display: inline-block;">
                <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png"
                     width="30" height="30" alt="LinkedIn" style="display:block;">
            </a>

            <!-- Instagram -->
            <a href="https://www.instagram.com/use.less.beauty/" target="_blank" style="margin: 0 12px; display: inline-block;">
                <img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png"
                     width="30" height="30" alt="Instagram" style="display:block;">
            </a>

            <!-- TikTok -->
            <a href="https://www.tiktok.com/@useless.beautyme" target="_blank" style="margin: 0 12px; display: inline-block;">
                <img src="https://cdn-icons-png.flaticon.com/512/3046/3046121.png"
                     width="30" height="30" alt="TikTok" style="display:block;">
            </a>

            <!-- LinkedIn -->
            <a href="https://www.linkedin.com/company/use-less-beauty/" target="_blank" style="margin: 0 12px; display: inline-block;">
                <img src="https://cdn-icons-png.flaticon.com/512/3536/3536505.png"
                     width="30" height="30" alt="LinkedIn" style="display:block;">
            </a>

        </td>
    </tr>
</table>
<!-- ================= SOCIAL MEDIA ICONS END ================= -->

<?php
do_action( 'woocommerce_email_footer', $email );
