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
            var wcity = getCookie("wooexp_city_val");
            var wdate = getCookie("wooexp_date");
    
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
            if(suburb_city==''){
                jQuery('.city-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').hide();
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-main .close-sec').hide();
                jQuery('.date_select1').html('&nbsp;');
            }
            else if(suburb_city.includes("VIC, Australia")){
                var cus_sectbox=jQuery('.melborne_date_selection').html();
                jQuery('.date_select1').html(cus_sectbox);
                jQuery('.city-popup-links .right-box .success-city').html(suburb_city);
                jQuery('.city-popup-links .close-box').hide();
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').show();
                jQuery('.city-popup-main .close-sec').hide();
            }else if(suburb_city.includes("NSW, Australia")){
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