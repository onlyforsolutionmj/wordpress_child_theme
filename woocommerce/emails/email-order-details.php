<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border:1px solid #e4e4e4;">
    <thead>
    <tr>
        <th scope="col" style="text-align:left; font-weight: bold; padding:10px; border:1px solid #e4e4e4;">Product</th>
        <th scope="col" style="text-align:left; font-weight: bold; padding:10px; border:1px solid #e4e4e4;">Quantity</th>
        <th scope="col" style="text-align:left; font-weight: bold; padding:10px; border:1px solid #e4e4e4;">Price</th>
    </tr>
    </thead>
    <tbody>
	<?php echo wc_get_email_order_items( $order, array(
		'show_sku'      => $sent_to_admin,
		'show_image'    => false,
		'image_size'    => array( 32, 32 ),
		'plain_text'    => $plain_text,
		'sent_to_admin' => $sent_to_admin,
	) ); ?>
    </tbody>
    <tfoot>
	<?php
	if ( $totals = $order->get_order_item_totals() ) {
		$i = 0;
		foreach ( $totals as $total ) {
			$i++;
			?><tr>
            <th colspan="2" scope="row" style="text-align:left;border:1px solid #e4e4e4;padding:10px"><?php echo $total['label']; ?></th>
            <td style="border: 1px solid #e4e4e4;"><?php echo $total['value']; ?></td>
            </tr><?php
		}
	}
	?>
    </tfoot>
</table>