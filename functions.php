<?php

/**
 * Add Javascript to Checkout page
 */
add_action( 'woocommerce_after_checkout_form', 'add_checkout_page_js');
 
function add_checkout_page_js() {
  echo '<script type="text/javascript">window.jQuery(function ($) {
    function isInThePast () {
      var userInput = window.jQuery("#delivery_date").val()
      var dateDelivery = window.moment(userInput, "MMMM DD, YYYY").hour(12).minute(0)
      var dateToday = window.moment().hour(12).minute(0)
      return dateDelivery.diff(dateToday, "minutes") <= -1440
    }

    function createAccount () {
      jQuery("#createaccount").prop("checked", true)
    }

    function alterHTML () {
      $(".woocommerce-shipping-fields__field-wrapper").prepend("<h3>Delivery details</h3>")
    }

    /***
     * reduceCheckoutAbandonment :: hide content to reduce distraction on checkout page
     */

    function reduceCheckoutAbandonment () {
      var pattern = RegExp("/checkout/")
      if (pattern.test(window.location.href)) {
        // hide header on desktop
        window.jQuery("#menu-navigation-menu").children().hide()
        window.jQuery(".account-holder").children().hide()
        window.jQuery("#menu-navigation-menu").prepend("<li id=\'menu-item-9330\' class=\'menu-item menu-item-type-post_type menu-item-object-page menu-item-9330\'><a href=\'javascript:history.back()\'>< Continue Shopping</a></li>")
        // hide header on mobile
        window.jQuery(".hide-for-large.toggle-holder").children().hide()
        window.jQuery(".hide-for-large.toggle-holder").prepend("<a style=\'padding-left:10px;\' href=\'javascript:history.back()\'>< Back</a>")
        // hide the footer
        window.jQuery("footer").css("background", "#fff").children().hide()
      }
    }


  /***
   * rejectInvalidDeliveryDate :: show user an error when given invalid delivery date
   */

  function rejectInvalidDeliveryDate () {
    function fireTagManagerEvent (value) {
      if (typeof window.dataLayer === "undefined") {
        console.log("Missing dataLayer variable")
        return
      }

      var label = typeof value === "number"
        ? value.toString()
        : value

      var dataObject = {
        "event": "delivery_date_invalid",
        "category": "click",
        "label": label,
        "value": label
      }

      window.dataLayer.push(dataObject)
    }

      function getDeliveryCity () {
        // input must exist
        var el = window.jQuery("#shipping_postcode")
        if (!el.val()) return

        // input must be 4 characters
        var input = el.val().trim()
        if (input.length < 4) {
          throw new Error("Invalid postcode. Length must be 4.")
        }

        var firstChar = input.slice(0, 1)
        switch (firstChar) {
          case "2": {
            return "Sydney"
          }
          case "3": {
            return "Melbourne"
          }
          default: {
            return "Other"
            //return null
          }
        }
      }

      function checkIsClosedForHoliday (city, date) {
        console.log("Checking for holiday closure", city, date)
        var message = ""
        var closures = [
          {
            city: ["Melbourne", "Sydney"],
            date: "April 2, 2021",
            message: "Sorry, we are closed on Good Friday. Next available delivery date is Saturday, April 3."
          },
          {
            city: ["Melbourne", "Sydney"],
            date: "April 5, 2021",
            message: "Sorry, we are closed on Easter Monday. Next available delivery date is Tuewday, April 6."
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
          console.error("Range error. Expect number between 0-6")
          return "Unexpected error. Please check postcode is valid."
        }
        // alert on invalid postcode
        var city = getDeliveryCity()
        if (!city) {
          return "Please enter valid postcode before choosing delivery date."
        }
        // alert on store closure
        var messages = {
          "Sydney": [
            /* Sunday */
            "Sorry we are closed on Sundays. Please choose any other day.",
            /* Monday */
            "",
            /* Tuesday */
            "",
            /* Wednesday */
            "",
            /* Thursday */
            "",
            /* Friday */
            "",
            /* Saturday */
            ""
          ],
          "Melbourne": [
            /* Sunday */
            "Sorry we are closed on Sundays. Please choose any other day.",
            /* Monday */
            "",
            /* Tuesday */
            "",
            /* Wednesday */
            "",
            /* Thursday */
            "",
            /* Friday */
            "",
            /* Saturday */
            ""
          ]
        }
        return messages[city][day]
      }

      window.jQuery("#delivery_date").change(function (event) {
        // Only run of moment library is available
        if (!window.moment) return

        // Sanitize string input and convert to integer
        var input = window.jQuery(this).val().trim()
        if (input === "") return

        // Transform text input to integer with range 0 - 6
        var dayInt = window.moment(input, "MMMM D, YYYY").weekday()

        // Log error if shop is closed
        try {
          var city = getDeliveryCity()

          var rejectSameDayDelivery = isPastOrderDeadline()
          if (rejectSameDayDelivery) {
            window.alert("You missed our 1 pm cut-off for delivery today. Please choose a different date.")
            window.jQuery(this).val("")
            return
          }

          var rejectTimeTravel = isInThePast()
          if (rejectTimeTravel) {
            window.alert("Selected date is the past. Please choose a different date.")
            window.jQuery(this).val("")
            return
          }

          var holidayClosureMessage = checkIsClosedForHoliday(city, input)
          if (holidayClosureMessage) {
            fireTagManagerEvent(city)
            window.alert(holidayClosureMessage)
            window.jQuery(this).val("")
            return
          }

          var weekdayClosureMessage = checkIsClosedWeekday(dayInt)
          if (weekdayClosureMessage) {
            fireTagManagerEvent(city)
            window.alert(weekdayClosureMessage)
            window.jQuery(this).val("")
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
      var id = "id=\'char_count_container\'"
      var style = "style=\'margin-left:10px;color:#2ecc71;font-weight:bold\'"
      var limit = 200
      var curr = 0

      window.jQuery("#order_comments_field > label")
        .append("<span  " + id + style + ">(<span id=\'char_count_val\'>" + limit + "</span> chars remaining)</span>")

      window.jQuery("#order_comments").keyup(function (evt) {
        curr = evt.target.value.length
        if (curr >= limit) {
          window.jQuery("#char_count_val").text(0)
          window.jQuery("#char_count_container").css("color", "#e74c3c")
          window.jQuery("#order_comments").css("border", "1px solid #e74c3c")
          window.jQuery("#order_comments").val(evt.target.value.slice(0, limit))
        } else {
          window.jQuery("#char_count_val").text(limit - curr)
          window.jQuery("#char_count_container").css("color", "#2ecc71")
          window.jQuery("#order_comments").css("border", "1px solid #e5e5e5")
        }
      })
    }

    $(document).ready(function () {
      alterHTML()
      createAccount()
      reduceCheckoutAbandonment()
      rejectInvalidDeliveryDate()
      rejectInvalidMessageLength()
    })
  })</script>';
}

/** Add Javascript to Cart page */
add_action('woocommerce_after_cart', 'add_cart_page_js');

function add_cart_page_js() {
  echo '<script type="text/javascript">window.jQuery(function ($) {
    /***
     * hideCouponFromCart :: user can only redeem coupon on checkout page
     */

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
  </script>'
}
