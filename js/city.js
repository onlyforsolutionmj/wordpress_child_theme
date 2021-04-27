jQuery(function($){
    $(document).ready(function(){
       
   var wc_city = {
        init: function () {
            $(document).on('click','.close-sec',{view:this},this.redirect_city);
            // $(document).on("click",'.sticky_city',{view:this},this.trigger_city_popup);
            $(document).on("click",'.sticky_city, .sticky_city_menu',function()
            {

                $('.fancybox-container').addClass('fancybox-is-open');
                $('body').addClass('fancybox-active');
                $('body').addClass('compensate-for-scrollbar');
                $('.fancybox-container').show();
                var scity = $('.sticky_city .city').html();
                $('.suburb_city').val(scity);
                $('.city-popup-links .right-box .success-city').html(scity);
                $('.city-popup-links .right-box').show();
                var suburb_postcode = readCookiem('postcode');

                //var suburb_postcode = jQuery('#postal_code').val();
                var mpostcodeList=spostcodeList =false;
                /***************************
                     *
                     *        MELBOURNE
                     *
                     ***************************/
                var  mpostcodeList= citiesjson.VIC;

                    /****************************
                     *
                     *         SYDNEY
                     *
                     ****************************/

                    // NSW 0 mins
                    var spostcodeList = citiesjson.NSW;

                mpostcodeIsValid='0';

                for (var i = 0; i < mpostcodeList.length; i++) {
                    if (mpostcodeList[i] == suburb_postcode) {
                      mpostcodeIsValid = '1';
                    }
                }
                for (var i = 0; i < spostcodeList.length; i++) {
                    if (spostcodeList[i] == suburb_postcode) {
                      mpostcodeIsValid = '2';
                    }
                }
                if(mpostcodeIsValid == '1'){
                    var cus_sectbox=$('.melborne_date_selection').html();
                }
                else if(mpostcodeIsValid == '2'){
                    var cus_sectbox=$('.sydney_date_selection').html();
                }
                var sdate = $('.sticky_city .date').html();
                var cus_sectbox =cus_sectbox.replace('<a href="javascript:void(0)"><span>Click to see available dates</span></a>','<a href="javascript:void(0)">'+sdate+'</a>');
                var cus_sectbox =cus_sectbox.replace('<li><a href="javascript:void(0)"><span class="date">'+sdate,'<li class="active"><a href="javascript:void(0)"><span class="date">'+sdate);
                $('.date-popup-links .date_lbl, .date-popup-links .date_help').show();
                $('.date_select1').html(cus_sectbox);
                $('.date-popup-links .right-box').show();
                $('.date-popup-links .right-box .success-date').html(sdate);
                $('.close-sec').show();

            });
            wc_city.check_city();
        },
        redirect_city:function(){
            var city_val = $('.suburb_city').val();
            if(city_val.includes("VIC, Australia")) {
                var city = 'melbourne';
            } else if(city_val.includes("NSW, Australia")) {
                var city = 'sydney';
            } else{
                var city='';
            }

            if(city){
                var date = jQuery('.date_select1 .drop-down .selected a').html();
                var updae_newsfa =  date.replace(/,/g, "-");
                var updae_citysfa =  city_val.replace(/,/g, "-");
                wc_city.update_data('wooexp_city',city,'wooexp_date',date,'wooexp_city_val',city_val,1);
                var expires = "";
                document.cookie = "wooexp_date_newsf" + "=" + updae_newsfa + ";" + expires + ";path=/";
                document.cookie = "wooexp_city_newsf" + "=" + updae_citysfa + ";" + expires + ";path=/";
                var url = window.location.href;
                if(wooexperts.is_front_page==='no'){
                    //url = wc_city.set_param_val(url,'pa_city',city);
                }
                if(url.indexOf('pa_city') !== -1){
                    //url = wc_city.set_param_val(url,'pa_city',city);
                }
                window.location.href = url;
            } else{
                $('.close-sec').hide();
                $('.city-popup-links .right-box').hide();
                $('.city-popup-links .close-box').show();
                $('.date-popup-links .right-box').hide();
                $('.date-popup-links .close-box').hide();
                $('.date-popup-links .date_lbl,.date-popup-links .date_help').hide();
                $('.date_select1').html('&nbsp;');
            }

        },
        check_city:function(){
            var city = wc_city.get_data('wooexp_city');
            var date = wc_city.get_data('wooexp_date');
            if((city==='' && wooexperts.is_product_page==='1')
                || (city==='' && wooexperts.is_tax_page==='yes')
                || (city==='' && wooexperts.is_front_page==='yes')
                || (city==='' && wooexperts.is_popup_page==='yes')
            ){
                wc_city.trigger_city_popup();
            }else if(city !='')
            {
                 wc_city.trigger_city_popup();
                 $('.fancybox-container').hide();
                 setTimeout(function(){
                    $('.fancybox-container').removeClass('fancybox-is-open');
                    $('body').removeClass('compensate-for-scrollbar');
                    $('body').removeClass('fancybox-active');
                  },100);
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
        get_data:function(cname){
            var name = cname + "=";
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
            <div class="city-popup-head text-center"><span class="fig-bloom-text">Where & when</span><span class="fig-bloom-text">do you want delivery?</span></div>\
            <div class="city-popup-links columns medium-12 pop-col-12">\
            <label class="suburb_lbl text-left">delivery Suburb/Postcode<span class="suburb_help">?</span></label>\
            <input type="text" class="suburb_city" placeholder="Start typing a suburb/postcode"/><p class="suburb_helptext">Choose a suburb in our delivery area</p>\
            <ul id="cities_dropdown" style="display:none"></ul>\
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
            <label class="date_lbl text-center">delivery Date<span class="date_help">?</span></label>\
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

            // var html = jQuery('.city_popup_wwt').html();
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

jQuery(document).ready(function()
{
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
    //var wcity = getCookie("wooexp_city_val");
    var wcity = getCookie("wooexp_city_newsf");
    //var wdate = getCookie("wooexp_date");
    var wdate = getCookie("wooexp_date_newsf");
    

    if(wcity != '' && wdate != ''){
        var cityval = wcity.split("-");
        setTimeout(function(){
            jQuery('.sticky_city').css('display','flex' );
            jQuery('.samedaydelivery').css('bottom','38px' );
            jQuery("#shipping_city").val(cityval[0]);
            jQuery('.sticky_city .city').html(wcity.replace(/-/g, ",").replace(" ,",","));
            jQuery('.sticky_city .date').html(wdate.replace(/-/g, ","));
        }, 500);
    }
    });

});


function readCookiem(name){
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}


jQuery(document).on("click",".suburb_help",function() {
    jQuery('.suburb_helptext').toggle();
});
jQuery(document).on("click",".date_help",function() {
    jQuery('.date_helptext').toggle();
});
