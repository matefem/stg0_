<?php get_header(); ?>
<?php
	$postID = get_the_ID();


	$queryLawyers = new WP_Query(
		array(
	    	'post_type' => 'lawyer',
			'orderby' => 'rand',
	    	'posts_per_page' => -1,
	    	'meta_query' => Array(
	    		Array(
	    			'key' => 'office',
	    			'value' => $postID
	    		)
	    	)
		)
	);

	$lawyers = Array();
	$allLawyers = Array();

	foreach(getAllStaffFunctions() as $key => $value) {
		$allLawyers[$key] = Array();
	}


	$i = 0;
	while($queryLawyers->have_posts()) {
		$queryLawyers->the_post();

		$lawyeId = get_the_ID();

		$infos = Array(
			'ID' 		=> $lawyeId,
			'title' 	=> get_field('name', $lawyeId),
			'status'  	=> get_field('status', $lawyeId),
			'function'  => get_field('function', $lawyeId),
			'image' 	=> get_field('image', $lawyeId),
			'url'		=> get_permalink($lawyeId)
		);

		$allLawyers[$infos['function']['value']][] = $infos;
		$i++;
	}
	// print_r($allLawyers); echo '***********';

	$n = 0;
	foreach($allLawyers as $value) {
		$i = 0;
		while($i < count($value) && $n < 3) {
			$lawyers[] = $value[$i];
			$i++;
			$n++;
		}
	}
	$haveMore = (count($queryLawyers->posts) > $i);
	wp_reset_postdata();

	the_post($postID);
?>

<section class="page white" data-component="Office">

	<?php getTemplate('partials/contact'); ?>
	
	<div class="module module-40 separate">
		<div class="infos">
			<div class="title" data-animation="titleReveal">
				<?php echo get_field('title'); ?>
			</div>
			<div class="description">
				<?php echo get_field('description'); ?>
			</div>
		</div>

		<div class="image">
			<picture>
				<source srcset="<?php echo get_field('image')['url'] ?>" media="(min-width: 960px)" type="image/jpeg">
				<img src="<?php echo get_field('image')['sizes']['medium_large'] ?>" alt="" draggable="false">
			</picture>
			<?php
			if(!empty(get_field('image')['caption'])) { ?>
				<div class="caption"><?php echo get_field('image')['caption']; ?></div>
			<?php } ?>
		</div>

		<div class="module module-20">
			<div class="list">
				<div class="title" data-animation="titleReveal"><?php _e("Domaines d’intervention", 'capstan'); ?></div>
				<ul>
					<?php
					foreach (get_field('domains') as $domain) { ?>
						<li><a href="<?php get_permalink($domain->ID); ?>"><i class="icon-round"></i><?php echo $domain->post_title; ?></a></li>
					<?php } ?>
				</ul>
			</div>
			<div class="card">
				<div class="title" data-animation="titleReveal"><?php _e("Informations", 'capstan'); ?></div>
				<ul>
					<li>
						<span class="name">Adresse</span>
						<?php if(!empty(get_field('infos')['maps_link'])) { ?>
						<a href="<?php echo get_field('infos')['maps_link'] ?>" target="_blank" class="value">
							<?php echo get_field('infos')['address'] ?>
						</a>
						<?php } else { ?>
							<span class="value"><?php echo get_field('infos')['address'] ?></span>
						<?php } ?>
					</li>
					<li>
						<span class="name">Téléphone</span>
						<a class="value" href="tel:<?php echo str_replace(' ', '', get_field('infos')['phone']) ?>" target="_blank">
							<?php echo get_field('infos')['phone'] ?>
						</a>
					</li>
					<li>
						<span class="name">Mail</span>
						<a class="value" href="mailto:<?php echo get_field('infos')['mail'] ?>" target="_blank">
							<?php echo get_field('infos')['mail'] ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="module module-21">
			<div class="title" data-animation="titleReveal"><?php _e("L’équipe sur place", 'capstan'); ?></div>
			<div class="grid lawyers-grid" data-office="<?php echo $postID; ?>">
			<?php
				foreach($lawyers as $lawyer) { ?>
				<div class="item" data-id="<?php echo $lawyer['ID']; ?>">
					<div class="image overflow-h" data-animation="imageReveal">
						<picture>
							<img src="<?php echo $lawyer['image']['sizes']['medium_large'] ?>" alt="<?php echo $lawyer['image']['alt'] ?>" draggable="false">
						</picture>
					</div>
					<a href="<?php echo $lawyer['url'] ;?>">
						<div class="subtitle"><?php echo $lawyer['title'] ?></div>
						<div class="job"><?php echo $lawyer['status'] ?></div>
					</a>
				</div>
			<?php } ?>
			</div>
			<?php if($haveMore) { ?>
				<button class="link-plus lawyers-plus"><?php _e("VOIR PLUS", 'capstan'); ?> <i class="icon-plus"></i></button>
			<?php } ?>
		</div>
	</div>

    <?php the_content(); ?>
</section>


<?php get_footer(); ?>