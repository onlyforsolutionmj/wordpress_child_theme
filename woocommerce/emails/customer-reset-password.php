<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
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
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<tr>
    <td valign="top" id="templateBody">
        <div>
            <h1>Reset your password</h1>
            <p>Someone requested a password reset for the following account</p>
            <div class="spacer 20px"></div>
            <h3>Your account details</h3>
            <p style="background:#f8f8f8; border: 1px solid #e4e4e4; padding: 20px;"><strong>Username:&nbsp;</strong><?php echo esc_html($user_login); ?></p>
            <p>If this was a mistake, you can ignore this email and nothing will happen.</p>
            <p><?php _e( 'Otherwise please ', 'woocommerce' ); ?>
                <a class="link" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'login' => rawurlencode( $user_login ) ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>">
                    <?php _e( 'click here', 'woocommerce' ); ?></a> to reset your password.
            </p>
            <p>If you have questions about accessing your account please email us on <a href="mailto:service@figandbloom.com.au">service@figandbloom.com.au</a><</p>
            <p style="margin-top: 20px;">With love,<br />
                Fig &amp; Bloom</p>
        </div>
        <div class="spacer 20px"></div>
    </td>
</tr>
<?php do_action( 'woocommerce_email_footer', $email ); ?>
