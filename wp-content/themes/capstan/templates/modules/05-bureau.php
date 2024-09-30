<div class="module module-05" data-background="dark" data-component="Bureau05Component">
	<?php 
		$query = new WP_Query(
			array(
		    	'post_type' => 'office',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'order' => 'ASC'
			)
		);

		$offices = Array();

		while($query->have_posts()) {
			$query->the_post();

			$postID = get_the_ID();

			 $offices[] = Array(
				'title' => get_field('title', $postID),
				'image' => get_field('image', $postID),
				'infos' => get_field('infos', $postID)
			);
		}

		wp_reset_postdata();
	?>
	<div class="title" data-animation="titleReveal">
		<?php the_field('title') ?>
	</div>

	<div class="gallery">
		<?php
		foreach ($offices as $office) { ?>
			<div class="item">
				<picture class="fit">
					<source srcset="<?php echo $office['image']['sizes']['2048x2048'] ?>" media="(min-width: 960px)" type="image/jpeg">
					<img src="<?php echo $office['image']['sizes']['medium_large'] ?>" alt="" draggable="false">
				</picture>
			</div>
		<?php } ?>
		<img src="<?php echo get_template_directory_uri().'/resources/assets/img/module/filter2.png'; ?>" alt="" draggable="false" class="overlay-filter2">
		<span class="line"></span>
	</div>

	<div class="infos-container">
		<?php
		foreach ($offices as $office) { ?>
			<div class="infos">
				<div class="logo">
					<span class="inline-block">
						<i class="icon-icon"></i> <span><?php echo strip_tags($office['title']); ?></span>
					</span>
				</div>

				<div class="adresse">
					<span class="inline-block">
						<?php echo $office['infos']['address']; ?><br />
						<?php echo $office['infos']['phone']; ?><br />
						<?php echo $office['infos']['mail']; ?>
					</span>
				</div>
				<?php if(!empty($office['infos']['mail'])) { ?>
					<a href="mailto:<?php echo $office['infos']['mail'] ?>" class="link-arrow-right">
						<?php _e('Nous contacter', 'capstan'); ?> <i class="icon-right"></i>
					</a>
				<?php } ?>
			</div>
		<?php } ?>
	</div>

</div>