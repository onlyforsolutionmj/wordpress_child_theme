<?php

/** Add momentJS to footer */
add_action('wp_footer', 'add_moment_js_to_footer');
function add_moment_js_to_footer() {
  ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <?php
}


/** Add Javascript to Checkout page */
add_action( 'woocommerce_after_checkout_form', 'add_checkout_page_js');
function add_checkout_page_js() {
  ?>
  <script type="text/javascript">window.jQuery(function ($) {
    function isPastOrderDeadline () {
      var dateString = window.jQuery('#delivery_date').val()
      var date = window.moment(dateString, 'MMMM DD, YYYY')
      var isSameDate = window.moment().isSame(date, 'day')
      var isPastHour = window.moment().hour() >= 13
      var isPastMinute = window.moment().minute() > 10
      return isSameDate && isPastHour && isPastMinute
    }

    function isInThePast () {
      var userInput = window.jQuery('#delivery_date').val()
      var dateDelivery = window.moment(userInput, 'MMMM DD, YYYY').hour(12).minute(0)
      var dateToday = window.moment().hour(12).minute(0)
      return dateDelivery.diff(dateToday, 'minutes') <= -1440
    }

    function createAccountTrue () {
      window.jQuery('#createaccount').prop('checked', true)
    }

    function alterHTML () {
      $('.woocommerce-shipping-fields__field-wrapper').prepend('<h3>Delivery details</h3>')
    }

    function reduceCheckoutAbandonment () {
      var pattern = RegExp('/checkout/')
      if (pattern.test(window.location.href)) {
        // disable logo link
        window.jQuery('.logolink').prop('href', '/cart/')
        // hide header on desktop
        window.jQuery('.menu-holder').children().hide()
        window.jQuery('.menu-holder').prepend('<li style="list-style-type:none;" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9330"><a href="javascript:history.back()">🠐 Back to Cart</a></li>')
        // hide header on mobile
        jQuery('header').children().hide()
        jQuery('header').prepend('<a style="padding-left:1rem; font-size: 1.2rem;" href="javascript:history.back()">🠐 Back to Cart</a>')
        // hide the footer
        window.jQuery('footer').css('background', '#fff').children().hide()
      }
    }

    function rejectInvalidDeliveryDate () {
      function getDeliveryCity () {
        // input must exist
        var el = window.jQuery('#shipping_postcode')
        if (!el.val()) return

        // input must be 4 characters
        var input = el.val().trim()
        if (input.length < 4) {
          throw new Error('Invalid postcode. Length must be 4.')
        }

        var firstChar = input.slice(0, 1)
        switch (firstChar) {
          case '2': {
            return 'Sydney'
          }
          case '3': {
            return 'Melbourne'
          }
          default: {
      return 'Other'
            //return null
          }
        }
      }

      function checkIsClosedForHoliday (city, date) {
        console.log('Checking for holiday closure', city, date)
        var message = ''
        var closures = [
          {
            city: ['Melbourne', 'Sydney'],
            date: 'April 25, 2021',
            message: "Sorry, we are closed on Sunday (except Mother's Day)."
          },
          {
            city: ['Melbourne', 'Sydney'],
            date: 'May 2, 2021',
            message: "Sorry, we are closed on Sunday (except Mother's Day)."
          },
          {
            city: ['Melbourne', 'Sydney'],
            date: 'May 16, 2021',
            message: "Sorry, we are closed on Sunday (except Mother's Day)."
          },
          {
            city: ['Melbourne', 'Sydney'],
            date: 'May 16, 2021',
            message: "Sorry, we are closed on Sunday (except Mother's Day)."
          }
        ]

        for (var i = 0; i < closures.length; i++) {
          var closure = closures[i]
          if (closure.date === date) {
            for (var j = 0; j < closure.city.length; j++) {
              if (closure.city[j] === city) {
                message = closure.message
              }
            }
          }
        }

        return message
      }

      function checkIsClosedWeekday (day) {
        if (!(day >= 0 && day <= 6)) {
          console.error('Range error. Expect number between 0-6')
          return 'Unexpected error. Please check postcode is valid.'
        }
        // alert on invalid postcode
        var city = getDeliveryCity()
        if (!city) {
          return 'Please enter valid postcode before choosing delivery date.'
        }
        // alert on store closure
        var messages = {
          'Sydney': [
            /* Sunday */
            '',
            /* Monday */
            '',
            /* Tuesday */
            '',
            /* Wednesday */
            '',
            /* Thursday */
            '',
            /* Friday */
            '',
            /* Saturday */
            ''
          ],
          'Melbourne': [
            /* Sunday */
            '',
            /* Monday */
            '',
            /* Tuesday */
            '',
            /* Wednesday */
            '',
            /* Thursday */
            '',
            /* Friday */
            '',
            /* Saturday */
            ''
          ]
        }
        return messages[city][day]
      }

      window.jQuery('#delivery_date').change(function (event) {
        // Only run of moment library is available
        if (!window.moment) return

        // Sanitize string input and convert to integer
        var input = window.jQuery(this).val().trim()
        if (input === '') return

        // Transform text input to integer with range 0 - 6
        var dayInt = window.moment(input, 'MMMM D, YYYY').weekday()

        // Log error if shop is closed
        try {
          var city = getDeliveryCity()

          var rejectSameDayDelivery = isPastOrderDeadline()
          if (rejectSameDayDelivery) {
            window.alert('You missed our 1 pm cut-off for delivery today. Please choose a different date.')
            window.jQuery(this).val('')
            return
          }

          var rejectTimeTravel = isInThePast()
          if (rejectTimeTravel) {
            window.alert('Selected date is the past. Please choose a different date.')
            window.jQuery(this).val('')
            return
          }

          var holidayClosureMessage = checkIsClosedForHoliday(city, input)
          if (holidayClosureMessage) {
            window.alert(holidayClosureMessage)
            window.jQuery(this).val('')
            return
          }

          var weekdayClosureMessage = checkIsClosedWeekday(dayInt)
          if (weekdayClosureMessage) {
            window.alert(weekdayClosureMessage)
            window.jQuery(this).val('')
            return
          }
        } catch (e) {
          console.error(e)
        }
      })
    }

    /***
     * rejectInvalidMessageLength :: limit the length of gift card messages to 200 characters
     */
    function rejectInvalidMessageLength () {
      var id = 'id="char_count_container"'
      var style = 'style="margin-left:10px;color:#2ecc71;font-weight:bold"'
      var limit = 200
      var curr = 0

      window.jQuery('#order_comments_field > label')
        .append('<span  ' + id + style + '>(<span id="char_count_val">' + limit + '</span> chars remaining)</span>')

      window.jQuery('#order_comments').keyup(function (evt) {
        curr = evt.target.value.length
        if (curr >= limit) {
          window.jQuery('#char_count_val').text(0)
          window.jQuery('#char_count_container').css('color', '#e74c3c')
          window.jQuery('#order_comments').css('border', '1px solid #e74c3c')
          window.jQuery('#order_comments').val(evt.target.value.slice(0, limit))
        } else {
          window.jQuery('#char_count_val').text(limit - curr)
          window.jQuery('#char_count_container').css('color', '#2ecc71')
          window.jQuery('#order_comments').css('border', '1px solid #e5e5e5')
        }
      })
    }

    function showCountdownTimer () {
      if (window.moment().hour() === 12) {
        var element = '<div style="width:100%; padding: 20px 0; background-color:#ff7675; ' +
          'position: fixed; bottom:0px; z-index:1000; color:white; text-align:center; ' +
          'font-weight: bold;">Order before 1 pm for same day delivery.</div>'

        window.jQuery('body').append(element)
      }
    }

    $(document).ready(function () {
      alterHTML()
      createAccountTrue()
      reduceCheckoutAbandonment()
      rejectInvalidDeliveryDate()
      rejectInvalidMessageLength()
      showCountdownTimer()
    })
  })
  </script>
  <?php

}


/** Add Javascript to Cart page */
add_action('woocommerce_after_cart', 'add_cart_page_js');
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


/* Convert Revenue Tracking */
add_action( 'woocommerce_thankyou', 'cf_conversion_tracking_thank_you_page' );
function cf_conversion_tracking_thank_you_page($order_id) {
	if(!isset($_COOKIE['cfconversioncounted'])){
		if ( $order_id > 0 ) {
			$order = wc_get_order( $order_id );
			if ( $order instanceof WC_Order ) {
				$order_total = $order->get_subtotal();
				$item_count = $order->get_item_count();
				?>
                <script type="text/javascript">
                    let subTotal = '<?php echo $order_total ?>';
					let prodCount = '<?php echo $item_count ?>';
					
					subTotal = subTotal.replace(/,/, '');
					
					window._conv_q = window._conv_q || [];
    				window._conv_q.push(["pushRevenue", subTotal, prodCount, 100312454]);
                </script>
				<?php
				setcookie('cfconversioncounted', 'true');
			}
		}
	}
}

if(!function_exists( 'wc_no_products_found')){ 
    function wc_no_products_found(){
        $class = '';
        if(class_exists('WC_Prdctfltr')){
            WC_Prdctfltr::$settings['did_noproducts'] = true;
            if(isset(WC_Prdctfltr::$settings['instance'])){
                $override = WC_Prdctfltr::$settings['instance']['wc_settings_prdctfltr_noproducts'];
            }
            else {
                $override = '';
            }
            $class = ( WC_Prdctfltr::$settings['wc_settings_prdctfltr_ajax_class'] == '' ? 'products' : WC_Prdctfltr::$settings['wc_settings_prdctfltr_ajax_class'] );
        }

        if($override == ''){
            echo '<div class="'.$class.' woocommerce-page prdctfltr-added-wrap">';
            echo '<p class="woocommerce-info">'.__('No products were found matching your selection.','woocommerce').'</p>';
            echo '</div>';
        } 
        else  
        {
            echo do_shortcode($override);
        } 
    } 
}

function woo_related_products_limit() {
    global $product;

    $args['posts_per_page'] = 6;
    return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args' );
function jk_related_products_args( $args ) {
    $args['posts_per_page'] = 6; // 4 related products
    $args['columns'] = 3; // arranged in 2 columns
    return $args;
}

add_action('woocommerce_payment_complete','wooexperts_map_order_customer',10,1);
function wooexperts_map_order_customer($order_id){
    $order = wc_get_order($order_id);
    $billing_email = $order->get_billing_email();
    if(filter_var($billing_email,FILTER_VALIDATE_EMAIL)){
        $user = get_user_by('email',$billing_email);
        if(isset($user->ID) && $user->ID){
            update_post_meta($order_id,'_customer_user',$user->ID);
        }
        else
        {
            $customer_id = wc_create_new_customer($billing_email,'','');
            update_post_meta($order_id,'_customer_user',$customer_id);
            $first_name = $order->get_billing_first_name();
            $last_name = $order->get_billing_last_name();
            update_user_meta($customer_id,'first_name',$first_name);
            update_user_meta($customer_id,'last_name',$last_name);
        }
    }
}

add_filter('woocommerce_checkout_registration_required','wooexperts_disable_registration_error',999,1);
function wooexperts_disable_registration_error($yes){
    return false;
}

add_action('wp_head','google_site_verification');
function google_site_verification(){
    echo "<meta name='google-site-verification' content='YYE5fGMzood9k6r3PUx-QvL74oLCMVNUSdQRXIwHAyY' />";
}

add_filter('woocommerce_checkout_fields', 'order_checkout_fields');
add_filter('woocommerce_default_address_fields', 'order_checkout_fields_default');

function order_checkout_fields($fields) {
    $fields['billing']['billing_first_name']['priority'] = 10;
    $fields['billing']['billing_last_name']['priority'] = 20;
    $fields['billing']['billing_email']['priority'] = 40;
    $fields['billing']['billing_phone']['priority'] = 50;

    $fields['shipping']['shipping_first_name']['priority'] = 10;
    $fields['shipping']['shipping_last_name']['priority'] = 20;
    $fields['shipping']['shipping_company']['priority'] = 30;
    $fields['shipping']['shipping_phone']['priority'] = 40;

    $fields['shipping']['shipping_address_1']['priority'] = 60;
    $fields['shipping']['shipping_address_2']['priority'] = 70;
    // $fields['shipping']['shipping_address_2']['required'] = TRUE;
    // $fields['shipping']['shipping_address_2']['label'] = "APARTMENT NUMBER, SUITE ETC";
    // $fields['shipping']['shipping_address_2']['placeholder'] = "APARTMENT NUMBER, SUITE ETC";
    $fields['shipping']['shipping_country']['priority'] = 80;
    $fields['shipping']['shipping_city']['priority'] = 90;
    $fields['shipping']['shipping_state']['priority'] = 100;
    $fields['shipping']['shipping_postcode']['priority'] = 110;
    $fields['shipping']['delivery_date']['priority'] = 120;
    $fields['additional']['delivery_date']['priority'] = 120;

    $fields['additional']['order_comments']['priority'] = 200;

    $fields['shipping']['shipping_company']['class'] = array('form-row-first');
    $fields['shipping']['shipping_phone']['class'] = array('form-row-last');

    unset($fields['billing']['billing_address_google']);
    unset($fields['shipping']['shipping_address_google']);
    return $fields;
}

function order_checkout_fields_default($fields) {
    $fields['shipping']['shipping_address_1']['priority'] = 50;
    $fields['shipping']['shipping_address_2']['priority'] = 60;
    $fields['shipping']['shipping_country']['priority'] = 70;
    $fields['shipping']['shipping_city']['priority'] = 80;
    $fields['shipping']['shipping_state']['priority'] = 90;
    $fields['shipping']['shipping_postcode']['priority'] = 100;

    $fields['shipping']['shipping_company']['class'] = array('form-row-first');
    $fields['shipping']['shipping_phone']['class'] = array('form-row-last');

    unset($fields['billing']['billing_address_google']);
    return $fields;
}


add_filter( 'default_checkout_shipping_country', 'change_default_shipping_country' );
function change_default_shipping_country() {
    return 'AU';
}

function add_javascript_to_footer() {
    ?>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.20.1/moment.min.js"></script>
    <?php
}
add_action('wp_footer', 'add_javascript_to_footer');

function add_javascript_to_head() {
    ?>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.20.1/moment.min.js"></script>
    <?php
}
//add_action('wp_head', 'add_javascript_to_head'); MIKE moved to footer

add_action('wp_enqueue_scripts','wooexperts_enqueue_scripts',999);
function wooexperts_enqueue_scripts(){
    global $wp_query;
    global $wp;



    $url = explode("\r\n",get_option('pages_url'));
    if(!empty($url)):
        $cururl = $wp->request;
        if(strpos($cururl,"/")):
            $cururl = explode("/", $cururl);
            $cururl = $cururl[0];
        endif;
        if(is_front_page() && $wp->request==""){
            $cururl = "home-page";
        }
        //if(in_array($cururl, $url) || $_COOKIE['wooexp_city_val']!=""):
        if(in_array($cururl, $url)):
            wp_enqueue_script('fancybox',get_stylesheet_directory_uri().'/js/jquery.fancybox.min.js',array('jquery'),NULL,true);
            // wp_enqueue_style('fancybox-style',get_stylesheet_directory_uri().'/css/jquery.fancybox.min.css'); MIKE moved to footer
            wp_enqueue_style('by-city-style',get_stylesheet_directory_uri().'/css/city.css?var='.time());
                    wp_enqueue_style('by-all-style',get_stylesheet_directory_uri().'/css/all.css');
                    wp_register_script('by-city',get_stylesheet_directory_uri().'/js/city.js',array('jquery'),time(),true);
            $args = array(
                'site'=>site_url(),
                'is_city_page'=> is_tax() && is_tax('pa_city') ? 'yes' : 'no',
                'is_tax_page'=> is_tax() && !is_tax('pa_city') ? 'yes' : 'no',
                'is_front_page'=> is_front_page() ? 'yes' : 'yes',
                'is_product_page'=> is_page() && isset($wp_query->post->ID) ? (int)get_post_meta($wp_query->post->ID,'_woo_enable_redirect',true) : '0',
                'is_popup_page'=> is_page(array('choose-city')) ? 'yes' : 'no',
            );
            wp_localize_script('by-city','wooexperts',$args);
            wp_enqueue_script('by-city' );
        else:
            wp_enqueue_style('by-city-style',get_stylesheet_directory_uri().'/css/city.css?var='.time());
        endif;
    endif;
}

add_filter('woocommerce_product_query_tax_query','wooexperts_product_tax_query',9999999,2);
function wooexperts_product_tax_query($q,$obj){
    $city = get_selected_city();
    $shortcode_city = get_city_attr_shortcode();
    $city = $city=='' && $shortcode_city!='' ? $shortcode_city : $city;

    if($city!=''){
        $q['relation']='AND';
        $q[] = array(
            'taxonomy' => 'pa_city',
            'field'    => 'slug',
            'terms'    => array($city),
            'operator' => 'IN'
        );
    }
    
    return $q;
}


add_filter('woocommerce_product_query_meta_query','wooexperts_product_meta_query',9999999,2);
function wooexperts_product_meta_query($q,$obj){
    $q[] = array(
        'key'     => '_stock_status',
        'value'   => 'outofstock',
        'compare'   => 'NOT IN'
    );
    return $q;
}

add_action('woocommerce_product_query','product_query_wooexperts',9999999,2);
function product_query_wooexperts($q,$obj){

    $city = get_selected_city();
    $shortcode_city = get_city_attr_shortcode();
    
    $city = $city=='' && $shortcode_city!='' ? $shortcode_city : $city;

    


    if($city!=''){
        $post_not_in = get_out_of_stock_by_city();
        $include_products =  array();
        if(isset($_COOKIE['wooexp_date']) && isset($_COOKIE['wooexp_city']))
        {
            $excluded_slot=get_option('excluded_slot');
            if(!empty($excluded_slot))
            {
                $current=strtotime($_COOKIE['wooexp_date']);
                foreach ($excluded_slot as $citydata) 
                {   
                    $date = strtotime($citydata['date']);
                    if($citydata['location']==$_COOKIE['wooexp_city'] && $current==$date)
                    {   
                        $proIds=explode(',', $citydata['slot']);
                        if($citydata['type']=="exclude"){
                            if(!empty($proIds)):
                                foreach($proIds as $prodid):
                                    $post_not_in[] = $prodid;
                                endforeach;
                            endif;
                        }else{
                            if(!empty($proIds)):
                                foreach($proIds as $prodid):
                                    $include_products[] = $prodid;
                                endforeach;
                            endif; 
                        }
                    }
                }
            }
        }

        if(empty($include_products)):
            $q->set('post__not_in',$post_not_in,true);
        else:
            $q->set('post__in',$include_products,true);
        endif;
    }
}


add_action('admin_head-nav-menus.php','add_city_menu');
function add_city_menu(){
    $param = array( 0 => 'This param will be passed to my_render_menu_metabox' );
    add_meta_box('wooexp-city-box',__('City Dropdown','wooexp-city'),'wooexp_city_box_init','nav-menus','side','default', $param );
}

function wooexp_city_box_init(){
    include(dirname(__FILE__).'/city-menu.php');
}

add_filter('nav_menu_link_attributes','city_nav_menu_link_attributes',10,2);
function city_nav_menu_link_attributes($atts,$item){
    if(isset($item->data_city)){
        $atts['data-city'] = esc_attr($item->data_city);
    }
    return $atts;
}

add_filter('wp_get_nav_menu_items','city_switcher_menu');
function city_switcher_menu($items){
    if(doing_action('customize_register') || is_admin()){
        return $items;
    }
    usort($items,'usort_city_menu_items');

    $cities = array('melbourne'=>'Melbourne','sydney'=>'Sydney');
    $new_items = array();
    $offset = 0;
    $i = 0;

    foreach($items as $item){
        if('wooexp_city' == $item->type){
            $item->title = get_current_city();
            $item->attr_title = '';
            $item->url = '';
            $item->parent = true;
            $item->data_city = get_selected_city();
            $item->classes = array('wooexp-city-item wooexp-city-parent sticky_city_menu');
            $new_items[] = $item;
            $offset++;
            if(is_array($cities) && !empty($cities)){
                foreach($cities as $city_slug=>$city_name){
                    if(get_selected_city()!=$city_slug){
                        $new_item = clone $item;
                        $new_item->ID = $new_item->ID . '-'.$city_slug;
                        $new_item->title = $city_name;
                        $new_item->attr_title = '';
                        $new_item->data_city = $city_slug;
                        $item->parent = false;
                        $new_item->url = '';
                        $new_item->classes = array('wooexp-city-item wooexp-city-child ');
                        $new_item->menu_item_parent = $item->db_id;
                        $new_item->db_id = 0;
                        $new_item->menu_order += $offset + $i++;
                        $new_items[] = $new_item;
                        $offset += $i - 1;
                    }
                }
            }
        }
        else
        {
            $item->menu_order += $offset;
            $new_items[] = $item;
        }
    }
    return $new_items;
}

function usort_city_menu_items( $a, $b ) {
    return ($a->menu_order < $b->menu_order) ? -1 : 1;
}

function get_selected_city_cookies(){
    return isset($_COOKIE['wooexp_city']) && $_COOKIE['wooexp_city']!='' ? $_COOKIE['wooexp_city'] : '';
}

function get_selected_city(){
    $city = get_selected_city_cookies();
    $term='';
    if(is_tax('pa_city') && $city==''){
        $term = get_query_var('term');
    }
    return isset($_COOKIE['wooexp_city']) && $_COOKIE['wooexp_city']!='' ? $_COOKIE['wooexp_city'] : $term;
}

function get_current_city(){
    if(isset($_COOKIE['wooexp_city']) && $_COOKIE['wooexp_city']!=''){
        return $_COOKIE['wooexp_city'];
    }
    else
    {
        return 'Select City';
    }
}

function wooexperts_current_page_url(){
    global $wp;
    global $pagenow;
    return add_query_arg($_SERVER['QUERY_STRING'],'',home_url($wp->request));
}

function wooexperts_get_page_by_slug($page_slug,$post_type='page'){
    global $wpdb;
    $page_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM ".$wpdb->posts." WHERE post_name = %s AND post_type= %s",$page_slug,$post_type));
    return $page_id;
}

add_action('template_redirect','fig_bloom_city_template_redirect');
function fig_bloom_city_template_redirect(){
    global $wp_query;
    $redirect = isset($wp_query->post->ID) ? (int)get_post_meta($wp_query->post->ID,'_woo_enable_redirect',true) : false;
    $city = get_selected_city();
    $shortcode_city = get_city_attr_shortcode();
    $city = $city=='' && $shortcode_city!='' ? $shortcode_city : $city;
    if((is_front_page() || is_page(array('melbourne','sydney','choose-city'))) && $city!='' && !isset($_REQUEST['pa_city'])){
        // $url = add_query_arg('pa_city',$city,get_permalink(wooexperts_get_page_by_slug($city)));
        // wp_redirect($url);
        // exit;
    }
    elseif(is_page(array('melbourne','sydney','choose-city')) && $city!='' && isset($_REQUEST['pa_city'])){
        // if(is_page('melbourne') && $_REQUEST['pa_city']=='sydney'){
        //     $url = add_query_arg('pa_city',$city,get_permalink(wooexperts_get_page_by_slug('sydney')));
        //     wp_redirect($url);
        //     exit;
        // }
        // elseif(is_page('sydney') && $_REQUEST['pa_city']=='melbourne'){
        //     $url = add_query_arg('pa_city',$city,get_permalink(wooexperts_get_page_by_slug('melbourne')));
        //     wp_redirect($url);
        //     exit;
        // }
        // elseif(is_page('choose-city')){
        //     $url = add_query_arg('pa_city',$city,get_permalink(wooexperts_get_page_by_slug($city)));
        //     wp_redirect($url);
        //     exit;
        // }
    }
    elseif((!is_tax( 'pa_city' ) && is_tax()) || $redirect || is_shop()){
        // if(!is_cart() && !is_checkout() && !isset($_REQUEST['pa_city'])){
        //     if($city!=''){
        //         $curl = wooexperts_current_page_url();
        //         $url = add_query_arg('pa_city',$city,$curl);
        //         wp_redirect($url);
        //         exit;
        //     }
        // }
        // elseif(isset($_REQUEST['pa_city'])){
        //     $url_city = $_REQUEST['pa_city'];
        //     if($city!='' && $url_city!=$city){
        //         $curl = wooexperts_current_page_url();
        //         $url = add_query_arg('pa_city',$city,$curl);
        //         wp_redirect($url);
        //         exit;
        //     }
        // }
    }
    elseif(is_product() && $city!='' && !isset($_REQUEST['attribute_pa_city'])){
        // $curl = wooexperts_current_page_url();
        // $url = add_query_arg('attribute_pa_city',$city,$curl);
        // wp_redirect($url);
        // exit;
    }
}

add_action('pre_get_posts','check_custom_query_param',999);
function check_custom_query_param($query){
    $shortcode_city = get_city_attr_shortcode();
    
    if(isset($query->query['post_type']) && $query->query['post_type']=='product'){
        $city = get_selected_city();
        if(class_exists('WC_Prdctfltr') && $city!=''){
            WC_Prdctfltr::make_global(array('pa_city'=>$city,'instock_products'=>'in'),'FALSE');
        }
        elseif(class_exists('WC_Prdctfltr') && $shortcode_city!=''){
            WC_Prdctfltr::make_global(array('pa_city'=>$shortcode_city,'instock_products'=>'in'),'FALSE');
        }
    }
}

function wooexperts_normalize_attributes($atts){
    if(is_array($atts) && !empty($atts)){
        foreach($atts as $key => $value){
            if(is_int($key)){
                $value = strtr($value,array('['=>'',']'=>''));
                $atts[$value] = '';
                unset($atts[$key]);
            }
        }
    }
    return $atts;
}

function get_city_attr_shortcode(){

    global $post;
    $city = ''; 
    if(isset($post->ID) && get_post_type($post->ID)=='page'){
        if($city==''){
            if(preg_match_all( '/\[(\[?)(prdctfltr_sc_products)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/s ',$post->post_content,$matches)){
                if(is_array($matches) && !empty($matches)){
                    foreach($matches as $m){
                        if(isset($m[0]) && is_string($m[0]) &&strpos($m[0],'[prdctfltr_sc_products') !== false){
                            $atts = wooexperts_normalize_attributes(shortcode_parse_atts($m[0]));
                            if(isset($atts['pa_city'])){
                                $city = trim($atts['pa_city']);
                                if($city!=''){
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $city;
}


add_filter( 'pre_get_posts','wooexperts_sc_wc_query',9999999,1);
function wooexperts_sc_wc_query($query){
    global $wpdb;
    $shortcode_city = get_city_attr_shortcode();
    
    if(isset($query->query['post_type']) && $query->query['post_type']=='product' && isset($query->is_singular) && !$query->is_singular){
        $city = get_selected_city();
        $city = $city=='' && $shortcode_city!='' ? $shortcode_city : $city;
        if($city!='' && class_exists('WC_Prdctfltr')){
            $outofstock = get_term_by( 'slug', 'outofstock', 'product_visibility' );
            $variableStockOut = $wpdb->get_results(sprintf('
                                SELECT DISTINCT(%1$s.post_parent) as ID FROM %1$s
                                INNER JOIN %2$s AS pf1 ON (%1$s.ID = pf1.post_id)
                                INNER JOIN %3$s ON (%1$s.ID = %3$s.object_id)
                                WHERE %1$s.post_type = "product_variation"
                                AND pf1.meta_key IN ("attribute_pa_city") AND pf1.meta_value IN ("'.$city.'")
                                AND ( %1$s.ID IN ( SELECT object_id FROM %3$s WHERE term_taxonomy_id IN ( ' . $outofstock->term_id . ' ) ) )
                                AND ( %1$s.ID IN ( SELECT post_id FROM %2$s WHERE meta_key="attribute_pa_city" AND meta_value="'.$city.'"  GROUP BY post_id HAVING COUNT( DISTINCT meta_key ) = 1 ) )
                                AND ( %1$s.post_parent NOT IN (
                                SELECT DISTINCT(p.post_parent) FROM '.$wpdb->posts.' as p,'.$wpdb->postmeta.' as m,'.$wpdb->postmeta.' as m2 WHERE p.post_type="product_variation" 
                                AND m.meta_key="_stock_status" AND m.meta_value="instock"
                                AND m2.meta_key="attribute_pa_city" AND m2.meta_value="'.$city.'"
                                AND m.post_id = m2.post_id
                                AND p.ID = m.post_id
                                ))
                                GROUP BY pf1.post_id
                                HAVING COUNT(DISTINCT pf1.meta_key) = 1
                                LIMIT 29999
                            ', $wpdb->posts, $wpdb->postmeta, $wpdb->term_relationships ));
            if(is_array($variableStockOut) && !empty($variableStockOut)){
                $out_of_stock = array();
                foreach($variableStockOut as $var_id){
                    $out_of_stock[]=$var_id->ID;
                }
                

                $excluded_slot=get_option('excluded_slot');

                if(!empty($excluded_slot))
                {
                    $current=strtotime($_COOKIE['wooexp_date']);
                
                    foreach ($excluded_slot as $citydata) 
                    {   
                        $date    = strtotime($citydata['date']);
                        
                        if($citydata['location']==$city && $current==$date)
                        {   
                            $proIds=explode(',', $citydata['slot']);

                            $out_of_stock = array_merge($out_of_stock, $proIds);
                          
                        }
                    }
    
                }
                
                if(!empty($out_of_stock))
                {
                    $query->query_vars['post__not_in'] = $out_of_stock;
                }
            }
        }
    }
    return $query;
}


add_action('template_redirect', 'product_view_disable_wwt');
function product_view_disable_wwt() {
    global $post;

    $page_object = get_queried_object();
    $page_id = get_queried_object_id();
    
     
    if(isset($_COOKIE['wooexp_date']) && isset($_COOKIE['wooexp_city']))
    {
        $excluded_slot=get_option('excluded_slot');

        if(!empty($excluded_slot))
        {
            $current=strtotime($_COOKIE['wooexp_date']);
        
            foreach ($excluded_slot as $citydata) 
            {   
                $date = strtotime($citydata['date']);
                
                if($citydata['location']==$_COOKIE['wooexp_city'] && $current==$date)
                {   
                    $proIds=explode(',', $citydata['slot']);
                    if($citydata['type']=="include"){
                        if(!in_array($page_id, $proIds) && is_product())
                        {
                             wp_redirect(home_url('/404'));
                             exit;
                        }
                    }else{
                        if(in_array($page_id, $proIds) && is_product())
                        {
                             wp_redirect(home_url('/404'));
                             exit;
                        }
                    }
                }
            }

        }
    }
}

 add_filter( 'woocommerce_checkout_fields' , 'default_values_checkout_fields');
  function default_values_checkout_fields( $fields ) 
  {
     
    

$city='';
    if(isset($_COOKIE['wooexp_city']))
    {   
        $city='';
        if($_COOKIE['wooexp_city']=='sydney')
        {
            $city='NSW';
        }
        else if($_COOKIE['wooexp_city']=='melbourne')
        {
            $city='VIC';
        }
        $fields['shipping']['shipping_state']['default'] = $city;    
    }

    // $getpin=GetZipCode($_COOKIE['wooexp_city_val']);
    // if(!empty($getpin))
    // { 
    //     $fields['shipping']['shipping_city']['default']=$getpin['suburb'];
    //     $fields['shipping']['shipping_postcode']['default']=$getpin['postcode'];
    // }

    if($_COOKIE['wooexp_city_newsf']!="")
    { 
    	$state = explode("-", $_COOKIE['wooexp_city_newsf']);
        $fields['shipping']['shipping_city']['default']=$state[0];
        $fields['shipping']['shipping_postcode']['default']=$_COOKIE['postcode'];
    }


    if(isset($_COOKIE['wooexp_city_val']))
    {     
        $address=str_replace($city.', Australia','',$_COOKIE['wooexp_city_val']);
        $address=str_replace($getpin['suburb'].' ','',$address);
        
        //$fields['shipping']['shipping_address_1']['default'] = $address;
        $fields['shipping']['shipping_address_1']['default'] = ""; 
    } 
  
    $fields['shipping']['shipping_state']['custom_attributes']       = array( 'readonly' => true );
    $fields['shipping']['shipping_postcode']['custom_attributes']       = array( 'readonly' => true );
    $fields['shipping']['shipping_city']['custom_attributes']       = array( 'readonly' => true );
    $fields['shipping']['shipping_city']['description']       = "<p></p>";

    return $fields;
  }
   
function GetZipCode($address)
{   
    $res=array();
    if(!empty($address))
    {      
        $Address= str_replace(' ','+',$address);    
        $getGeocodeAddr = wp_remote_request('https://maps.googleapis.com/maps/api/geocode/json?address='.$Address.'&sensor=true_or_false&key=AIzaSyD6DQmPCuinTl1eiCNBjfQIw-9m9GQEWZI'); 
        $getGeocodeAddr=$getGeocodeAddr['body'];
        $outputData = json_decode($getGeocodeAddr);  
  
        $lat  = $outputData->results[0]->geometry->location->lat; 
        $long = $outputData->results[0]->geometry->location->lng; 

        $getZip = wp_remote_request('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&sensor=true_or_false&key=AIzaSyD6DQmPCuinTl1eiCNBjfQIw-9m9GQEWZI');
        $getZip=$getZip['body'];
        $outputZip = json_decode($getZip);

       if(!empty($outputZip))
       { 
            $addressData = $outputZip->results[0]->address_components;
            foreach($addressData as $addr)
            {
                if($addr->types[0] == 'postal_code')
                {  
                    $res['postcode']=$addr->long_name; 
                }  
                if($addr->types[0] == 'locality')
                {  
                    $res['suburb']=$addr->long_name; 
                }          
            }      
            
        }
    }
    return $res; 
}


  function footer_script_wwt()
  { 
    if(isset($_COOKIE['wooexp_date']) && is_page('checkout'))
    {   
        $city='';$address='';
        if(isset($_COOKIE['wooexp_city']))
        {   
            if($_COOKIE['wooexp_city']=='sydney')
            {
                $city='NSW';
            }
            else if($_COOKIE['wooexp_city']=='melbourne')
            {
                $city='VIC';
            }   
        }
        $getpin=GetZipCode($_COOKIE['wooexp_city_val']);
        $suburb='';
        $postcode='';
        if(!empty($getpin))
        { 
            $suburb=$getpin['suburb'];
            $postcode=$getpin['postcode'];
        }

        $suburb=$_COOKIE['wooexp_city'];
        $postcode = $_COOKIE['postcode'];
        if(isset($_COOKIE['wooexp_city_val']))
        {     
            $address=str_replace($city.', Australia','',$_COOKIE['wooexp_city_val']);
            $address=str_replace($getpin['suburb'].' ','',$address);
        }
        ?> 
        <script> 
            jQuery(document).ready(function()
            {    
                var datebirth='<?php echo date('Y-m-d',strtotime($_COOKIE['wooexp_date'])); ?>'; 
                
                jQuery('#shipping_state').val('<?php echo $city; ?>');
                jQuery('#shipping_city').val('<?php echo $suburb; ?>');
                jQuery('#shipping_postcode').val('<?php echo $postcode; ?>');
                setTimeout(function()
                {
                    jQuery("#delivery_date").datepicker("setDate",new Date(datebirth)); 
                    jQuery( "#delivery_date" ).attr('style','pointer-events:none');   
                    jQuery( "#shipping_state" ).next('span').attr('style','pointer-events:none;width: 100%;');
                    jQuery( "#shipping_country" ).next('span').attr('style','pointer-events:none;width: 100%;');

                },5000);
                
            });

        </script>
        <?php
    }
    
  } 
  add_action('wp_footer','footer_script_wwt');


add_action('wp_enqueue_scripts','google_maps_script_loader',999);
function google_maps_script_loader() {
    wp_dequeue_script( 'gmapdep' );    
}

function get_out_of_stock_by_city(){
    global $wpdb;
    
    $out_of_stock = array();
    $city = get_selected_city();
    $shortcode_city = get_city_attr_shortcode();
    $city = $city=='' && $shortcode_city!='' ? $shortcode_city : $city;
    if($city!=''){
        $outofstock = get_term_by( 'slug', 'outofstock', 'product_visibility' );
        $variableStockOut = $wpdb->get_results(sprintf('
                                SELECT DISTINCT(%1$s.post_parent) as ID FROM %1$s
                                INNER JOIN %2$s AS pf1 ON (%1$s.ID = pf1.post_id)
                                INNER JOIN %3$s ON (%1$s.ID = %3$s.object_id)
                                WHERE %1$s.post_type = "product_variation"
                                AND pf1.meta_key IN ("attribute_pa_city") AND pf1.meta_value IN ("'.$city.'")
                                AND ( %1$s.ID IN ( SELECT object_id FROM %3$s WHERE term_taxonomy_id IN ( ' . $outofstock->term_id . ' ) ) )
                                AND ( %1$s.ID IN ( SELECT post_id FROM %2$s WHERE meta_key="attribute_pa_city" AND meta_value="'.$city.'"  GROUP BY post_id HAVING COUNT( DISTINCT meta_key ) = 1 ) )
                                AND ( %1$s.post_parent NOT IN (
                                SELECT DISTINCT(p.post_parent) FROM '.$wpdb->posts.' as p,'.$wpdb->postmeta.' as m,'.$wpdb->postmeta.' as m2 WHERE p.post_type="product_variation" 
                                AND m.meta_key="_stock_status" AND m.meta_value="instock"
                                AND m2.meta_key="attribute_pa_city" AND m2.meta_value="'.$city.'"
                                AND m.post_id = m2.post_id
                                AND p.ID = m.post_id
                                ))
                                GROUP BY pf1.post_id
                                HAVING COUNT(DISTINCT pf1.meta_key) = 1
                                LIMIT 29999
                            ', $wpdb->posts, $wpdb->postmeta, $wpdb->term_relationships ));
        if(is_array($variableStockOut) && !empty($variableStockOut)){
            foreach($variableStockOut as $var_id){
                $out_of_stock[]=$var_id->ID;
            }
        }
    }
    return $out_of_stock;
}

add_filter( 'woocommerce_variation_is_active', 'wcbv_variation_is_active', 10, 2 );
function wcbv_variation_is_active( $active, $variation ) {
    if( ! $variation->is_in_stock() ) {
        return false;
    }
    return $active;
}

add_action('woocommerce_before_add_to_cart_form','woocommerce_sold_out_dropdown');
function woocommerce_sold_out_dropdown() {
    ?>
    <script type="text/javascript">
        jQuery( document ).bind( 'woocommerce_update_variation_values', function() {
            jQuery( '.variations select option' ).each( function( index, el ) {
                var sold_out = '<?php _e( 'Out of Stock', 'woocommerce' ); ?>';
                var re = new RegExp( ' - ' + sold_out + '$' );
                el = jQuery( el );

                if ( el.is( ':disabled' ) ) {
                    if ( ! el.html().match( re ) ) el.html( el.html() + ' - ' + sold_out );
                } else {
                    if ( el.html().match( re ) ) el.html( el.html().replace( re,'' ) );
                }
            } );
        } );
    </script>
    <?php
}

function wooexperts_get_available_variations(){
    global $product;

    $available_variations = array();
    foreach($product->get_children() as $child_id){
        $variation = wc_get_product( $child_id );
        if(!$variation || ! $variation->exists()){
            continue;
        }
        if(apply_filters('woocommerce_hide_invisible_variations',true,$product->get_id(),$variation) && ! $variation->variation_is_visible()){
            continue;
        }

        $available_variations[] = $product->get_available_variation( $variation );
    }
    $available_variations = array_values( array_filter( $available_variations ) );

    return $available_variations;
}

if(!function_exists( 'woocommerce_variable_add_to_cart')){
    function woocommerce_variable_add_to_cart() {
        global $product;
        wp_enqueue_script( 'wc-add-to-cart-variation' );
        $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
        wc_get_template( 'single-product/add-to-cart/variable.php', array(
            'available_variations' => $get_variations ? wooexperts_get_available_variations() : false,
            'attributes'           => $product->get_variation_attributes(),
            'selected_attributes'  => $product->get_default_attributes(),
        ) );
    }
}

add_filter( 'woocommerce_get_availability', 'wcs_custom_get_availability', 1, 2);
function wcs_custom_get_availability( $availability, $_product ) {
    if(!$_product->is_in_stock()){
        $availability['availability'] = __('This product is currently of stock in selected city.', 'woocommerce');
    }
    return $availability;
}


class thb_mobileDropdown_2 extends Walker_Nav_Menu {

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;


        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        $class_names = $class_names ? ' class=" ' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        if($item->db_id && isset($item->data_city)){
            $item_output .= '<a'. $attributes .'>'.'<span></span>';
        }
        else
        {
            $item_output .= '<a'. $attributes .'>'. (isset($item->data_city) ? '' : '');
        }


        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;


        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

if(!class_exists('WooExperts_Meta_Box')){
    class WooExperts_Meta_Box {

        /**
         * Constructor.
         */
        public function __construct() {
            if ( is_admin() ) {
                add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
                add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
            }

        }

        /**
         * Meta box initialization.
         */
        public function init_metabox() {
            add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
            add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
        }

        /**
         * Adds the meta box.
         */
        public function add_metabox() {
            add_meta_box(
                'woo-enable-redirect-meta-box',
                __( 'Is Product page ?', 'textdomain' ),
                array( $this, 'render_metabox' ),
                'page',
                'side',
                'high'
            );

        }

        /**
         * Renders the meta box.
         */
        public function render_metabox($post){
            wp_nonce_field( 'woo_enable_redirect_action', 'woo_enable_redirect_nonce' );
            $redirect = (int)get_post_meta($post->ID,'_woo_enable_redirect',true);
            $checked = $redirect ? 'checked' : '';
            echo '<div class="woo-enable-redirect">';
            echo '<input type="checkbox" name="woo-enable-redirect" value="1" '.$checked.'><label>Check if Product page ?</label>';
            echo '</div>';
        }

        /**
         * Handles saving the meta box.
         *
         * @param int     $post_id Post ID.
         * @param WP_Post $post    Post object.
         * @return null
         */
        public function save_metabox( $post_id, $post ) {
            // Add nonce for security and authentication.
            $nonce_name   = isset( $_POST['woo_enable_redirect_nonce'] ) ? $_POST['woo_enable_redirect_nonce'] : '';
            $nonce_action = 'woo_enable_redirect_action';

            // Check if nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
                return;
            }

            // Check if user has permissions to save data.
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }

            // Check if not an autosave.
            if ( wp_is_post_autosave( $post_id ) ) {
                return;
            }

            // Check if not a revision.
            if ( wp_is_post_revision( $post_id ) ) {
                return;
            }

            if(isset($_POST['woo-enable-redirect']) && $_POST['woo-enable-redirect']){
                update_post_meta($post_id,'_woo_enable_redirect',1);
            }
            else
            {
                delete_post_meta($post_id,'_woo_enable_redirect');
            }
        }
    }
}
new WooExperts_Meta_Box();

// CLEAN UP SCRIPTS TO ONLY DISPLAY WHERE NEEDED
function reassign_jQuery() {
     wp_deregister_script( 'jquery' );
     wp_deregister_script( 'jquery-core' ); // do not forget this
     wp_deregister_script( 'jquery-migrate' ); // do not forget this

     wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', array(), '1.12.4', TRUE );
     wp_enqueue_script('jquery'); 
} 

// Now you can load all of your jQuery dependent scripts, probably you'll prefer to add a priority actions checking 
/* WON'T WORK IN CHILD THEMEif ( !is_admin() ) {
    add_action('init', 'reassign_jQuery'); 
}*/

function child_manage_woocommerce_styles() {
    
    wp_register_script('global',get_stylesheet_directory_uri().'/js/global.js',array('jquery'),time(),true);
    wp_enqueue_script('global');
    
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

    wp_enqueue_script( 'ajax_custom_script',  get_stylesheet_directory_uri() . '/js/ajax-me.js', array('jquery'),time(),true);
    wp_localize_script( 'ajax_custom_script', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

    /* WON'T WORK IN CHILD THEME
    if ( !is_front_page () ) {
        wp_dequeue_style( 'SB_INSTAGRAM_STYLES' );
    }
    if ( is_front_page () ) {
        wp_dequeue_style( 'WC-BLOCK-STYLE' );
    }
    if ( function_exists( 'is_woocommerce' ) ) {
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            //wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            //wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    } */

}
add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );

// PUT SOME NON-ESSENTIAL STYLES IN THE FOOTER
function prefix_add_footer_styles() {
    wp_enqueue_style('fancybox-style',get_stylesheet_directory_uri().'/css/jquery.fancybox.min.css');
};
add_action( 'get_footer', 'prefix_add_footer_styles' );

// REMOVE EMOJI AND EMBED SCRIPTS
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// SEARCH FORM
function thb_add_searchform_duplicate() { ?>
        <aside id="searchpopup" class="mfp-hide" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_autocomplete_ajax' ) ); ?>">
            <div class="thb-close-text"><?php esc_html_e('PRESS ESC TO CLOSE', 'north' ); ?></div>
            <div class="row align-center">
                <div class="small-12 medium-8 columns">
                    <?php
                        if(thb_wc_supported()) {
                            if ( !defined( 'YITH_WCAS' ) ) {
                                get_product_search_form();
                            } else {
                                echo do_shortcode('[yith_woocommerce_ajax_search]' );
                            }
                        } else {
                            get_search_form();
                        }
                    ?>
                    <div class="thb-autocomplete-wrapper"></div>
                </div>
            </div>
        </aside>
    <?php
}
add_action( 'wp_footer', 'thb_add_searchform_duplicate', 100 );
add_action( 'admin_menu', 'register_delivery_slot_menu_page' );
function register_delivery_slot_menu_page() {
  add_menu_page( 'Delivery Spots settings', 'Delivery Spots Page', 'manage_options', 'delivery_slot.php', 'delivery_slot', 'dashicons-clipboard', 90 );
}
function delivery_slot(){
    if($_POST['melborne_submit']){
        update_option('melborne_slot',$_POST['melborne_slot']);
        $name='Melborne spots';
    }
    if($_POST['sydney_submit']){
        update_option('sydney_slot',$_POST['sydney_slot']);
        $name='Sydney spots';
    }
    if($_POST['special_submit']){
        update_option('special_slot',array_values(array_filter($_POST['sp'])));
        $name='Special spots';
    }
    if($_POST['excluded_submit']){
        update_option('excluded_slot',array_values(array_filter($_POST['exc'])));
        $name='Excluded Products';
    }

    if($_POST['pages_url_sub']){
        
        update_option('pages_url',$_POST['pages_url']);
        $name='Pages Url';
    }
    if (isset($name)){ ?>
    <div class="notice notice-success is-dismissible"> 
        <p><?php echo $name.' has been successfully updated.'; ?>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div><?php }
?>

    <div class="wrap delivery_slot_section">
        <h1>Delivery Spots Settings</h1>
        <h2>Melbourne</h2>
        <form action="?page=delivery_slot.php" method="post" id="melbourne">
            <div class="scroll-table">
                <table class="form-table">
                    <tr><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>
                    <?php $melborne_slots=get_option('melborne_slot');
                    if($melborne_slots){ ?>
                        <tr>
                            <?php foreach($melborne_slots as $melborne_slot){ ?>
                                <td><input type="number" name="melborne_slot[]" min="0" value="<?php echo $melborne_slot; ?>"></td>
                            <?php } ?>
                        </tr>
                    <?php } else { ?>    
                        <tr><td><input type="number"  min="0" name="melborne_slot[]"></td><td><input type="number" min="0" name="melborne_slot[]"></td><td><input type="number" min="0" name="melborne_slot[]"></td><td><input type="number" min="0" name="melborne_slot[]"></td><td><input type="number" min="0" name="melborne_slot[]"></td><td><input type="number" min="0" name="melborne_slot[]"></td><td><input type="number" min="0" name="melborne_slot[]"></td></tr>  
                    <?php } ?>                  
                </table>
            </div>
            <p class="submit"><input type="submit" name="melborne_submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form action="?page=delivery_slot.php">
        <h2>Sydney</h2>
        <form action="?page=delivery_slot.php" method="post" id="sydney">
            <div class="scroll-table">
                <table class="form-table">
                    <tr><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th><th>Saturday</th><th>Sunday</th></tr>
                    <?php $sydney_slots=get_option('sydney_slot');
                    if($sydney_slots){ ?>
                        <tr>
                            <?php foreach($sydney_slots as $sydney_slot){ ?>
                                <td><input type="number" min="0" name="sydney_slot[]" value="<?php echo $sydney_slot; ?>"></td>
                            <?php } ?>
                        </tr>
                    <?php } else { ?>   
                        <tr><td><input type="number" min="0"name="sydney_slot[]"></td><td><input type="number" min="0" name="sydney_slot[]"></td><td><input type="number" min="0" name="sydney_slot[]"></td><td><input type="number" min="0" name="sydney_slot[]"></td><td><input type="number" min="0" name="sydney_slot[]"></td><td><input type="number" min="0" name="sydney_slot[]"></td><td><input type="number" min="0" name="sydney_slot[]"></td></tr>
                    <?php } ?>   
                </table>
            </div>
            <p class="submit"><input type="submit" name="sydney_submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>
        <h2>Special dates</h2>
        <form action="?page=delivery_slot.php" method="post" id="special">
            <div class="scroll-table">
                <table class="form-table special_table">
                    <tr><th>Location</th><th>Date</th><th>Max order count</th><th></th></tr>
                    <?php $special_slots=get_option('special_slot');
                    if($special_slots){ $special_slots_index=array_values(array_filter($special_slots));?>
                        <?php foreach($special_slots_index as $key=>$special_slot){ ?>
                            <tr class="special_slot_row" data-key="<?php echo $key ?>">
                                <td><select name="sp[<?php echo $key ?>][locaton]"><option <?php if($special_slot['locaton']=='melbourne'){echo 'selected';}?> value="melbourne">Melbourne</option><option <?php if($special_slot['locaton']=='sydney'){echo 'selected';}?> value="sydney">Sydney</option></select></td>
                                <td><input required type="date" name="sp[<?php echo $key ?>][date]" min="<?php echo date("Y-m-d", strtotime("now")); ?>" value="<?php echo $special_slot['date'] ?>" ></td>
                               <td><input required min="0" name="sp[<?php echo $key ?>][slot]" type="number"  value="<?php echo $special_slot['slot'] ?>"></td><td><p class="remove_row">-</p><p class="add_row">+</p></td>
                            </tr>
                        <?php } ?>
                         
                    <?php } else { ?>   
                        <tr class="special_slot_row" data-key="0"><td><select name="sp[0][locaton]"><option value="melbourne">Melbourne</option><option value="sydney">Sydney</option></select></td>
                    <td><input required type="date" name="sp[0][date]" min="<?php echo date("Y-m-d", strtotime("+1days")); ?>"></td><td><input name="sp[0][slot]" required min="0" type="number"></td><td><p class="remove_row">-</p><p class="add_row">+</p></td></tr>
                    <?php } ?>  
                </table>
            </div>
            <p class="submit"><input type="submit" name="special_submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>
        <h2>Exclude/Include Products</h2>
        <form action="?page=delivery_slot.php" method="post" id="excluded">
            <div class="scroll-table">
                <table class="form-table excluded_table">
                    <tr><th>Location</th><th>Date</th><th>Type</th><th>Product Ids (with comma(,) separated)</th><th></th></tr>
                    <?php $excluded_slots=get_option('excluded_slot'); 
                    if($excluded_slots){ $excluded_slot_index=array_values(array_filter($excluded_slots));?>
                        <?php foreach($excluded_slot_index as $key=>$excluded_slot){ ?>
                            <tr class="excluded_slot_row" data-key="<?php echo $key ?>">
                                <td><select name="exc[<?php echo $key ?>][location]"><option <?php if($excluded_slot['location']=='melbourne'){echo 'selected';}?> value="melbourne">Melbourne</option><option <?php if($excluded_slot['location']=='sydney'){echo 'selected';}?> value="sydney">Sydney</option></select></td>
                                <td><input required type="date" name="exc[<?php echo $key ?>][date]" min="<?php echo date("Y-m-d", strtotime("now")); ?>" value="<?php echo $excluded_slot['date'] ?>" ></td>
                                <td>
                                <select name="exc[<?php echo $key ?>][type]">
                                <option value="exclude" <?php echo ($excluded_slot['type']=="exclude" ? "selected='selected'" : "")?>>Exclude</option>
                                <option value="include" <?php echo ($excluded_slot['type']=="include" ? "selected='selected'" : "")?>>Include</option>
                                </select>
                                </td>
                               <td><input required min="0" name="exc[<?php echo $key ?>][slot]" type="text"  value="<?php echo $excluded_slot['slot'] ?>"></td><td><p class="remove_row excluded">-</p><p class="add_row excluded">+</p></td>
                            </tr> 
                        <?php } ?> 
                         
                    <?php } else { ?> 
                        <tr class="excluded_slot_row" data-key="0"><td><select name="exc[0][location]"><option value="melbourne">Melbourne</option><option value="sydney">Sydney</option></select></td>
                    <td><input required type="date" name="exc[0][date]" min="<?php echo date("Y-m-d", strtotime("+1days")); ?>"></td>
                    <td>
                    <select name="exc[0][type]">
                    <option value="exclude">Exclude</option>
                    <option value="include">Include</option>
                    </select>
                    </td>
                    <td><input name="exc[0][slot]" required min="0" type="text"></td><td><p class="remove_row excluded">-</p><p class="add_row excluded">+</p></td></tr>
                    <?php } ?>  
                </table>
            </div>
            <p class="submit"><input type="submit" name="excluded_submit" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>
        <h2>Popup Pages</h2>
        <form action="?page=delivery_slot.php" method="post" id="popuppages">
            <div class="scroll-table">
                <table class="form-table popup_pages">
                    <tr>
                        <th style="padding-bottom: 0;">Pages Url</th></tr>
                        <tr>
                            <td style="padding-left: 0;">
                                <textarea name="pages_url" class="large-text" style="height: 150px"><?php echo get_option('pages_url'); ?></textarea>
                            </td>
                        </tr>
                </table>
            </div>
            <p class="submit"><input type="submit" name="pages_url_sub" id="submit" class="button button-primary" value="Save Changes"></p>
        </form>
        <div class="record">
            <?php
                $melborne_slot=get_option('melborne_slot');
                $sydney_slot=get_option('sydney_slot'); 
                $days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
                
                $special_slots=get_option('special_slot');
                if(!empty($special_slots)):
                    foreach($special_slots as $special_slot):
                        if($special_slot['locaton']=="melbourne"):
                            $melborne_slot[date("MdY",strtotime($special_slot['date']))] =  $special_slot['slot'];
                        endif;
                        if($special_slot['locaton']=="sydney"):
                            $sydney_slot[date("MdY",strtotime($special_slot['date']))] =  $special_slot['slot'];
                        endif;
                    endforeach;
                endif;


            ?>
            <table class="wp-list-table widefat fixed striped posts">
                
                
                <thead>
                    <tr>
                        <th>Date/Day</th>
                        <th>Sydney Spots</th>
                        <th>Melbourne Spots</th>
                    </tr>
                    <?php 
                    for($i=01; $i<=date('t'); $i++):

                        $dateKey = date("Y-m-".$i);
                        $key  = $days_array[(date("N",strtotime($dateKey)) - 1)].", ".date("F d",strtotime($dateKey));
	                    
                        $dateUsedslot = date("F j, Y",strtotime($dateKey));
	                
                        $usedSlotes=get_total_slots_per_day_admin($dateUsedslot); 

                        $VICslotes=$usedSlotes['VIC'];
                        $NSWslotes=$usedSlotes['NSW'];

                    ?>
                        <tr style="<?php if($days_array[(date("N",strtotime($dateKey)) - 1)]=="Sunday"): echo "background: #dedede"; endif; ?>">
                            <td><?php 
                             echo date("M/d/Y",strtotime($dateKey)); ?></td>
                            <td>
                                <span style="color: #027502;font-weight: bold;">Max Spots: 
                                    <?php 
                                    if(array_key_exists(date("MdY",strtotime($dateKey)),$sydney_slot)):
                                        echo $sydney_slot[date("MdY",strtotime($dateKey))];
                                    else:
                                        echo $sydney_slot[(date('N',strtotime($dateKey)) - 1)]; 
                                    endif;
                                    ?>
                                </span>
                                <br>
                                <span style="font-weight: bold;color: #d28905;">Taken Spots: <?php
                                    if(array_key_exists($key, $NSWslotes)):
                                        echo $soldspotNsw = $NSWslotes[$key];
                                    else:
                                        echo $soldspotNsw = 0;
                                    endif;
                                ?></span>
                                <br>
                                <span style="font-weight: bold;color: #FF0000;">Remaining Spots: <?php 
                                if(array_key_exists(date("MdY",strtotime($dateKey)),$sydney_slot)):
                                    echo ($sydney_slot[date("MdY",strtotime($dateKey))] - $soldspotNsw); 
                                else:
                                    echo ($sydney_slot[(date('N',strtotime($dateKey)) - 1)] - $soldspotNsw); 
                                endif;
                                ?></span>
                            </td>
                            <td>
                                <span style="color: #027502;font-weight: bold;">Max Spots: 
                                    <?php 
                                    if(array_key_exists(date("MdY",strtotime($dateKey)),$melborne_slot)):
                                        echo $melborne_slot[date("MdY",strtotime($dateKey))];
                                    else:
                                        echo $melborne_slot[(date('N',strtotime($dateKey)) - 1)]; 
                                    endif;
                                    ?></span>
                                <br>
                                <span style="font-weight: bold;color: #d28905;">Taken Spots:  <?php
                                    if(array_key_exists($key, $VICslotes)):
                                        echo $soldspotVic = $VICslotes[$key];
                                    else:
                                        echo $soldspotVic = 0;
                                    endif;
                                ?></span>
                                <br>
                                <span style="font-weight: bold;color: #FF0000;">Remaining Spots: <?php 
                            if(array_key_exists(date("MdY",strtotime($dateKey)),$melborne_slot)):
                                echo ($melborne_slot[date("MdY",strtotime($dateKey))] - $soldspotVic); 
                            else:
                                echo ($melborne_slot[(date('N',strtotime($dateKey)) - 1)] - $soldspotVic); 
                            endif;
                                ?></span>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </thead>
            
            
            </table>
        </div>
    </div>
    <?php
}

function sydney_notice() {
    ?>
    <div class="updated notice">
        <p><?php echo 'Sydney spots has been successfully updated.'; ?></p>
    </div>
    <?php
}
function special_notice() {
    ?>
    <div class="updated notice">
        <p><?php echo 'Special spots has been successfully updated.'; ?></p>
    </div>
    <?php
}
function excluded_notice() {
    ?>
    <div class="updated notice">
        <p><?php echo 'Excluded spots has been successfully updated.'; ?></p>
    </div>
    <?php
}

function deliver_slot_enqueue_admin_script( $hook ) {
    wp_enqueue_script( 'deliver_slot_script', get_stylesheet_directory_uri(). '/js/admin.js',array(), time(), TRUE  );
    wp_enqueue_style( 'deliver_slot-css', get_stylesheet_directory_uri(). '/css/admin.css' );
}
add_action( 'admin_enqueue_scripts', 'deliver_slot_enqueue_admin_script' );


function get_customer_total_order() {
    $customer_orders = get_posts( array(
        'numberposts' => - 1,
        // 'meta_key'    => '_customer_user',
        // 'meta_value'  => get_current_user_id(),
        'post_type'   => array( 'shop_order' ),
        // 'post_status' => array( 'wc-completed' ),
        'date_query' => array(
            'after' => date('Y-m-d', strtotime('-1 year')),
            'before' => date('Y-m-d', strtotime('today')) 
        )

    ) );

    $total = 0;
    foreach ( $customer_orders as $customer_order ) {
        $order = wc_get_order( $customer_order );
        $total += $order->get_total();
    }

    return $total;
}

add_action('woocommerce_checkout_update_order_meta',function ( $order_id ) {
    $delivery_date = $_POST['delivery_date'];
    $delivery_date=date("Y/m/d",strtotime($delivery_date));
    update_post_meta($order_id, '_delivery_date', $delivery_date );
});


add_action( 'save_post', 'mv_save_wc_order_other_fields', 10, 1 );
if ( ! function_exists( 'mv_save_wc_order_other_fields' ) )
{
    function mv_save_wc_order_other_fields( $post_id )
    {

        if ( 'shop_order' == $_POST[ 'post_type' ] ) {

            $delivery_date = get_post_meta( $post_id,'delivery_date',true);
            $delivery_date=date("Y/m/d",strtotime($delivery_date));
            update_post_meta( $post_id, '_delivery_date', $delivery_date );
        }
    }
}

function get_total_slots_per_day()
{  
    $statuses = ['completed','processing'];
    $args = array(
        'return' => 'ids',
        'meta_key'     => '_delivery_date', 
        'meta_value'=>strtotime(date("Y-m-d")),
        'meta_compare' => '>',  
        'status' => $statuses, 
    );   
    $orders = wc_get_orders( $args );

    $VICsloteArray=array(); 
    $NSWsloteArray=array(); 

    if(!empty($orders))
    {   
        foreach ($orders as $order_id) 
        {
            $order = wc_get_order($order_id);
            $order_data = $order->get_data();
          
            $delivery_date=date("l, F d",strtotime(get_post_meta( $order_id,'_delivery_date',true)));
           
            if($order_data['shipping']['state']=='VIC' || $order_data['billing']['state']=='VIC')
            {   
                $VIClastVal= (empty($VICsloteArray[$delivery_date])) ? 0 : $VICsloteArray[$delivery_date]; 
                $VICsloteArray[$delivery_date]=$VIClastVal+1;
            } 
            else if( $order_data['shipping']['state']=='NSW' || $order_data['billing']['state']='NSW')
            {
                // $NSWsloteArray[$delivery_date]=$orders;
                $NSWlastVal= (empty($NSWsloteArray[$delivery_date])) ? 0 : $NSWsloteArray[$delivery_date]; 
                $NSWsloteArray[$delivery_date]=$NSWlastVal+1;
            }
        }       
    }
     
     $result=array('VIC' => $VICsloteArray, 'NSW' => $NSWsloteArray);

    // if(isset($_GET['test']))
    // {  
      
    //    //  update_post_meta(26242,'delivery_date_check','08/05/2020');
    //    // echo get_post_meta( 26242,'_delivery_date',true).'<br>';
    //     echo '<pre>';
    //     print_r($result);
    //     print_r($orders);
    //     echo '</pre>';
    //     die('======');       
    // } 
    return $result;
}

function get_total_slots_per_day_admin($order_date)
{  
global $wpdb;

$orderrows = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE `meta_key`='delivery_date' AND `meta_value`='".$order_date."' ORDER BY `post_id` DESC");

	
	//print_r($orderrows);

$statuses = ['completed','processing'];
    // $args = array(
    //     'return' => 'ids',
    //     'meta_key'     => 'delivery_date', 
    //     'meta_value'=> $order_date,
    //     'meta_compare' => '=',  
    //     'status' => $statuses,
    //     'limit'=> 100
    // );   
    // $orders = wc_get_orders( $args );
$orders = array();
if(!empty($orderrows)):
    foreach($orderrows as $order):
        $orders[] = $order->post_id;
	
    endforeach;
endif;

    $VICsloteArray=array(); 
    $NSWsloteArray=array(); 
    $VIClastVal=0;
    $NSWlastVal = 0;
    if(!empty($orders))
    {   
        foreach ($orders as $order_id) 
        {
            $order = wc_get_order($order_id);
            $order_data = $order->get_data(); 
            $delivery_date=date("l, F d",strtotime(get_post_meta( $order_id,'delivery_date',true)));
            if(($order_data['shipping']['state']=='VIC' || $order_data['billing']['state']=='VIC') && (($order_data['status']=='processing') || $order_data['status']=='completed'))
            {   
				//print_r($order_data);
                $VIClastVal++;
                $VICsloteArray[$delivery_date]=$VIClastVal;
            } 
            else if(($order_data['shipping']['state']=='NSW' || $order_data['billing']['state']=='NSW') && (($order_data['status']=='processing') || $order_data['status']=='completed'))
            {
                // $NSWsloteArray[$delivery_date]=$orders;
                $NSWlastVal++; 
                $NSWsloteArray[$delivery_date]=$NSWlastVal;
            }
        }       
    }
     
     $result=array('VIC' => $VICsloteArray, 'NSW' => $NSWsloteArray);

    // if(isset($_GET['test']))
    // {  
      
    //    //  update_post_meta(26242,'delivery_date_check','08/05/2020');
    //    // echo get_post_meta( 26242,'_delivery_date',true).'<br>';
    //     echo '<pre>';
    //     print_r($result);
    //     print_r($orders);
    //     echo '</pre>';
    //     die('======');       
    // } 
    return $result;
}

// add_action( 'init', 'get_total_slots_per_day' );
function disable_plugin_updates( $value ) {
    unset( $value->response['address-autocomplete-using-google-place-api/address-autocomplete-using-google-place-api.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'disable_plugin_updates' );

// Redirect users after add to cart.
function my_custom_add_to_cart_redirect( $url ) {
    $url = get_permalink(26258);
    return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );


// define the actions for the two hooks created, first for logged in users and the next for logged out users



function my_enqueue() {

    wp_enqueue_script( 'ajax-script', get_template_directory_uri() . '/js/global.js', array('jquery') );


    wp_localize_script( 'ajax-script', 'my_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'my_enqueue' );


add_action("wp_ajax_get_cities_list", "get_cities_list");
// define the function to be fired for logged in users
function get_cities_list() {
  echo "M I HERE";
   die();
}


// add_filter( 'woocommerce_checkout_create_order', 'check_remaining_spot', 10, 1 );
// function check_remaining_spot($order) {
// $usedSlots = get_total_slots_per_day();
// if($_COOKIE['wooexp_city']=="melbourne"):
//     $melborne_slot=get_option('melborne_slot');
//     $days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

//     $used = 0;
//     $delkey = date("l, F d");
//     if(array_key_exists($delkey, $usedSlots['VIC'])):
//         $used = $usedSlots['VIC'][$delkey];
//     endif;
//     $year = date("Y");
//     $remaining = ($melborne_slot[(date('N',strtotime($_COOKIE['wooexp_date']." ".$year)) - 1)] - $used);

//     echo $remaining;
//     //echo date("l, d M");
// else:
//     $sydney_slot=get_option('sydney_slot'); 
//     $days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

//     $used = 0;
//     $delkey = date("l, F d");
//     if(array_key_exists($delkey, $usedSlots['NSW'])):
//         $used = $usedSlots['NSW'][$delkey];
//     endif;
//     $year = date("Y");
//     $remaining = ($sydney_slot[(date('N',strtotime($_COOKIE['wooexp_date']." ".$year)) - 1)] - $used);

//     echo $remaining;
// endif;



//   echo "<pre>";
//   print_r($usedSlots);
//   echo $_COOKIE['postcode'];
//   echo "<br>";
//   echo $_COOKIE['wooexp_city'];
//   echo "<br>";
//   echo $_COOKIE['wooexp_city_val'];
//   echo "<br>";
//   echo $_COOKIE['wooexp_date'];
//   exit;
//   return $order;

// }

add_action( 'woocommerce_after_checkout_validation', 'remaining_spot_check_while_checkout', 10, 2);
 
function remaining_spot_check_while_checkout( $fields, $errors ){
                
    $usedSlots = get_total_slots_per_day();
    setcookie('validation_error_remaining',0);
    if($_COOKIE['wooexp_city']=="melbourne"):
        $melborne_slot=get_option('melborne_slot');
        $days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        $special_slots=get_option('special_slot');
        if(!empty($special_slots)):
            foreach($special_slots as $special_slot):
                if($special_slot['locaton']=="melbourne"):
                    $melborne_slot[(date("N",strtotime($special_slot['date'])) - 1)] =  $special_slot['slot'];
                endif;
            endforeach;
        endif;

        $used = 0;
        $year = date("Y");
        $delkey = date("l, F d",strtotime($_COOKIE['wooexp_date']." ".$year));
        if(array_key_exists($delkey, $usedSlots['VIC'])):
            $used = $usedSlots['VIC'][$delkey];
        endif;
        
        $remaining = ($melborne_slot[(date('N',strtotime($_COOKIE['wooexp_date']." ".$year)) - 1)] - $used);
        if($remaining<1):
        	echo $errors->add( 'validation', 'No Spots Remaining For '.$_COOKIE['wooexp_date'].'. Please select another delivery date.');
        	
        	setcookie('validation_error_remaining',1);
        	setcookie('wooexp_city','',1);
        	setcookie('wooexp_date','',1);
        	setcookie('wooexp_city_val','',1);
        endif;
        //echo date("l, d M");
    else:
        $sydney_slot=get_option('sydney_slot'); 
        $days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        $special_slots=get_option('special_slot');
        if(!empty($special_slots)):
            foreach($special_slots as $special_slot):
                if($special_slot['locaton']=="sydney"):
                    $sydney_slot[(date("N",strtotime($special_slot['date'])) - 1)] =  $special_slot['slot'];
                endif;
            endforeach;
        endif;
        $used = 0;
        $year = date("Y");
        $delkey = date("l, F d",strtotime($_COOKIE['wooexp_date']." ".$year));
        if(array_key_exists($delkey, $usedSlots['NSW'])):
            $used = $usedSlots['NSW'][$delkey];
        endif;
        
        $remaining = ($sydney_slot[(date('N',strtotime($_COOKIE['wooexp_date']." ".$year)) - 1)] - $used);
        if($remaining<1):
        	echo $errors->add( 'validation', 'No Spots Remaining For '.$_COOKIE['wooexp_date'].'. Please select another delivery date.');
        	
        	setcookie('validation_error_remaining',1);
        	setcookie('wooexp_city','',1);
        	setcookie('wooexp_date','',1);
        	setcookie('wooexp_city_val','',1);
        endif;
    endif;
}
add_action( 'wp_ajax_get_suburb', 'get_suburb' );
add_action('wp_ajax_nopriv_get_suburb', 'get_suburb');
function get_suburb() {
    global $wpdb; // this is how you get access to the database
    $suburbs = $wpdb->get_results($wpdb->prepare("SELECT `suburb_name`, `suburb_postcode`, `state`  FROM `wp_suburbs` WHERE `suburb_name` LIKE '".$wpdb->esc_like($_POST['suburb'])."%' OR `suburb_postcode` LIKE '".$wpdb->esc_like($_POST['suburb'])."%' limit 10"));
    if(!empty($suburbs)):
        foreach($suburbs as $suburb):
            ?>
                <li><a href="javascript:void(0);" class="cities_link" data-postcode="<?php echo $suburb->suburb_postcode; ?>"><?php echo $suburb->suburb_name; ?> <?php echo $suburb->state; ?>, <?php echo $suburb->suburb_postcode; ?>, Australia</a></li>
            <?php
        endforeach;
    endif;
    wp_die(); // this is required to terminate immediately and return a proper response
}


// function wpdocs_register_my_custom_menu_page(){
//     add_menu_page( 
//         __( 'Custom Menu Title', 'textdomain' ),
//         'custom menu',
//         'manage_options',
//         'custompage',
//         'my_custom_menu_page',
//         plugins_url( 'myplugin/images/icon.png' ),
//         6
//     ); 
// }
// add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );
 
// /**
//  * Display a custom menu page
//  */
function my_custom_menu_page(){
    esc_html_e( 'Admin Page Test', 'textdomain' );  

 $args = array(
      'numberposts' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
      'post_status'=>'publish',
      'post_type'   => 'delivery-suburb',
      'post_parent'=> 163674
    );
     
    $states = get_posts( $args );

    


    $output = "";
    if(!empty($states)):
        echo "<table>";
        foreach($states as $state):
            echo "<tr>";
            //echo "<td>".$state->post_title."</td>";
            echo "<td>".get_post_meta( $state->ID, 'zip_code', true )."</td>";
            echo "</tr>";
            //$output .= '<div class="medium-4" style="float: left; width:33%;"><p><a href="'.esc_url( get_permalink($state->ID) ).'">'.$state->post_title.'('.(get_post_meta( $state->ID, 'shipping_price', true )=="" ? "$0.00" : "$".get_post_meta( $state->ID, 'shipping_price', true )).')</a></p></div>';
        endforeach;
            echo '</table>';
    endif;


    ///global $wpdb;
     // $metta = $wpdb->get_results("SELECT * FROM `wp_suburbs` WHERE `state`='VIC'");


     //    $my_post = array(
     //          'post_title'    => "HELLO ",
     //          'post_content'  => "HELLO",
     //          'post_status'   => 'publish',
     //          'post_type'     => 'delivery-suburb',
     //          'post_author'   => 1,
     //          'post_parent' => 163673
     //        );
             
     //        // Insert the post into the database
     //        $id = wp_insert_post( $my_post );

     //        update_post_meta ( $id, 'zip_code', 3000 );

     //echo $wpdb->num_rows;

    // echo "<pre>";
    // print_r($metta);
    // echo "</pre>";

    // if(!empty($metta)):
    //     $i=1;
    //     foreach($metta as $item):
    //        $my_post = array(
    //           'post_title'    => $item->suburb_name,
    //           'post_content'  => $item->suburb_name,
    //           'post_status'   => 'publish',
    //           'post_type'     => 'delivery-suburb',
    //           'post_author'   => 1,
    //           'post_parent' => 163673
    //         );
             
    //         // Insert the post into the database
    //         $id = wp_insert_post( $my_post );

    //         update_post_meta ( $id, 'zip_code', $item->suburb_postcode );
    //         if($id):
    //             echo $i.")  YES";
    //             echo "<br>";
    //         else:
    //             echo $i.")  NO ".$item->suburb_name." ".$item->suburb_postcode;
    //             echo "<br>";
    //         endif; 
    //         $i++;
    //     endforeach;
    // endif;
//     // Create post object

}

// function na_remove_slug( $post_link, $post, $leavename ) {

//     if ( 'delivery-suburb' != $post->post_type || 'publish' != $post->post_status ) {
//         return $post_link;
//     }

//     $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

//     return $post_link;
// }
// add_filter( 'post_type_link', 'na_remove_slug', 10, 3 );

// function na_parse_request( $query ) {

//     if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
//         return;
//     }

//     if ( ! empty( $query->query['name'] ) ) {
//         $query->set( 'post_type', array( 'post', 'delivery-suburb', 'page' ) );
//     }
// }
// add_action( 'pre_get_posts', 'na_parse_request' );


function cptui_register_my_cpts_delivery_suburb() {

    /**
     * Post Type: Suburbs.
     */

    $labels = [
        "name" => __( "Suburbs", "north-wp" ),
        "singular_name" => __( "Suburb", "north-wp" ),
    ];

    $args = [
        "label" => __( "Suburbs", "north-wp" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => true,
        "rewrite" => [ "slug" => "delivery-suburb", "with_front" => false ],
        "query_var" => true,
        "supports" => [ "title", "editor", "thumbnail", "page-attributes" ],
    ];

    register_post_type( "delivery-suburb", $args );
}

add_action( 'init', 'cptui_register_my_cpts_delivery_suburb' );


add_shortcode('SUBURBS', 'get_suburbs' );
function get_suburbs($atts) {
    global $wpdb;


    // $args = array(
    //   'numberposts' => -1,
    //   'orderby' => 'title',
    //   'order' => 'ASC',
    //   'post_status'=>'publish',
    //   'post_type'   => 'delivery-suburb',
    //   'post_parent'=> $atts['parent']
    // );
     
    // $states = get_posts( $args );

  


$output = "<div class='col-md-12 text-right'>
<div class='col-md-3 col-md-offset-6 text-right'><label>Search</label></div>
<div class='col-md-4'><input type='text' class='form-control searchcity' data-parent='".$atts['parent']."'> <a href='javascript:void(0);' class='btn searchcitybtn' >Search</a></div>
</div>
<div style='display:none' class='searchResult".$atts['parent']."'></div>
<div class='wrapResult".$atts['parent']."'>";
foreach( range('A', 'Z') as $elements) { 
      
    
$results = $wpdb->get_results("SELECT * FROM `wp_posts`  WHERE `post_parent`=".$atts['parent']." AND `post_type`='delivery-suburb' AND `post_status`='publish' AND `post_title` like '".$elements."%' ORDER BY 'post_title' ASC LIMIT 10");

    
    if(!empty($results)):
    	$output .= '<div class="medium-4 row_'.$elements.'_'.$atts['parent'].'" style="float: left; width:33%;"><h2>'.$elements.'</h2><p>';    
        foreach($results as $state):
            
            $output .= '<a href="'.esc_url( get_permalink($state->ID) ).'">'.$state->post_title.'</a><br>';
            
        endforeach;
            $output .='</p><center><span class="row_'.$elements.'_'.$atts['parent'].'_spin" style="display:none; text-align:center;"><i class="fa fa-spinner fa-spin"></i></span><div style="text-align:left;"><a href="javascript:void(0);" class="load_more_states" data-wrap="row_'.$elements.'_'.$atts['parent'].'" data-element="'.$elements.'" data-limit="10" data-state="'.$atts['parent'].'">Show More</a></div></center></div>';
    endif;

} 

    $output .='<div class="clearfix"></div></div>';
    
    return $output;
}

add_action( 'wp_ajax_my_action', 'load_ciies' );

function load_ciies() {
    global $wpdb; // this is how you get access to the database

$results = $wpdb->get_results("SELECT * FROM `wp_posts`  WHERE `post_parent`=".$_POST['state']." AND `post_type`='delivery-suburb' AND `post_status`='publish' AND `post_title` like '".$_POST['element']."%' ORDER BY 'post_title' ASC LIMIT ".$_POST['limit'].",10");


    $output = "";
    if(!empty($results)):
        
        foreach($results as $state):
            
            $output .= '<a href="'.esc_url( get_permalink($state->ID) ).'">'.$state->post_title.'</a><br>';
            
        endforeach;
            
    endif;

    echo $output;
    wp_die(); // this is required to terminate immediately and return a proper response
}


add_action( 'wp_ajax_nopriv_my_action', 'load_ciies' );


add_action( 'wp_ajax_search_city', 'search_city' );

function search_city() {
    global $wpdb; // this is how you get access to the database

$results = $wpdb->get_results("SELECT * FROM `wp_posts`  WHERE `post_parent`=163674 AND `post_type`='delivery-suburb' AND `post_status`='publish' AND `post_title` like '".$_POST['search']."%' ORDER BY 'post_title' ASC");


    $output = '<div class="medium-4 row_vic_'.$atts['parent'].'" style="float: left; width:33%;"><h2>VIC</h2><p>';
    if(!empty($results)):
        
        foreach($results as $state):
            
            $output .= '<a href="'.esc_url( get_permalink($state->ID) ).'">'.$state->post_title.'</a><br>';
            
        endforeach;
            
    endif;

    $results = $wpdb->get_results("SELECT * FROM `wp_posts`  WHERE `post_parent`=163673 AND `post_type`='delivery-suburb' AND `post_status`='publish' AND `post_title` like '".$_POST['search']."%' ORDER BY 'post_title' ASC");


    $output .= '</div><div class="medium-4 row_vic_'.$atts['parent'].'" style="float: left; width:33%;"><h2>NSW</h2><p>';
    if(!empty($results)):
        
        foreach($results as $state):
            
            $output .= '<a href="'.esc_url( get_permalink($state->ID) ).'">'.$state->post_title.'</a><br>';
            
        endforeach;
            
    endif;

    $output .= "</div><div class='clearfix'></div>";

    echo $output;
    wp_die(); // this is required to terminate immediately and return a proper response
}


add_action( 'wp_ajax_nopriv_search_city', 'search_city' );

add_shortcode( 'showproduct', 'wpdocs_footag_func' );

function wpdocs_footag_func($atts){

	$city = get_selected_city();
    $shortcode_city = get_city_attr_shortcode();
    
    $city = $city=='' && $shortcode_city!='' ? $shortcode_city : $city;

    if($city!=''){
        $post_not_in = get_out_of_stock_by_city();
        $include_products =  array();
        if(isset($_COOKIE['wooexp_date']) && isset($_COOKIE['wooexp_city']))
        {
            $excluded_slot=get_option('excluded_slot');
            if(!empty($excluded_slot))
            {
                $current=strtotime($_COOKIE['wooexp_date']);
                foreach ($excluded_slot as $citydata) 
                {   
                    $date = strtotime($citydata['date']);
                    if($citydata['location']==$_COOKIE['wooexp_city'] && $current==$date)
                    {   
                        $proIds=explode(',', $citydata['slot']);
                        if($citydata['type']=="exclude"){
                            if(!empty($proIds)):
                                foreach($proIds as $prodid):
                                    $post_not_in[] = $prodid;
                                endforeach;
                            endif;
                        }else{
                            if(!empty($proIds)):
                                foreach($proIds as $prodid):
                                    $include_products[] = $prodid;
                                endforeach;
                            endif; 
                        }
                    }
                }
            }
        }
        if(empty($include_products)):
        	?>
			<ul class="products row thb-main-products">
			<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => $atts['limit'],
					'limit'=> $atts['limit'],
					'orderby'=>"date",
					'order'=>"DESC",
					'category'=> $atts['category'],
					'cat_operator'=>$atts['cat_operator'],
					'post__not_in'=> $post_not_in
					);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) : $loop->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile;
				} else {
					echo __( 'No products found' );
				}
				wp_reset_postdata();
			?>
			</ul><?php
        else:
           ?>
			<ul class="products row thb-main-products">
			<?php
				$args = array(
					'post_type' => 'product',
					'posts_per_page' => $atts['limit'],
					'limit'=> $atts['limit'],
					'orderby'=>"date",
					'order'=>"dec",
					'category'=> $atts['category'],
					'cat_operator'=>$atts['cat_operator'],
					'post__in'=> $include_products
					);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) : $loop->the_post();
						wc_get_template_part( 'content', 'product' );
					endwhile;
				} else {
					echo __( 'No products found' );
				}
				wp_reset_postdata();
			?>
			</ul><?php
        endif;
    }
	 
}
