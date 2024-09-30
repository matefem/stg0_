<div class="module module-36" data-background="dark">
	
	<div class="columns">
		<div class="line">
			<div class="image overflow-h" data-animation="imageReveal">
				<picture>
					<img src="<?php echo get_field('left_image')['sizes']['mobile']; ?>" alt="<?php echo get_field('left_image')['alt']?>" draggable="false">
				</picture>
				<?php 
				if(get_field('redlayer')=="1") { ?>
				<img src="<?php echo get_template_directory_uri().'/resources/assets/img/module/filter.png'; ?>" alt="" draggable="false" class="overlay-filter">
				<?php } ?>
			</div>
		</div>
		<div class="line">
			<div class="image overflow-h" data-animation="imageReveal">
				<picture>
					<img src="<?php echo get_field('right_image')['sizes']['mobile']; ?>" alt="<?php echo get_field('right_image')['alt']?>" draggable="false">
				</picture>

				<?php 
				if(get_field('redlayer')=="1") { ?>
				<img src="<?php echo get_template_directory_uri().'/resources/assets/img/module/filter.png'; ?>" alt="" draggable="false" class="overlay-filter">
				<?php } ?>
			</div>
		</div>
	
	</div>

</div>