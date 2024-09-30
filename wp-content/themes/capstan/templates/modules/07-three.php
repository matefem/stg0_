<div class="module <?php echo (get_field('mobile_slideshow')?'module-07-bis':'module-07');?>" data-background="dark">
	
	<div class="title" data-animation="titleReveal"><?php the_field('title'); ?></div>
	<div class="description"><?php the_field('description'); ?></div>

	<div class="columns">
		<?php
		$images = get_field('images');

		for($i = 0;$i < count($images); $i++) { ?>
			<div class="line">
				<div class="overflow-h image-container" data-animation="imageReveal">
					<picture>
						<img src="<?php echo $images[$i]['image']['sizes']['mobile'] ?>" alt="<?php echo $images[$i]['image']['alt'] ?>" draggable="false">
					</picture>
				</div>
				<?php
				if(!empty($images[$i]['description'])) { ?>
					<div class="infos">
						<div class="number"><?php echo (($i+1)<10?'0':'').($i+1); ?></div>
						<div class="subtitle"><?php echo $images[$i]['description']; ?></div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>

</div>