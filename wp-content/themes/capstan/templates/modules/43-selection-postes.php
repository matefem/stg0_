<?php

		$postQuery = new WP_Query(Array(
			'post_type' => 'career',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'orderby' => 'title'
		));

		$postes = Array();
		$offices = Array();


		while($postQuery->have_posts()) {
			$postQuery->the_post();

			$postID = get_the_ID();

			$poste = strip_tags(get_field('title', $postID));
			if(!in_array($poste, $postes)) {
				$postes[] = $poste;
			}

			$city = get_field('city', get_field('place', $postID));
			if(!in_array($city, $offices)) {
				$offices[] = $city;
			}
		}
		wp_reset_postdata();


		$query = Array(
			'post_type' => 'career',
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'order' => 'ASC'
		);

		$wpQuery = new WP_Query($query);
	?>
<div class="module module-43" data-background="none" data-component="Postes43Component">
	<div class="global-fade"></div>
	
	<div class="head">
		<div class="title"><?php _e('Je souhaite rejoindre Capstan à ', 'capstan'); ?>
			<div class="select message-formule select-openable">
				<span class="preview empty">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <i class="icon-up"></i>
				<select class="formule message-formule-select" name="formule" required>
					<option value="all"><?php _e('Tous', 'capstan'); ?></option>
				<?php foreach($offices as $office) { ?>
					<option value="<?php echo $office; ?>"><?php echo $office; ?></option>
				<?php } ?>
				</select>
			</div>
			<?php _e('au poste :', 'capstan'); ?>
			<div class="select message-formule select-openable">
				<span class="preview empty">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <i class="icon-up"></i>
				<select class="formule message-formule-select" name="formule" required>
					<option value="all"><?php _e('Tous', 'capstan'); ?></option>
					<?php foreach($postes as $poste) { ?>
						<option><?php echo $poste; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
	
	<div class="columns">
		<table class="content">
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
						<div class="date"><?php _e('Publié il y a', 'capstan'); ?> <?php echo round((time() - intval(get_the_time('U'))) / (3600 * 24) + 1); ?> <?php _e('jours', 'capstan'); ?></div>
					</td>
					<td class="info">
						<span class="label"><?php _e('CONTRAT', 'capstan'); ?></span>
						<span class="value"><?php echo getContractType(get_field('contract', $postID));?></span>
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
</div>