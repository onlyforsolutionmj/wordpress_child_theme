<?php
/**
 * Email Order Items
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-items.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text_align = is_rtl() ? 'right' : 'left';
foreach ( $items as $item_id => $item ) :
	if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		$product = $item->get_product();
		?>
        <tr>
            <td style="text-align:left; vertical-align:middle; border:1px solid #eee; word-wrap:break-word; padding: 10px;">
                <?php
                // Product name
                echo apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false );

                // SKU
                if ( $show_sku && is_object( $product ) && $product->get_sku() ) {
	                echo ' (#' . $product->get_sku() . ')';
                }

                // allow other plugins to add additional product information here
                do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

                wc_display_item_meta( $item );

                if ( $show_download_links ) {
	                wc_display_item_downloads( $item );
                }

                // allow other plugins to add additional product information here
                do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );
                ?>
            </td>
            <td style="text-align:left; vertical-align:middle; border:1px solid #eee; padding:12px;">
                <?php echo apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ); ?>
            </td>
            <td style="text-align:left; vertical-align:middle; border:1px solid #eee; padding:10px;">
	            <?php echo $order->get_formatted_line_subtotal( $item ); ?>
            </td>
        </tr>
		<?php
	}

endforeach;
?>
