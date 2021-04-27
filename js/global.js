window.jQuery(function ($) {
  $('#delivery_date').prop('readonly', true)

  function alterhtml () {
    $('.woocommerce-shipping-fields__field-wrapper').prepend('<h3>Delivery details</h3>')
    $('.woocommerce-form__label.woocommerce-form__label-for-checkbox.checkbox').hide()
    $('.footer').append('<div style=\'display:flex;justify-content:center;padding-top:50px;\'><p style=\'font-size:14px;\'>© Fig &amp; 2019</p><p style=\'padding:0 10px;\'>|</p><p style=\'font-size:14px;\'><a target=\'_blank\' href=\'/terms-and-conditions/\'>Terms and Conditions</a></p><p style=\'padding:0 10px;\'>|</p><p style=\'font-size:14px;\'>All prices are in Australian dollars</a></p></div>')
  }

  /***
   * hideCouponFromCart :: user can only redeem coupon on checkout page
   */
  function hideCouponFromCart () {
    var isViewingCart = RegExp('cart').test(window.location.href)
    if (!isViewingCart) return
    window.setInterval(function () {
      window.jQuery('.coupon').hide()
    }, 250)
  }

  /***
   * hideFieldShippingCountry :: user doesn't need to choose country
   */
  function hideFieldShippingCountry () {
    var isViewingCheckout = RegExp('checkout').test(window.location.href)
    if (isViewingCheckout) {
      window.jQuery('#shipping_country_field').hide()
    }
  }

  /***
   * reduceCartAbandonment :: hide shipping total from cart view
   */
  function reduceCartAbandonment () {
    var pattern = RegExp('/cart/')
    if (pattern.test(window.location.href)) {
      window.jQuery('.woocommerce-shipping-totals.shipping').hide()
    }
  }

  /***
   * reduceCheckoutAbandonment :: hide content to reduce distraction on checkout page
   */
  function reduceCheckoutAbandonment () {
    var pattern = RegExp('/checkout/')
    if (pattern.test(window.location.href)) {
      // hide header on desktop
      window.jQuery('#menu-navigation-menu').children().hide()
      window.jQuery('.account-holder').children().hide()
      window.jQuery('#menu-navigation-menu').prepend('<li id="menu-item-9330" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-9330"><a href="javascript:history.back()">< Continue Shopping</a></li>')
      // hide header on mobile
      window.jQuery('.hide-for-large.toggle-holder').children().hide()
      window.jQuery('.hide-for-large.toggle-holder').prepend('<a style="padding-left:10px;" href="javascript:history.back()">< Back</a>')
      // hide the footer
      window.jQuery('footer').css('background', '#fff').children().hide()
    }
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

  /***
   * hideBeansBranding :: hide Beans logo and link from rewards and loyalty pages
   */
  function hideBeansBranding () {
    var isViewingRewards = RegExp('rewards').test(window.location.href)
    var isViewingReferral = RegExp('referral').test(window.location.href)

    if (isViewingRewards) {
      window.setInterval(function () {
        window.jQuery('#liana-rewards-page').siblings().first().remove()
      }, 250)
    }

    if (isViewingReferral) {
      window.setInterval(function () {
        window.jQuery('#bamboo-referral-page').siblings().first().remove()
      }, 250)
    }
  }

  function isPastOrderDeadline () {
    var dateString = window.jQuery('#delivery_date').val()
    var date = window.moment(dateString, 'MMMM DD, YYYY')
    var isSameDate = window.moment().isSame(date, 'day')
    var isPastHour = window.moment().hour() >= 13
    var isPastMinute = window.moment().minute() > 10
    return isSameDate && isPastHour && isPastMinute
  }

  function showCountdownTimer () {
    //if (window.moment().hour() === 12) {
      if (parseInt($(".date_current_hour_sf").val()) === 12) {
      var element = '<div class="samedaydelivery" style="width:100%; padding: 20px 0; background-color:#ff7675; ' +
        'position: fixed; bottom:0px; z-index:1000; color:white; text-align:center; ' +
        'font-weight: bold;">Order before 1 pm for same day delivery.</div>'

      window.jQuery('body').append(element)
      //   var eventTime = window.moment().hour(13).minute(0)
      //   var diffTime = eventTime - window.moment()
      //   var duration = window.moment.duration(diffTime * 1000, 'milliseconds')
      //   var interval = 1000

    //   setInterval(function () {
    //     duration = window.moment.duration(duration - interval, 'milliseconds')
    //     window.jQuery('#countdown-minutes').text(duration.minutes())
    //     window.jQuery('#countdown-seconds').text(duration.seconds())
    //   }, interval)
    }
  }

  function isInThePast () {
    var userInput = window.jQuery('#delivery_date').val()
    var dateDelivery = window.moment(userInput, 'MMMM DD, YYYY').hour(12).minute(0)
    var dateToday = window.moment().hour(12).minute(0)
    return dateDelivery.diff(dateToday, 'minutes') <= -1440
  }

  $(document).ready(function () {
    alterhtml()
    reduceCartAbandonment()
    reduceCheckoutAbandonment()
    hideCouponFromCart()
    hideFieldShippingCountry()
    hideBeansBranding()
    rejectInvalidMessageLength()
    showCountdownTimer()
  })
})

jQuery(document).ready(function($){
  setTimeout(function(){ 
    $("#shipping_city-description").html("Want to change the suburb? <span class='stickycity' onclick='runpop();'><strong>Click Here</strong></span>");
  }, 1000);

  
  // $("#shipping_address_2").attr("placeholder","Apartment, suite, unit etc.");
  // $("#shipping_address_2").closest('.form-row').addClass('validate-required');

  // $('body').on('blur change', '#shipping_address_2', function(){
  //   var wrapper = $(this).closest('.form-row');
  //   $(this).attr("placeholder","Apartment, suite, unit etc.");
  //   // you do not have to removeClass() because Woo do it in checkout.js
  //   if($(this).val()=="") { // check if contains numbers
  //     wrapper.addClass('woocommerce-invalid'); // error
  //   } else {
  //     wrapper.addClass('validate-required'); // success
  //   }
  // });  

  $(document.body).on('checkout_error',function(){
       if(getCookie('validation_error_remaining')==1){
        location.reload();
       }
  });
});


function runpop(){
    jQuery(".sticky_city").trigger("click");
  }


function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
              c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
        }
        return "";
    }




jQuery(document).ready(function($) {


  $(document).on("click",".load_more_states",function(){
    var limit = $(this).attr('data-limit');
    var element = $(this).attr('data-element');
    var state = $(this).attr('data-state');
      var data = {
        'action': 'my_action',
        'element': element,
        'limit': limit,
        'state': state
      };
      var anchorlink = $(this);
      $(".row_"+element+"_"+state+"_spin").show();
      anchorlink.hide();
      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      jQuery.post(my_ajax_object.ajax_url, data, function(response) {
        if(response==""){
          anchorlink.hide();
          $(".row_"+element+"_"+state+"_spin").hide();
        }else{
          $(".row_"+element+"_"+state+"_spin").hide();
          anchorlink.show();
          $(".row_"+element+"_"+state+" p").append(response);
          anchorlink.attr("data-limit",(parseInt(limit) + 10));
        }
        
      });
  });
    
  $(document).on("click",".searchcitybtn",function(){
    var parent = $(".searchcity").attr('data-parent');
    var search = $(".searchcity").val();

    if($(".searchcity").val().length < 2){
      $(".wrapResult"+parent).show();
      $(".searchResult"+parent).hide();
      return false;
    }
    
    $(".wrapResult"+parent).hide();
    $(".searchResult"+parent).show().html("<div class='text-center' style='font-size:45px;'><i class='fa fa-spinner fa-spin'></i></div>");

      var data = {
        'action': 'search_city',
        'parent': parent,
        'search': search
      };
      
      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
      jQuery.post(my_ajax_object.ajax_url, data, function(response) {
        if(response!=""){
          $(".wrapResult_"+parent).hide();
          $(".searchResult"+parent).show().html(response);
        }        
      });
  });
});