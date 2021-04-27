<?php get_header(); ?>
<?php
  $parent = get_post($post->post_parent);
  $state = "";
  switch ($parent->post_title) {
    case 'NSW':
      $state = "Sydney";
      break;
    case 'VIC':
      $state = "Melbourne";
      break;
    default:
      # code...
      break;
  }
?>
<div class="page-padding post post-69572 page type-page status-publish hentry">
<div data-midnight="dark-title" class="row wpb_row row-fluid align-center"><div class="wpb_column columns medium-12 large-7 medium-10 thb-dark-column small-12"><div class="vc_column-inner "><div class="wpb_wrapper">
<div class="wpb_text_column wpb_content_element  ">
<div class="wpb_wrapper">
<h1 class="custom page-title">Flower Delivery to<br>
<?php the_title(); ?></h1>
</div>
</div>
</div></div></div></div><div data-midnight="dark-title" class="row wpb_row row-fluid"><div class="wpb_column columns medium-12 thb-dark-column small-12"><div class="vc_column-inner vc_custom_1521069451403"><div class="wpb_wrapper">
<div class="wpb_text_column wpb_content_element   vc_custom_1561581475841">
<div class="wpb_wrapper">
<div class="woocommerce columns-4 "><div class="woocommerce-notices-wrapper"></div>
<?php echo do_shortcode('[products limit="18" orderby="date" order="dec" paginate="true"]'); ?>

</div>
</div>
</div>
</div></div></div></div><div data-midnight="dark-title" class="row wpb_row row-fluid align-center vc_custom_1521069717570"><div class="align-center wpb_column columns medium-12 large-8 medium-10 thb-dark-column small-12"><div class="vc_column-inner "><div class="wpb_wrapper">

<div class="wpb_text_column wpb_content_element  ">
<div class="wpb_wrapper">
<h2>Fig &amp; Bloom is a family-owned local florist in <?=$state; ?></h2>
<p>Our team of creative floral designers are the best in the business. Every floral arrangement that leaves our studio is handmade with love and the utmost attention to detail. We accept orders for same day flower delivery until 1 pm each day. Order flowers online and one of our friendly and professional drivers will deliver your flowers to <strong><?php the_title(); ?></strong>.</p>
<p>If you have any questions or want to talk with someone who can help process your order, please get in touch via one of the methods on our <a href="/contact-us/">Contact Us</a> page</p>
</div>
</div>
<div class="vc_empty_space" style="height: 50px"><span class="vc_empty_space_inner"></span></div>
<div class="wpb_text_column wpb_content_element  ">
<div class="wpb_wrapper">
<h3>Fresh contemporary flowers delivered to <?php the_title(); ?></h3>
<p><a href="/">Fig &amp; Bloom</a> is a local <?=$state; ?> florist dedicated to delivering premium, contemporary flower bouquets to <strong><?php the_title(); ?></strong>. We source our flowers from the best local Australian growers at the wholesale market each morning, which means your flowers last as long as possible.</p>
</div>
</div>
<div class="vc_empty_space" style="height: 50px"><span class="vc_empty_space_inner"></span></div>
<div class="wpb_text_column wpb_content_element  ">
<div class="wpb_wrapper">
<h3 class="normal"><span lang="EN">It’s true—we send you a photo!</span></h3>
<p>Fig &amp; Bloom is different from any other florist. We believe seeing the flowers is half the fun! When your order has been delivered, we’ll send you a confirmation email along with a photo of your unique order.</p>
</div>
</div>
<div class="vc_empty_space" style="height: 50px"><span class="vc_empty_space_inner"></span></div>
<div class="wpb_text_column wpb_content_element  ">
<div class="wpb_wrapper">
<h3 class="normal"><span lang="EN">Same day delivery by 5 pm<br>
</span></h3>
<p>Need your flowers delivered to <strong><?php the_title(); ?></strong> faster? No problem, we have a priority delivery service and can dispatch your order with the next available driver (a surcharge applies).</p>
<p>If our driver has any difficulty delivering your order, they’ll call the recipient. And if they can’t get in touch with the recipient, the driver will leave your flowers at the front door (assuming it is safe to do so).</p>
</div>
</div>
<div class="vc_empty_space" style="height: 50px"><span class="vc_empty_space_inner"></span></div>
<div class="wpb_text_column wpb_content_element  ">
<div class="wpb_wrapper">
<h3>We also deliver to these nearby areas</h3>
<style type="text/css">
	
	.button--nearby-suburb a {
    color: #151515;
    text-decoration: none;
    display: inline-block;
    padding: 5px 10px;
    margin: 10px 10px 10px 0;
    border: 1px solid #151515;
}
</style>
<?php
$args = array(
      'posts_per_page' => 5,
      'orderby' => 'rand',
      'post_status'=>'publish',
      'post_type'   => 'delivery-suburb',
      'post_parent'=> $post->post_parent
    );
     
    $states = get_posts( $args );

    $output = "";
    if(!empty($states)):
        foreach($states as $state):
            
            $output .= '<div class="button--nearby-suburb" ><a href="'.esc_url( get_permalink($state->ID) ).'">'.$state->post_title.'</a></div>';
        endforeach;
            echo $output;
    endif;
?>
<div class="button--nearby-suburb no-border">and <a href="/delivery-suburbs/">many more</a></div>
</div>
</div>
</div></div></div></div>

<div data-midnight="dark-title" class="row wpb_row row-fluid align-center"><div class="wpb_column columns medium-12 large-10 medium-10 thb-dark-column small-12"><div class="vc_column-inner "><div class="wpb_wrapper">
<div class="wpb_raw_code wpb_content_element wpb_raw_html">
<div class="wpb_wrapper">
	<div class="productreviewwidget" data-listing-id="4fbd0588-ae85-309a-9cab-9ab18efdc655" data-full-width="1" data-num-reviews="6" data-order="best" data-layout="horizontal" data-theme-id="light" data-version="1"></div>
<script type="text/javascript">
    (function() {
      function async_load(){
          var s = document.createElement("script");
          s.type = "text/javascript";
          s.async = true;
          s.src = '//www.productreview.com.au/assets/js/widget/reviews-itemid.js';
          var x = document.getElementsByTagName("script")[0];
          x.parentNode.insertBefore(s, x);
      }
      if (window.attachEvent) {
          window.attachEvent("onload", async_load);
      } else {
          window.addEventListener("load", async_load, false);
      }
    })();
</script>
</div>
</div>
<div class="vc_empty_space" style="height: 100px"><span class="vc_empty_space_inner"></span></div></div></div></div></div>


<?php get_footer(); ?>