<div class="module module-04" data-component="Video04Component" data-menu="1" data-menu-img="<?php echo get_field('background')['sizes']['1536x1536'] ?>" data-menu-content="<?php echo strip_tags(get_field('title')); ?>" data-background="none">
	<div class="video-container">
		<picture>
			<source srcset="<?php echo get_field('background')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
			<img src="<?php echo get_field('background')['sizes']['medium_large'] ?>" alt="<?php echo get_field('background')['alt'] ?>" draggable="false" class="video fit">
		</picture>

		<?php 
		if(get_field('redlayer')=="1") { ?>
		<img src="<?php echo get_template_directory_uri().'/resources/assets/img/module/filter.png'; ?>" alt="" draggable="false" class="overlay-filter">
		<?php } ?>

	</div>

	<?php if(!empty(get_field('subtitle')) && !empty(get_field('title'))) { ?>
		<div class="overlay"></div>
		<div class="subtitle overflow-h" data-animation="YReveal"><div><?php the_field('subtitle'); ?></div></div>
		<div class="title" data-animation="titleReveal"><?php the_field('title'); ?></div>
	<?php } ?>

	
</div>