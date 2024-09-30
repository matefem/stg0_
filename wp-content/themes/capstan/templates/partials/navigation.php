<div class="navigation" data-component="NavigationComponent">
	<?php 
	    $locations = get_nav_menu_locations();

	    if(!empty($locations['conctact_us']))
	    	$contactus = wp_get_nav_menu_items($locations['conctact_us']);
	?>
	<div class="dots">
		<span class="info">1/04</span>
	</div>

	<div class="arrows">
		<div class="menu">
			<div>
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>
		<?php 
		if(!empty($contactus) && count($contactus) > 0) { ?>
		<div class="overflow-h contact-us">
			<a href="<?php echo $contactus[0]->url; ?>" class="inner"><i class="icon-chat"></i><?php echo $contactus[0]->title; ?></a>
		</div>
	<?php } ?>
	</div>

</div>