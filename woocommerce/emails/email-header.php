<?php
/**
 * Email Header
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-header.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 4.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
    <link rel="stylesheet" href="https://use.typekit.net/ntc4bdt.css">
</head>
<body>
<center>
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" id="bodyTable" background="https://www.figandbloom.com.au/wp-content/uploads/2021/04/HandRose_Black_1500px-01.png" style="height:100%;background-repeat:no-repeat;background-size:cover;background-color:#ffffff;">
    <tr>
        <td style="height:150px;"></td>
    </tr>
    <tr>
        <td align="center" valign="top" id="bodyCell">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" class="templateContainer">
        <!-- BEGIN HEADER // -->
        <tr>
            <td valign="top" id="templateHeader" style="text-align:center;">
            <!-- BEGIN MODULE: HEADER IMAGE // -->
            <img style="width:70%; padding: 50px 0;" src="https://www.figandbloom.com.au/wp-content/uploads/2021/04/FB_Logo_Horizontal_BLK_914x200-1.png" class="templateImage" mc:label="header_image" mc:edit="header_image" mc:allowdesigner="" mc:allowtext="" alt="Logo-Horizontal-Black-920px.png?auto=compress%2Cformat&amp;ixlib=php-1.2.1&amp;s=7db8f65e4c7da49845260df4045e593f">
            <!-- // END MODULE: HEADER IMAGE -->
            </td>
        </tr>
        <!-- // END HEADER -->