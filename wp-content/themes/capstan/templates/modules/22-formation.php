<?php 
	$officeId = get_field('office');

	$queryFormations = new WP_Query(
		array(
	    	'post_type' 		=> 'formation',
			'orderby' 			=> 'rand',
	    	'posts_per_page' 	=> 3,
	    	'meta_query' => Array(
	    		Array(
	    			'key' => 'office',
	    			'value' => $officeId
	    		)
	    	)
		)
	);

	$now = new DateTime('NOW');
	$formations = Array();

	while($queryFormations->have_posts()) {
		$queryFormations->the_post();

		$postID = get_the_ID();

		$date = get_field('dates', $postID);
		$first = DateTime::createFromFormat("d/m/Y", trim($date[0]['day']));

		if(intval($first->diff($now)->format('%R%a')) <= 0) {
			$formations[] = Array(
				'ID' 			=> $postID,
				'title' 		=> get_field('title', $postID),
				'description'  	=> get_field('description', $postID),
				'office'  		=> get_field('office', $postID),
				'type'  		=> get_field('type', $postID),
				'dates'  		=> get_field('dates', $postID),
				'url' 			=> get_permalink($postID)
			);
		}
	}
	usort($formations, function ($a, $b) {
        return $a['firstdate'] > $b['firstdate'];
    });
	wp_reset_postdata();
	
	if(count($formations) > 0) {
?>
<div class="module module-22 <?php echo get_field('background') == 'dark' ? 'black' : 'white'; ?>" data-component="Formation22Component" data-background="none">

	
	<div class="title" data-animation="titleReveal"><?php _e('Les formations disponibles à', 'capstan'); ?> <i><?php the_field('city', $officeId) ?>.</i></div>
	
	<div class="columns">
		<?php 
		foreach($formations as $formation) { ?>
		<div class="item">
			<?php $datetime = DateTime::createFromFormat("d/m/Y", trim($formation['dates'][0]['day'])); ?>
			<div class="date" data-animation="titleReveal"><?php echo $formation['type'] == 'multi' ? __('Formation récurrente') : french_date(strftime("%d %B %Y", $datetime->format('U'))) ?></div>
			<div class="subtitle"><?php echo $formation['title']; ?></div>
			<div class="description"><?php echo $formation['description']; ?></div>

			<div class="infos">
				<div class="info"><span class="name">Bureau</span><span class="value"><?php the_field('city', $formation['office']); ?></span></div>
				<?php if($formation['type'] == 'multi') { ?>
					<div class="hours">
						<div class="info">
							<span class="name">Horaires</span>
							<span class="value"><?php echo count($formation['dates']) ?> <?php _e('dates disponibles', 'capstan'); ?></span>
							<i class="icon-arrow-bottom"></i>
						</div>
						<div class="all-dates">
							<?php foreach ($formation['dates'] as $date) {
								$datetime = DateTime::createFromFormat("d/m/Y", trim($date['day'])); ?>
								<div class="single-date">
									<span class="left"><?php echo french_date(strftime("%A %d %B %Y", $datetime->format('U')));?></span>
									<span class="right"><?php echo $date['hour_start'] ?> - <?php echo $date['hour_end'] ?></span>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } else { ?>
					<div class="info"><span class="name"><?php _e('Horaires', 'capstan'); ?></span><span class="value"><?php _e('de', 'capstan'); ?> <?php echo $formation['dates'][0]['hour_start'] ?> <?php _e('à', 'capstan'); ?><?php echo $formation['dates'][0]['hour_end'] ?></span></div>
				<?php } ?>
			</div>
			
			<a href="<?php echo $formation['url'] ?>" class="link-top"><?php _e('En savoir plus', 'capstan'); ?> <i class="icon-arrow-top"></i></a>
		</div>
		<?php } ?>
	</div>

</div><?php } ?>