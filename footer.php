<i class="far fa-check" style="font-size:0px;"></i>
<?php

	$footer_style   = ot_get_option( 'footer_style', 'style1' );
	$disable_footer = get_post_meta( get_the_ID(), 'disable_footer', true );
	$onepage        = ( ot_get_option( 'footer', 'on' ) !== 'off' && $disable_footer !== 'on' ) && is_page_template( 'template-snap.php' );
?>
		</div><!-- End role["main"] -->

		<!-- Start Quick Shop -->
		<?php do_action( 'thb_quick_shop' ); ?>
		<!-- End Quick Shop -->

		<?php if ( $onepage ) { ?>
			<div class="footer-container wpb_row fp-auto-height" id="fp-footer">
		<?php } ?>
		<?php
		if ( 'on' === ot_get_option( 'footer', 'on' ) && 'on' !== $disable_footer ) {
			get_template_part( 'inc/templates/footer/footer-' . $footer_style );
		}
		?>
		<?php if ( $onepage ) { ?>
			</div> 
		<?php } ?>
	<?php do_action( 'thb_wrapper_end' ); ?>
</div> <!-- End #wrapper -->

<?php 
global  $woocommerce;

$methods  = WC_Shipping_Zones::get_zones();
$zonesArr = $wpdb->get_results("SELECT * FROM $wpdb->tax_rate_locations");

if(!empty($methods)):
	$zipcodes = array();
	foreach($methods as $method):
		if(!empty($method['zone_locations'])):
			if($method['zone_locations'][0]->code=="AU:NSW"):
				$keysubrb = "NSW";
			else:
				$keysubrb = "VIC";
			endif;
			foreach($method['zone_locations'] as  $zonelocation):
				if($zonelocation->type=="postcode"):
					 $zipcodes[$keysubrb][] = $zonelocation->code;
				endif;
			endforeach;
		endif;
	endforeach;
endif;
?>
<script type="text/javascript">
	
		var citiesjson = <?php echo json_encode($zipcodes); ?>;
	
</script>
<div class="sticky_city" <?php if($_COOKIE['wooexp_city_val']!=""): ?> style="display: -webkit-flex; display: flex;" <?php endif; ?>>

	Delivery to <span class="city"><?php echo str_replace(" ,", ",", str_replace(array("-"," -"), ",", $_COOKIE['wooexp_city_newsf'])); ?></span> on <span class="date"><?php echo str_replace("-", ",", $_COOKIE['wooexp_date_newsf']); ?></span>.</div>
	<?php date_default_timezone_set('Australia/Melbourne'); ?>
	<input type="hidden" class="date_current_hour_sf" value="<?php echo date('H'); ?>">
<div id="postal_code"></div>	
<?php 
	$melborne_slot=get_option('melborne_slot');
	$sydney_slot=get_option('sydney_slot');
	
	$special_slots=get_option('special_slot');
	$sydney_slots=$melborne_slots=array();
	$days_array = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
	for ($i = 0; $i < 120; $i++){
		date_default_timezone_set('Australia/Melbourne');
		$dates = date("l, d M", strtotime(now."+ $i days"));
		$weekDayname = date("N", strtotime(now."+ $i days"))-1;
		$syd_slot=(int)$sydney_slot[$weekDayname];
		$sydney_slots[$dates]=$syd_slot;
		$mel_slot=(int)$melborne_slot[$weekDayname];
		$melborne_slots[$dates]=$mel_slot;
	}

	foreach ($special_slots as $special_slot) {
		$slot=0;
		date_default_timezone_set('Australia/Sydney');
		$todaydate=date("Y-m-d", strtotime("now"));
		if ($todaydate <= $special_slot['date']) {
			if($special_slot['locaton']=='sydney'){
				$datestr=$special_slot['date'];
				$newDate = date("l, d M", strtotime($datestr));
				if($sydney_slots[$newDate]){
					$slot += (int)$special_slot['slot'];
				} else{
					$slot = (int)$special_slot['slot'];
				}
				$sydney_slots[$newDate]=$slot;
			} else {
				$datestr=$special_slot['date'];	
				$newDate = date("l, d M", strtotime($datestr));
				if($melborne_slots[$newDate]){
					$slot += (int)$special_slot['slot'];
				} else{
					$slot = (int)$special_slot['slot'];
				}
				$melborne_slots[$newDate]=$slot;
			}
		}
	}

	$usedSlotes=get_total_slots_per_day();



	$VICslotes=$usedSlotes['VIC'];
	$NSWslotes=$usedSlotes['NSW'];
	

	foreach ($NSWslotes as $key => $value) 
	{   

		$keycheck = date("l, d M",strtotime($key));
	    if(array_key_exists($key, $NSWslotes) && array_key_exists($keycheck	, $sydney_slots))
	    {  
	        $sydney_slots[$keycheck] = $sydney_slots[$keycheck] - $NSWslotes[$key];
	    }
	} 

	foreach ($VICslotes as $key => $value) 
	{   
		$keycheck = date("l, d M",strtotime($key));
	    if(array_key_exists($key, $VICslotes) && array_key_exists($keycheck, $melborne_slots))
	    {  
	    	$melborne_slots[$keycheck] = $melborne_slots[$keycheck] - $VICslotes[$key];	        
	    }
	    
	}
		
	date_default_timezone_set('Australia/Melbourne');
	$currentTime = time();
	
	if (((int) date('H', $currentTime)) >= 13) {
		array_shift($melborne_slots);
	}
	date_default_timezone_set('Australia/Sydney');
	$currentTime1 = time();
	if (((int) date('H', $currentTime1)) >= 13) {
		array_shift($sydney_slots); 
	}

	$melborneSlotv=get_option('melborne_slot');
	$sydneySlotv=get_option('sydney_slot');


	echo '<div class="sydney_date_selection">
			<div class="drop-down">
			  <div class="selected">
			    <a href="javascript:void(0)"><span>Click to see available dates</span></a>
			  </div>
			  <div class="options">
			    <ul>';
			    foreach($sydney_slots as $key => $sydney_slot){
			    	$year=  date("-Y");
			    	if(date("l, d M")==$key && date('H') > 12):
			    		continue;
			    	endif;
			    	if($sydneySlotv[(date("N",strtotime($key))-1)] > 0):
			    		if($sydney_slot==0 || $sydney_slot < 0){
				    		//echo '<li class="disable"><a href="javascript:void(0)"><span class="date">'.$key.'</span><span class="value disable">('.$sydney_slot.' spots remaining)</span></a></li>';	
				    		echo '<li class="disable sold_out">'.$key.'</span><span class="value disable"><span>Sold Out</span></span></li>';	
				    	}else{
				    		if($sydney_slot<=10):
				    			echo '<li class="remaining"><a href="javascript:void(0)"><span class="date">'.$key.'</span><span class="value">('.$sydney_slot.' spots remaining)</span></a></li>';	
				    		else:
				    			echo '<li class="available"><a href="javascript:void(0)"><span class="date">'.$key.'</span><span class="value"><span>Available</span></span></a></li>';	
				    		endif;
				    	}	
			    	else:
			    		if($sydney_slot>0):
			    			if($sydney_slot<=10):
				    			echo '<li class="remaining"><a href="javascript:void(0)"><span class="date">'.$key.'</span><span class="value">('.$sydney_slot.' spots remaining)</span></a></li>';	
				    		else:
				    			echo '<li class="available"><a href="javascript:void(0)"><span class="date">'.$key.'</span><span class="value"><span>Available</span></span></a></li>';	
				    		endif;
				    	else:
				    		echo '<li class="disable closed"><span class="date">'.$key.'</span><span class="value disable"><span>Closed</span></span></li>';
				    	endif;
			    	endif;
			    	
			    }
			    echo '</ul>
			  </div>
			</div>
		</div>';
		echo '<div class="melborne_date_selection">
			<div class="drop-down">
			  <div class="selected">
			    <a href="javascript:void(0)"><span>Click to see available dates</span></a>
			  </div>
			  <div class="options">
			    <ul>';
			    foreach($melborne_slots as $mkey => $melborne_slot){
			    	$year=  date("-Y");
					if(date("l, d M")==$key && date('H') > 12):
						continue;
					endif;
			    	if($melborneSlotv[(date("N",strtotime($mkey))-1)] > 0):
			    		if($melborne_slot==0 || $melborne_slot < 0){
							//echo '<li class="disable"><a href="javascript:void(0)"><span class="date">'.$mkey.'</span><span class="value disable">('.$melborne_slot.' spots remaining)</span></a></li>';	
							echo '<li class="disable sold_out">'.$mkey.'</span><span class="value disable"><span>Sold Out</span></span></li>';	
				    	}else{
				    		if($melborne_slot<=10):
				    			echo '<li class="remaining"><a href="javascript:void(0)"><span class="date">'.$mkey.'</span><span class="value">('.$melborne_slot.' spots remaining)</span></a></li>';	
				    		else:
								echo '<li class="available" ><a href="javascript:void(0)"><span class="date">'.$mkey.'</span><span class="value"><span >Available</span></span></a></li>';	
				    		endif;
				    	}
				    else:
				    	if($melborne_slot > 0):
				    		if($melborne_slot<=10):
				    			echo '<li class="remaining"><a href="javascript:void(0)"><span class="date">'.$mkey.'</span><span class="value">('.$melborne_slot.' spots remaining)</span></a></li>';	
				    		else:
								echo '<li class="available" ><a href="javascript:void(0)"><span class="date">'.$mkey.'</span><span class="value"><span >Available</span></span></a></li>';	
				    		endif;
				    	else:
				    		echo '<li class="disable closed"><span class="date">'.$mkey.'</span><span class="value"><span>Closed</span></span></li>';
				    	endif;
			    	endif;
			    	
			    }
			    echo '</ul>
			  </div>
			</div>
		</div>';

	/*
	 * Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */
?>

<script>
	$siteUrl = "<?php echo site_url(); ?>";
</script>
<?php
	wp_footer();
?>
</body>
</html>
