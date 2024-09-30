<div class="module module-09" data-background="none">
	<div class="image-container image1" data-animation="imageReveal">
		<picture>
			<img src="<?php echo get_field('images')[0]['image']['sizes']['mobile']; ?>" alt="<?php echo get_field('images')[0]['image']['alt']; ?>" draggable="false">
		</picture>
	</div>

	<div class="image-container image2" data-animation="imageReveal">
		<picture>
			<img src="<?php echo get_field('images')[1]['image']['sizes']['mobile']; ?>" alt="<?php echo get_field('images')[1]['image']['alt']; ?>" draggable="false" clas>
		</picture>
	</div>

	<div class="image-container image3" data-animation="imageReveal">
		<picture>
			<img src="<?php echo get_field('images')[2]['image']['sizes']['mobile']; ?>" alt="<?php echo get_field('images')[2]['image']['alt']; ?>" draggable="false">
		</picture>
	</div>

	<div class="title" data-animation="titleReveal">
		<?php the_field('title'); ?>
	</div>

	<a href="<?php echo get_field('link')['url']; ?>" target="<?php echo get_field('link')['target']; ?>" class="link-arrow-top" data-animation="linkArrowTopReveal"><span class="overflow-h inline-block"><span class="inline-block inner"><?php echo get_field('link')['title']; ?></span></span> <i class="icon-arrow-top"></i></a>

</div>