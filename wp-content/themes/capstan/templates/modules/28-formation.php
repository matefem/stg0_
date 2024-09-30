<div class="module module-28" data-component="Formation28Component" data-background="none">
	<?php

		$queryFormations = new WP_Query(
			array(
		    	'post_type' => 'formation',
		    	'posts_per_page' => -1
			)
		);

		$formations = Array();
		$i = 0;
		$now = new DateTime('NOW');
		while($queryFormations->have_posts() && $i < 8) {
			$queryFormations->the_post();

			$postID = get_the_ID();

			$date = get_field('dates', $postID);
			$almostOneDate = false;
				
			$first = DateTime::createFromFormat("d/m/Y", trim($date[0]['day']));

			for($i = 0; $i < count($date);$i++) {
				$d = DateTime::createFromFormat("d/m/Y", trim($date[$i]['day']));

				if(intval($d->diff($now)->format('%R%a')) <= 0) {
					$almostOneDate = true;
				}
			}

			if($almostOneDate) {
				$formations[] = Array(
					'ID' 			=> $postID,
					'title' 		=> get_field('title', $postID),
					'description'  	=> get_field('description', $postID),
					'office'  		=> get_field('office', $postID),
					'type'  		=> get_field('type', $postID),
					'dates'  		=> $date,
					'image' 		=> get_field('image', $postID),
					'url' 			=> get_permalink($postID),
					'firstdate'		=> $first
				);

				$i++;
			}
		}

		usort($formations, function ($a, $b) {
	        return $a['firstdate'] > $b['firstdate'];
	    });

		$haveMore = (count($queryFormations->posts) > $i);
		wp_reset_postdata();

		$queryOffices = new WP_Query(
			array(
				'post_type' => 'office',
				'posts_per_page' => -1
			)
		);
		$offices = Array();

		$i = 0;
		while($queryOffices->have_posts()) {
			$queryOffices->the_post();
			$postID = get_the_ID();

			$offices[] = Array(
				'ID' => $postID,
				'city' => get_field('city', $postID),
			);
		}
		wp_reset_postdata();
	?>
	<div>
		<div class="filters">
			<div class="subtitle"><?php _e('Filtrer par', 'capstan'); ?></div>
			<form>
				<div class="search">
					<input name="input" type="text" placeholder="<?php _e('Recherche par nom...', 'capstan'); ?>" value="" autocomplete="false"><i class="icon-search"></i>
				</div>
				<div class="offices list">
					<span class="value"><?php _e('Tous les bureaux', 'capstan'); ?></span><i class="icon-arrow-bottom"></i>
					<select>
						<option value=""><?php _e('Tous les bureaux', 'capstan'); ?></option>
						<?php foreach($offices as $office) { ?>
							<option value="<?php echo $office['ID']; ?>"><?php echo $office['city']; ?></option>
						<?php } ?>
					</select>
					<ul>
						<li data-value=""><?php _e('Tous les bureaux', 'capstan'); ?></li>
						<?php foreach($offices as $office) { ?>
							<li data-value="<?php echo $office['ID']; ?>"><?php echo $office['city']; ?></li>
						<?php } ?>
					</ul>
				</div>
				<div class="types list">
					<span class="value"><?php _e('Tous les types de formation', 'capstan'); ?></span><i class="icon-arrow-bottom"></i>
					<select>
						<option value=""><?php _e('Tous les types de formation', 'capstan'); ?></option>
						<option value="single"><?php _e('Formations ponctuelles', 'capstan'); ?></option>
						<option value="multi"><?php _e('Formations récurrentes', 'capstan'); ?></option>
					</select>
					<ul>
						<li data-value=""><?php _e('Tous les types de formation', 'capstan'); ?></li>
						<li data-value="single"><?php _e('Formations ponctuelles', 'capstan'); ?></li>
						<li data-value="multi"><?php _e('Formations récurrentes', 'capstan'); ?></li>
					</ul>
				</div>
			</form>

		</div>

		<div class="grid">
			<div>
				<div class="content">
					<?php
					foreach($formations as $formation) { ?>
						<div class="item" data-id="<?php echo $formation['ID'] ?>">
							<div class="image overflow-h" data-animation="imageReveal">
								<picture>
									<img src="<?php echo $formation['image']['sizes']['medium_large'] ?>" alt="<?php echo $formation['image']['alt'] ?>" draggable="false">
								</picture>
							</div>
							<div class="text">
								<?php $datetime = DateTime::createFromFormat("d/m/Y", trim($formation['dates'][0]['day']));
								if($datetime) { ?>
								<div class="date"><div><?php echo $formation['type'] == 'multi' ? 'Formation récurrente' : french_date(strftime("%d %B %Y", $datetime->format('U'))); ?></div></div>
								<a href="<?php echo $formation['url'] ?>"><div class="title" data-animation="titleReveal"><?php echo $formation['title']; ?></div></a>
								<div class="description"><?php echo $formation['description']; ?></div>
								<div class="infos">
									<div class="item"><span class="name">Bureau</span><span class="value"><?php the_field('city', $formation['office']); ?></span></div>

									<?php if($formation['type'] == 'multi') { ?>
										<div class="hours">
											<div class="item">
												<span class="name"><?php _e('Horaires', 'capstan'); ?></span>
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
										<div class="item"><span class="name">Horaires</span><span class="value">de <?php echo $formation['dates'][0]['hour_start'] ?> à <?php echo $formation['dates'][0]['hour_end'] ?></span></div>
									<?php } ?>
								</div>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<?php if($haveMore) { ?>
					<div class="text-center">
						<button class="link-plus"><?php _e('Voir plus de formations', 'capstan'); ?> <i class="icon-plus"></i></button>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>