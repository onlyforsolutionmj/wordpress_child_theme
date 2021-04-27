jQuery(function($){
   
   var wc_city = {
        init: function () {
            $(document).on('click','.close-sec',{view:this},this.redirect_city);
            $(document).on("click",'.sticky_city',{view:this},this.trigger_city_popup);
            wc_city.check_city();
        },
        redirect_city:function(){
            var city =$('.suburb_city').val().includes("VIC, Australia") ? 'melbourne': 'sydney';
            var city_val =$('.suburb_city').val();
            var date = jQuery('.date_select1 .drop-down .selected a').html();

            wc_city.update_data('wooexp_city',city,'wooexp_date',date,'wooexp_city_val',city_val,1);
            var url = window.location.href;
            if(wooexperts.is_front_page==='no'){
                url = wc_city.set_param_val(url,'pa_city',city);
            }
            if(url.indexOf('attribute_pa_city') !== -1){
                url = wc_city.set_param_val(url,'attribute_pa_city',city);
            }
            window.location.href = url;
        },
        check_city:function(){
            var city = wc_city.get_data('wooexp_city','wooexp_date');
            if((city==='' && wooexperts.is_product_page==='1')
                || (city==='' && wooexperts.is_tax_page==='yes')
                || (city==='' && wooexperts.is_front_page==='yes')
                || (city==='' && wooexperts.is_popup_page==='yes')
            ){
                wc_city.trigger_city_popup();
            }
            if ( city !== '' ) { // MIKE added this stuff
	            $( "li.product a" ).each(function( index ) { // Product Links
					var thishref = $(this).attr("href");
					$(this).attr("href", thishref + '?attribute_pa_city=' + city);
				});
				$( "#nav li.menu-item a" ).each(function( index ) { // Category Links
					if($(this).attr('href') !== undefined) { 
						var thishref = $(this).attr("href");
						if ( thishref && thishref.indexOf("product-category") >= 0 ) {
						    $(this).attr("href", thishref + '?pa_city=' + city);
						}
					}
				});
            }
        },
        set_param_val:function(uri, key, value){
            var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            }
            else {
                return uri + separator + key + "=" + value;
            }
        },
        get_data:function(cname,cdate){
            var name = cname + "=";
            var date = cdate + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i <ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },
        update_data:function(cname,cvalue,cdate,cdvalue,cnameval,cnamevalval,exdays){
            document.cookie = cname + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.cookie = cnameval + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.cookie = cdate + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            //var expires = "expires="+ d.toUTCString();
            var expires = '';
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            document.cookie = cnameval + "=" + cnamevalval + ";" + expires + ";path=/";
            document.cookie = cdate + "=" + cdvalue + ";" + expires + ";path=/";
        },
        trigger_city_popup:function(){
            var i;
            var html = '<div class="city-popup-main">\
            <div class="city-popup-inner">\
            <div class="city-popup-head text-center"><span class="fig-bloom-text">Where & when</span><span class="fig-bloom-text">do you want deliver?</span></div>\
            <div class="city-popup-links columns medium-12 pop-col-12">\
            <label class="suburb_lbl text-left">delivery Suburb</label><span class="suburb_help">?</span>\
            <input type="text" class="suburb_city" placeholder="Start typing a suburb"/><p class="suburb_helptext">Choose a suburb in our delivery area</p>\
            <div class="right-box">\
            <div class="submit-box">\
            <div class="submit-icon">\
            <span><i class="far fa-check"></i></span>\
            </div>\
            <div class="submit-text">\
            <h5>YES!</h5>\
            <p>We deliver to <span class="success-city"></span></p>\
            </div>\
            </div>\
            </div>\
            <div class="close-box">\
            <div class="submit-box">\
            <div class="submit-icon">\
            <span><i class="far fa-times"></i></span>\
            </div>\
            <div class="submit-text">\
            <h5>SORRY!</h5>\
            <p>We don\'t deliver to <span class="failure-city"></span></p>\
            </div>\
            </div>\
            </div>\
            </div>\
            <div class="date-popup-links columns medium-12 pop-col-12">\
            <label class="date_lbl text-center">delivery Date</label><span class="date_help">?</span>\
            <div class="date_select1"></div>\
            <p class="date_helptext">Choose from available delivery dates.</p>\
            <div class="right-box">\
            <div class="submit-box">\
            <div class="submit-icon">\
            <span><i class="far fa-check"></i></span>\
            </div>\
            <div class="submit-text">\
            <h5>YES!</h5>\
            <p>We can deliver on <span class="success-date"></span></p>\
            </div>\
            </div>\
            </div>\
            <div class="close-box">\
            <div class="submit-box">\
            <div class="submit-icon">\
            <span><i class="far fa-times"></i></span>\
            </div>\
            <div class="submit-text">\
            <h5>SORRY!</h5>\
            <p>We\'re fully blocked <span class="failure-date"></span>.<br>Please chose another date.</p>\
            </div>\
            </div>\
            </div>\
            </div>\
            <span class="close-sec"><a href="javascript:void(0)"" class="close-popup" title="Close this box">Done</a></span>\
            </div>';

            var defaults = {
                keyboard: false,
                smallBtn: false,
                buttons: [],
                trapFocus: false,
                clickSlide: false,
                clickOutside: false,
                dblclickContent: false,
                dblclickSlide: false,
                dblclickOutside: false,
                touch: {
                    vertical: false,
                    momentum: false
                },
                mobile: {
                    preventCaptionOverlap: false,
                    idleTime: false,
                    clickSlide: function(current, event) {
                        return current.type === "image" ? "toggleControls" : false;
                    },
                },
            };
            $.fancybox.open(html,defaults);
        }
    };
    wc_city.init();
});
jQuery(document).ready(function() {
    jQuery(document).on("change",".suburb_city",function() {
        setTimeout(function(){
            var suburb_city = jQuery('.suburb_city').val();
            var suburb_postcode = jQuery('#postal_code').val();
            var mpostcodeList = [
                /***************************
                 *
                 *        MELBOURNE
                 *
                 ***************************/

                // VIC 0KM
                3101,

                // VIC 0-10KM
                3102, 3122, 3121, 3067, 3121,
                3078, 3103, 3104, 3066, 3103,
                3144, 3126, 3078, 3065, 3142,
                3124, 3068, 3079, 3079, 3002,
                3144, 3000, 3141, 3146,
                3143, 3126, 3127, 3084, 3070,
                3068, 3053, 3129, 3128, 3071,
                3084, 3181, 3052, 3081, 3006,
                3123,

                // VIC 11-20KM
                3072, 3081, 3081, 3054, 3183,
                3054, 3004, 3145, 3161, 3181,
                3205, 3183, 3185, 3003, 3128,
                3006, 3084, 3145, 3050, 3051,
                3072, 3105, 3057,
                3162, 3147, 3031, 3125, 3206,
                3182, 3031, 3162, 3182, 3107,
                3085, 3073, 3130, 3008, 3163,
                3084, 3055, 3108, 3032, 3187,
                3184, 3204, 3206, 3207,
                3085, 3147, 3186, 3056, 3204,
                3032, 3058, 3083, 3129, 3163,
                3131, 3163, 3058, 3087, 3058,
                3150, 3151, 3039, 3044, 3087,
                3074, 3088, 3106, 3109, 3148,
                3083, 3188, 3012, 3166, 3094,
                3204, 3088, 3166, 3012,

                // VIC 21-30KM
                3044, 3032, 3166, 3130, 3040,
                3093, 3015, 3040, 3130, 3189,
                3188, 3041, 3166, 3167, 3040,
                3011, 3191, 3011, 3111, 3015,
                3013, 3132, 3046, 3060, 3046,
                3015, 3075, 3019, 3131,
                3193, 3095, 3034, 3012, 3190,
                3168, 3149, 3134, 3041, 3046,
                3082, 3016, 3041, 3025, 3016,
                3170, 3012, 3012, 3042, 3169,
                3165, 3095, 3135, 3168, 3088,
                3076, 3047, 3134, 3133, 3193,
                3033, 3042, 3026, 3047, 3202,
                3195, 3043, 3048, 3061, 3042,
                3018, 3136, 3150, 3090]

                /****************************
                 *
                 *         SYDNEY
                 *
                 ****************************/

                // NSW 0 mins
                var spostcodeList = [2015,

                // NSW 0-20 mins
                2043, 2017, 2042, 2044, 2008,
                2016, 2018, 2204, 2006, 2010,
                2020, 2033, 2048, 2050, 2052,

                // NSW 20-25 mins
                2007, 2021, 2037, 2000, 2032,
                2038, 2049, 2009, 2011, 2019,
                2031, 2040, 2203, 2205, 2025,
                2027, 2028, 2034, 2035, 2130,
                2193, 2216, 2061, 2206, 2062,
                2208, 2055, 2064, 2001,

                // NSW 25-30 mins
                2022, 2023, 2024, 2026, 2029,
                2036, 2039, 2041, 2045, 2046,
                2047, 2060, 2063, 2065, 2066,
                2067, 2068, 2069, 2089, 2090,
                2110, 2113, 2131, 2132, 2133,
                2134, 2136, 2191, 2192, 2194,
                2195, 2196, 2200, 2207, 2209,
                2212, 2217, 2218, 2219, 2220,
                2221, 2222, 2223,

                // NSW 30-40 mins
                2030, 2070, 2071, 2072, 2073,
                2086, 2087, 2088, 2092, 2093,
                2094, 2095, 2111, 2112, 2114,
                2115, 2118, 2122, 2127, 2128,
                2135, 2137, 2138, 2140, 2141,
                2143, 2144, 2162, 2163, 2190,
                2199, 2210, 2211, 2213, 2214,
                2224, 2225, 2226, 2227, 2228,
                2229, 2230, 2232

            ];
            for (var i = 0; i < mpostcodeList.length; i++) {
                if (mpostcodeList[i] === suburb_postcode) {
                  mpostcodeIsValid = true;
                }
            }
            for (var i = 0; i < spostcodeList.length; i++) {
                if (spostcodeList[i] === suburb_postcode) {
                  spostcodeIsValid = true;
                }
            }
            if(suburb_city==''){
                jQuery('.city-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').hide();
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-main .close-sec').hide();
                jQuery('.date_select1').html('&nbsp;');
            }
            else if(mpostcodeIsValid==true){
                var cus_sectbox=jQuery('.melborne_date_selection').html();
                jQuery('.date_select1').html(cus_sectbox);
                jQuery('.city-popup-links .right-box .success-city').html(suburb_city);
                jQuery('.city-popup-links .close-box').hide();
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').show();
                jQuery('.city-popup-main .close-sec').hide();
            }else if(spostcodeIsValid==true){
                var cus_sectbox=jQuery('.sydney_date_selection').html();
                jQuery('.date_select1').html(cus_sectbox);
                jQuery('.city-popup-links .right-box .success-city').html(suburb_city);
                jQuery('.city-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').show();
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-main .close-sec').hide();
            }else{
                jQuery('.date_select1').html('&nbsp');
                jQuery('.city-popup-links .right-box').hide();
                jQuery('.city-popup-links .close-box').show();
                jQuery('.city-popup-links .close-box .failure-city').html(suburb_city);
                jQuery('.city-popup-main .close-sec').hide();
            }
        }, 500);
    });
    jQuery(document).on("click",".date_select1 .drop-down .selected a",function() {
        jQuery(".date_select1 .drop-down .options ul").toggle();
    });

    //SELECT OPTIONS AND HIDE OPTION AFTER SELECTION
    jQuery(document).on("click",".date-popup-links .date_select1 .drop-down .options ul li a",function() {
        var selected_date = jQuery(this).children('.date').html();
        var disabled = jQuery(this).children('.value').hasClass('disable');
        jQuery('.date-popup-links .date_select1 .drop-down .options ul li').removeClass('active');
        jQuery('.date-popup-links .date_select1 .drop-down .selected a').html(selected_date);
        jQuery(this).parent().addClass('active');
        jQuery('.date-popup-links .date_select1 .drop-down .options ul').hide();
        if(disabled){
            jQuery('.date-popup-links .right-box').hide();
            jQuery('.date-popup-links .close-box').show();
            jQuery('.date-popup-links .close-box .failure-date').html(selected_date);
            jQuery('.city-popup-main .close-sec').hide();
        }else{
            jQuery('.date-popup-links .right-box .success-date').html(selected_date);
            jQuery('.date-popup-links .close-box').hide();
            jQuery('.date-popup-links .right-box').show();
            jQuery('.city-popup-main .close-sec').show();
        }
    });

    //HIDE OPTIONS IF CLICKED ANYWHERE ELSE ON PAGE
    jQuery('.date-popup-links .date_select1 a').bind('click', function(e) {
        var $clicked = jQuery(e.target);
        if (! $clicked.parents().hasClass("drop-down"))
            jQuery(".date_select1 .drop-down .options ul").hide();
    });
    jQuery(document).on("click",".suburb_help",function() {
        jQuery('.suburb_helptext').toggle();
    });
    jQuery(document).on("click",".date_help",function() {
        jQuery('.date_helptext').toggle();
    });
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
    var wcity = getCookie("wooexp_city_val");
    var wdate = getCookie("wooexp_date");
    if(wcity != '' && wdate != ''){
        setTimeout(function(){  
            jQuery('.sticky_city').show();
            jQuery('.samedaydelivery').css('bottom','38px' );
            jQuery('.sticky_city .city').html(wcity);
            jQuery('.sticky_city .date').html(wdate);
        }, 500);
    }
});