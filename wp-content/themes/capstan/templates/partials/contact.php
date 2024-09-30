<?php $locations = get_nav_menu_locations();

    if(!empty($locations['conctact_us']))
    	$contactus = wp_get_nav_menu_items($locations['conctact_us']);
    
	if(!empty($contactus) && count($contactus) > 0 && $pagename != "contact" && $pagename != "carrieres") { ?>
		<div class="overflow-h contact-us-chat contact-us-small">
			<a href="<?php echo $contactus[0]->url; ?>" class="inner">
				<i class="icon-chat"></i>
				<span class="text"><?php echo $contactus[0]->title; ?></span>
			</a>
		</div>
	<?php } ?>