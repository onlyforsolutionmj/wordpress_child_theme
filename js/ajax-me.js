
jQuery(document).ready(function($){
    $(document).on("keyup",".suburb_city",function(){
		if( this.value.length < 3 ){ $("#cities_dropdown").hide(); return;} 
      var data = {
        'action': 'get_suburb',
        'suburb': $(".suburb_city").val()
      };
		
		// jQuery.ajax({
  //           type: "POST",
  //           dataType: "html",
  //           url: frontendajax.ajaxurl,
  //           data: data,
		// 	//start_time: new Date().getTime(),
  //           success: function (data) {
		// 	//	alert('This request took '+(new Date().getTime() - this.start_time)+' ms');
  //              $("#cities_dropdown").show();
  //       $("#cities_dropdown").html(data);
  //           },
  //           error: function (jqXHR, textStatus, errorThrown) {
  //               alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
  //           }
  
  //       });
  if($(".suburb_city").val()==""){
          $("#cities_dropdown").hide();
  }
      // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post($siteUrl+"/ajaxse.php", data, function(response) {
  		 // alert(response)
          $("#cities_dropdown").show();
          $("#cities_dropdown").html(response);
        });
    });

    $(document).on("click",".cities_link",function(){
      $(".suburb_city").val($(this).html());
      setcity($(this).data('postcode'));
      $("#cities_dropdown").hide();
    })
});

function setcity(zipcode){


jQuery("#cities_dropdown").hide();
            var suburb_city = jQuery('.suburb_city').val();
            var suburb_postcode = zipcode;
            document.cookie = 'postcode=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            var d = new Date();
            d.setTime(d.getTime() + (24*60*60*1000));
            var expires = '';
            document.cookie = "postcode =" + zipcode + ";" + expires + ";path=/";
            //var suburb_postcode = jQuery('#postal_code').val();
            var mpostcodeList=spostcodeList =false;
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
            var suburb_city = jQuery('.suburb_city').val();
            if(suburb_city==''){ 
                jQuery('.city-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').hide();
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-main .close-sec').hide();
                jQuery('.date_select1').html('&nbsp;');
            }
            else if(mpostcodeIsValid == '1'){
                var cus_sectbox=jQuery('.melborne_date_selection').html();
                jQuery('.date-popup-links .date_lbl,.date-popup-links .date_help').show();
                jQuery('.date_select1').html(cus_sectbox);
                jQuery('.city-popup-links .right-box .success-city').html(suburb_city);
                jQuery('.city-popup-links .close-box').hide();
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').show();
                jQuery('.city-popup-main .close-sec').hide();
            }
            else if(mpostcodeIsValid == '2'){
                var cus_sectbox=jQuery('.sydney_date_selection').html();
                jQuery('.date-popup-links .date_lbl,.date-popup-links .date_help').show();
                jQuery('.date_select1').html(cus_sectbox);
                jQuery('.city-popup-links .right-box .success-city').html(suburb_city);
                jQuery('.city-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').show();
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-main .close-sec').hide();
            }else{
                jQuery('.date_select1').html('&nbsp');
                jQuery('.date-popup-links .right-box').hide();
                jQuery('.date-popup-links .close-box').hide();
                jQuery('.city-popup-links .right-box').hide();
                jQuery('.city-popup-links .close-box').show();
                jQuery('.city-popup-links .close-box .failure-city').html(suburb_city);
                jQuery('.city-popup-main .close-sec').hide();
            }
    jQuery("#cities_dropdown").hide();
}
function readCookie(name){
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}