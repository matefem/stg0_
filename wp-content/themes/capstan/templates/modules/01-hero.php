<div class="module module-01" data-component="Hero01Component" data-menu="1" data-menu-img="<?php echo get_field('background')['sizes']['1536x1536'] ?>" data-menu-content="<?php echo strip_tags(get_field('title')); ?>" data-background="none">

	<picture class="fit">
		<source srcset="<?php echo get_field('background')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
		<img src="<?php echo get_field('background')['sizes']['medium_large'] ?>" alt="<?php echo get_field('background')['alt'] ?>" draggable="false">
	</picture>

	<?php if(!empty(get_field('video'))) { ?>
		<video src="<?php echo get_field('video')['url']; ?>" alt="<?php echo get_field('video')['alt']; ?>" muted loop autoplay preload="metadata" playsinline class="video"></video>
	<?php  } ?>

	<div class="overlay"></div>
	
	<div class="title" data-animation="titleReveal">
		<?php the_field('title') ?>
	</div>

	<div class="hold hover">
		<span></span>
		<?php _e('Cliquez et maintenez', 'capstan'); ?>
	</div>
</div>