<div class="module module-12" data-component="Grid12Component" data-background="none">
	<?php

		$queryLawyers = new WP_Query(
			array(
		    	'post_type' => 'lawyer',
				'orderby' => 'rand',
		    	'posts_per_page' => -1,
				'post_status' => 'publish',
			)
		);

		$lawyers = Array();
		$allLawyers = Array();

		foreach(getAllStaffFunctions() as $key => $value) {
			$allLawyers[$key] = Array();
		}

		$n = 0;
		while($queryLawyers->have_posts()) {
			$queryLawyers->the_post();

			$postID = get_the_ID();

			$infos = Array(
				'ID' 		=> $postID,
				'title' 	=> get_field('name', $postID),
				'function' 	=> get_field('function', $postID),
				'status' 	=> get_field('status', $postID),
				'image'		=> get_field('image', $postID),
				'url'		=> get_permalink($postID),
				'socials' 	=>  get_field('socials', $postID),
				'office' 	=>  get_field('office', $postID)
			);

			$allLawyers[$infos['function']['value']][] = $infos;
			// if($n++ < 8) $lawyers[] = $infos;
		}
		wp_reset_postdata();

		$n = 0;
		foreach($allLawyers as $value) {
			$i = 0;
			while($i < count($value) && $n < 8) {
				$lawyers[] = $value[$i];
				$i++;
				$n++;
			}
		}

		$queryOffices = get_field('office_filter');

		$offices = Array();

		foreach($queryOffices as $postID) {
			$offices[] = Array(
				'ID' => $postID,
				'city' => get_field('city', $postID),
			);
		}
		wp_reset_postdata();
	?>
	<div class="content">
		<div class="filters">
			<div class="subtitle"><?php _e('Filtrer par', 'capstan');?></div>
			<form class="filters-forms">
				<div class="search">
					<input name="input" type="text" placeholder="<?php _e('Recherche par nom...', 'capstan');?>" value="" autocomplete="false">
					<i class="icon-search"></i>
				</div>
				<div class="offices list">
					<span class="value"><?php _e('Tous les bureaux', 'capstan');?></span>
					<i class="icon-arrow-bottom"></i>
					<select>
						<option value=""><?php _e('Tous les bureaux', 'capstan');?></option>
						<?php foreach($offices as $office) { ?>
							<option value="<?php echo $office['ID']; ?>"><?php echo $office['city']; ?></option>
						<?php } ?>
					</select>
					<ul>
						<li data-value=""><?php _e('Tous les bureaux', 'capstan');?></li>
						<?php foreach($offices as $office) { ?>
							<li data-value="<?php echo $office['ID']; ?>"><?php echo $office['city']; ?></li>
						<?php } ?>
					</ul>
				</div>
				<div class="titles list">
					<span class="value"><?php _e('Toutes les fonctions', 'capstan');?></span>
					<i class="icon-arrow-bottom"></i>
					<select>
						<option value=""><?php _e('Toutes les fonctions', 'capstan');?></option>
						<?php foreach(getAllStaffFunctions() as $key => $value) { ?>
							<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
						<?php } ?>
					</select>
					<ul>
						<li data-value=""><?php _e('Toutes les fonctions', 'capstan');?></li>
						<?php foreach(getAllStaffFunctions() as $key => $value) { ?>
							<li data-value="<?php echo $key; ?>"><?php echo $value; ?></li>
						<?php } ?>
					</ul>
				</div>
			</form>
		</div>
		<div class="grid">
			<div class="content">
				<?php
				foreach ($lawyers as $lawyer) { ?>
				<div class="item" data-id="<?php echo $lawyer['ID'] ?>">
					<div class="image">
						<picture>
							<img src="<?php echo $lawyer['image']['sizes']['mobile'] ?>" alt="<?php echo $lawyer['image']['alt'] ?>" draggable="false">
						</picture>
						<div class="overlay">
							<div class="border"></div>
							<div class="inner">
								<div class="work-for-container">
									<div class="work-for"><?php _e('Bureau', 'capstan');?></div>
									<a href="<?php echo get_permalink($lawyer['office']); ?>" class="office"><?php echo strip_tags(get_field('title', $lawyer['office'])); ?></a>
								</div>
								<div class="icons">
									<?php
									if(!empty($lawyer['socials']['phone'])) { ?>
										<a href="tel:<?php echo str_replace(' ', '', $lawyer['socials']['phone']);?>"><i class="icon-phone"></i></a>
									<?php }

									if(!empty($lawyer['socials']['mail'])) { ?>
										<a href="mailto:<?php echo $lawyer['socials']['mail'];?>"><i class="icon-mail"></i></a>
									<?php }

									if(!empty($lawyer['socials']['linkedin'])) { ?>
										<a href="<?php echo $lawyer['socials']['linkedin']['url'];?>" target="<?php echo $lawyer['socials']['linkedin']['target']; ?>"><i class="icon-in"></i></a>
									<?php } ?>
								</div>
								<a href="<?php echo $lawyer['url'] ?>" class="lawyer-cta"><?php _e('VOIR LE PROFIL', 'capstan');?><i class="icon-right"></i></a>
							</div>
						</div>
					</div>
					<a href="<?php echo $lawyer['url'] ?>">
						<div class="subtitle"><?php echo $lawyer['title'] ;?></div>
						<div class="job"><?php echo $lawyer['status'] ?></div>
					</a>
				</div>
			<?php } ?>
			</div>
			<div class="text-center">
				<button class="link-plus"><?php _e('VOIR PLUS', 'capstan');?><i class="icon-plus"></i></button>
			</div>
		</div>
	</div>
</div>