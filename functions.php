<?php

/** Add Javascript to Checkout page */
function add_checkout_page_js() {
  ?>
  <script type="text/javascript">window.jQuery(function ($) {
    $(document).ready(function () {
      console.log('Hello, Checkout')
    })
  })
  </script>
  <?php

}

add_action( 'woocommerce_after_checkout_form', 'add_checkout_page_js');

/** Add Javascript to Cart page */
function add_cart_page_js() {
  ?>
  <script type="text/javascript">window.jQuery(function ($) {
    /** Hide Coupon Input */
    function hideCouponFromCart () {
      var isViewingCart = RegExp("cart").test(window.location.href)
      if (!isViewingCart) return
      window.setInterval(function () {
        window.jQuery(".coupon").hide()
      }, 250)
    }

    $(document).ready(function () {
      hideCouponFromCart()
    })
  })
  </script>
  <?php
}

add_action('woocommerce_after_cart', 'add_cart_page_js');

/** Add momentJS to footer */
function add_moment_js_to_footer() {
  ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <?php
}

add_action('wp_footer', 'add_moment_js_to_footer');
