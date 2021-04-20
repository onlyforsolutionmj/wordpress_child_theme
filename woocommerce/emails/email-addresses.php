<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if($order){
	?>
    <table style="width:100%;margin-top:50px;" class="customer-details">
        <tbody>
        <tr>
            <td valign="top" width="50%">
                <p><strong>Billing Address</strong></p>
                <p><?php echo $order->get_formatted_billing_address(); ?></p>
            </td>
            <td valign="top" width="50%">
                <p><strong>Shipping Address</strong></p>
                <p><?php
                    if($order->get_formatted_shipping_address()!=''){
	                    echo $order->get_formatted_shipping_address();
                    }
                    else
                    {
	                    echo $order->get_formatted_billing_address();
                    }

                    ?></p>
            </td>
        </tr>
        </tbody>
    </table>
	<?php
}
