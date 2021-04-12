<?php

/**
 * Add javascript to checkout page
 */
add_action( 'woocommerce_after_checkout_form', 'add_checkout_page_js');
 
function add_checkout_page_js() {
echo '<script type="text/javascript">window.jQuery(function ($) {
  $(document).ready(function () {
    console.log("Hello, World!")
  })
})</script>';
}