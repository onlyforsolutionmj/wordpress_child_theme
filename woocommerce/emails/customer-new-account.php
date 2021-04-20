<?php
/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
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
            <h1>Rewards Account Created</h1>
            <p>Thank you for shopping with Fig &amp; Bloom. We've created an account for you so you can take advantage of our rewards program and track the progress of your orders. Your username and password was generated automatically.</p>
            <div class="spacer 20px"></div>
            <h3>Login details</h3>
            <p style="background:#f8f8f8; border: 1px solid #e4e4e4; padding: 20px;"><strong>Username:&nbsp;</strong><?php echo esc_html($user_login); ?><br />
            <?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>
            <strong>Password:&nbsp;&nbsp;</strong><?php echo esc_html($user_pass); ?></p>
            <?php endif; ?>
            <div class="spacer 20px"></div>
            <h3>Accessing your account</h3>
            <p>You can access your account area to view your orders and change your password here: <?php echo make_clickable(esc_url(wc_get_page_permalink('myaccount'))); ?></p>
            <p>If you have problems accessing your account please email us on <a href="mailto:service@figandbloom.com.au">service@figandbloom.com.au</a></p>
            <p style="margin-top: 20px;">With love,<br />
                Fig &amp; Bloom</p>
        </div>
        <div class="spacer 20px"></div>
    </td>
</tr>
<?php do_action( 'woocommerce_email_footer', $email );
