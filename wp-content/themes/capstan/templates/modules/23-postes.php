<?php
		$query = Array(
			'post_type' => 'career',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'meta_query' => [
				[
					'key' => 'place',
					'value' => get_field('office')
				]
			]
		);

		$wpQuery = new WP_Query($query);

		if(count($wpQuery->posts) > 0) {
	?>
<div class="module module-23" data-background="none">

	
	<div class="head">
		<div class="title"><?php _e('Nos postes ouverts à', 'capstan'); ?> <i><?php the_field('city', get_field('office')) ?></i>.</div>
	</div>
	
	<div class="columns">

		<table>
			<tr>
				<th><?php _e('Postes', 'capstan'); ?></th>
				<th><?php _e('Type de contrat', 'capstan'); ?></th>
				<th><?php _e('Bureau', 'capstan'); ?></th>
			</tr>
			<?php while($wpQuery->have_posts()) {
				$wpQuery->the_post();
				$postID = get_the_ID();
			?>

				<tr class="entry">
					<td class="title">
						<a href="<?php the_permalink($postID); ?>"><?php the_field('title', $postID); ?></a>
						<div class="date"><?php _e('Publié il y a', 'capstan'); ?> <?php echo round((time() - intval(get_the_time('U'))) / (3600 * 24) + 1); ?> jours</div>
					</td>
					<td class="info">
						<span class="label"><?php _e('CONTRAT', 'capstan'); ?></span>
						<span class="value"><?php the_field('contract', $postID);?></span>
					</td>
					<td class="info last">
						<span class="label"><?php _e('BUREAU', 'capstan'); ?></span>
						<span class="value"><?php the_field('city', get_field('place', $postID)) ?></span>
					</td>
				</tr>
			<?php }
				wp_reset_postdata();
			?>
		</table>
		
	</div>
	<?php if(!empty(get_field('link'))) { ?>
		<div class="link-container">
			<a href="<?php echo get_field('link')['url'] ?>" target="<?php echo get_field('link')['target'] ?>" class="link-top"><?php echo get_field('link')['title'] ?><i class="icon-arrow-top"></i></a>
		</div>
	<?php } ?>
</div>
<?php } ?>