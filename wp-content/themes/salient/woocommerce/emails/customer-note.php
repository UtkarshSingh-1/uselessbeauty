<?php
/**
 * Customer note email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-note.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 10.1.0
 */

use Automattic\WooCommerce\Utilities\FeaturesUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email_improvements_enabled = FeaturesUtil::feature_is_enabled( 'email_improvements' );

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php echo $email_improvements_enabled ? '<div class="email-introduction">' : ''; ?>
<p>
<?php
if ( ! empty( $order->get_billing_first_name() ) ) {
	/* translators: %s: Customer first name */
	printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) );
} else {
	printf( esc_html__( 'Hi,', 'woocommerce' ) );
}
?>
</p>
<p><?php esc_html_e( 'The following note has been added to your order:', 'woocommerce' ); ?></p>

<blockquote>
<?php
$safe_note = wc_wptexturize_order_note( $customer_note );
echo wpautop( make_clickable( $safe_note ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
</blockquote>

<p><?php esc_html_e( 'As a reminder, here are your order details:', 'woocommerce' ); ?></p>
<?php echo $email_improvements_enabled ? '</div>' : ''; ?>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo $email_improvements_enabled ? '<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td class="email-additional-content">' : '';
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
	echo $email_improvements_enabled ? '</td></tr></table>' : '';
}

/**
 * Additional content
 */

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