<div class="module module-18" data-background="none">
	<?php 
		$query = new WP_Query(
			array(
		    	'post_type' => 'office',
				'posts_per_page' => -1,
				'order' => 'ASC'
			)
		);

		$offices = Array();

		while($query->have_posts()) {
			$query->the_post();

			$postID = get_the_ID();

			 $offices[] = Array(
				'title' => strip_tags(get_field('title', $postID)),
				'image' => get_field('image', $postID),
				'url'	=> get_permalink($postID)
			); 
		}
		wp_reset_postdata();
	?>
	<div class="grid">
		<?php foreach ($offices as $office) { ?>
		<div class="item" data-follow-link>
			<div class="overflow-h image" data-animation="imageReveal">
				<picture>
					<img src="<?php echo $office['image']['sizes']['mobile'] ?>" alt="<?php echo $office['image']['alt'] ?>" draggable="false">
				</picture>
			</div>

			<div class="name">
				<a href="<?php echo $office['url']; ?>"><i class="icon-icon"></i> <?php echo $office['title']; ?></a>
			</div>
		</div>
		<?php } ?>
	</div>

</div>