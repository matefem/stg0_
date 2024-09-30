<footer>
		
	<?php


	    $locations = get_nav_menu_locations();

	    if(!empty($locations['footer']))
	    	$apropos = wp_get_nav_menu_items($locations['footer']);

	    if(!empty($locations['shares']))
	    	$socials = wp_get_nav_menu_items($locations['shares']);

	    if(!empty($locations['terms']))
	    	$terms = wp_get_nav_menu_items($locations['terms']);

		$query = new WP_Query(array(
			'post_type' => 'office',
			'post_status' => 'publish',
			'posts_per_page' => -1
		));

		$offices = Array();

		while($query->have_posts()) {
			$query->the_post();

			$postID = get_the_ID();

			 $offices[] = Array(
				'title' => get_field('title', $postID)
			); 
		}
		wp_reset_postdata();
	?>
	<div class="group">
		<div class="logo">
			<i class="icon-icon"></i>
		</div>
		<div class="catch"><?php _e('200 avocats experts en droit social, créateurs de solutions.', 'capstan'); ?></div>
	</div>

	<div class="sub">
		
		<div class="title"><?php _e('Nos bureaux78', 'capstan'); ?></div>

		<ul>
			<?php 
			while($query->have_posts()) {
				$query->the_post();
				$postID = get_the_ID(); ?>

				<li><a href="<?php echo get_permalink($postID); ?>"><?php echo strip_tags(get_field('title', $postID)) ?></a></li>
			<?php } ?>
		</ul>

	</div>

	<?php
	if(!empty($apropos)) { ?>
	<div class="sub sub2">
		
		<div class="title"><?php _e('A propos', 'capstan'); ?></div>

		<ul>
			<?php
			foreach($apropos as $item) { ?>
				<li><a href="<?php echo $item->url ?>"><?php echo $item->title ?></a></li>
			<?php } ?>
		</ul>

	</div>
	<?php } 

	if(!empty($terms)) { ?>
		<ul class="annexes">
			<li>&copy; <?php _e('2020 Capstan Copyrights, All Rights Reserved', 'capstan'); ?></li>
			<?php
			foreach($terms as $item) { ?>
				<li><a href="<?php echo $item->url ?>"><?php echo $item->title ?></a></li>
			<?php } ?>
		</ul>
	<?php } ?>

	<ul class="socials">
	<?php if(!empty($socials)) { ?>
		<li class="lang"><a data-method="noajax" href="/">Fr</a></li>
		<li class="lang"><a data-method="noajax" href="/en">En</a></li>
		<?php
		foreach($socials as $item) { ?>
			<li><a href="<?php echo $item->url ?>"><?php echo $item->title ?></a></li>
		<?php } ?>
	<?php } ?>
	</ul>

</footer>