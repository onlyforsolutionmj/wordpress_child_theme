<?php
/**
 * Customer refunded order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-refunded-order.php.
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
            <p>Just letting you know that your order has been refunded.</p>
            <div class="spacer 20px"></div>
            <h3 style="margin-bottom:20px;">Here's a reminder of what you sent</h3>
            <p style="font-weight:bold;">Order #<?php echo $order->get_order_number();?></p>
            <?php do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email ); ?>
            <?php do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email ); ?>
        </div>
        <div class="spacer 20px"></div>
    </td>
</tr>
<?php do_action( 'woocommerce_email_footer', $email ); ?>
