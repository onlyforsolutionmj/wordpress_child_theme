<?php
/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 4.0.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<tr>
    <td valign="top" id="templateBody">
        <div>
            <h1><?php echo $email_heading; ?></h1>
            <p>Our driver has just delivered the stunning blooms you chose. Speaking of choice, thank you&nbsp;for choosing Fig &amp; Bloom!</p>
            <p>We're sure <?php echo $order->get_shipping_first_name(); ?> will enjoy watching the flowers bloom over the coming week.</p>
            <p>With love,<br />
                Kellie xx</p>
            <p style="margin-top:40px;">PS: we'll send a separate email in a moment with a photo of your order. Thanks again for choosing Fig &amp; Bloom!</p>
        </div>
        <div class="spacer 20px"></div>
    </td>
</tr>
<?php do_action( 'woocommerce_email_footer', $email ); ?>