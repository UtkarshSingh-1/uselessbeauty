<?php
/**
 * Customer completed order email
 *
 * @see https://woocommerce.com/document/template-structure/
 * @version 9.9.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
<p>
<?php
if ( ! empty( $order->get_billing_first_name() ) ) {
	printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) );
} else {
	echo esc_html__( 'Hi,', 'woocommerce' );
}
?>
</p>

<p><?php esc_html_e( 'Great news – We have delivered your order successfully. We hope you love your waterless beauty essentials and experience true #guiltfreecare.', 'woocommerce' ); ?></p>

<?php if ( $email_improvements_enabled ) : ?>
	<p><?php esc_html_e( 'Here is a summary of what you have ordered:', 'woocommerce' ); ?></p>
<?php endif; ?>

<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
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
