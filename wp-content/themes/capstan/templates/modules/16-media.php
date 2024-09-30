<div class="module module-16 <?php the_field('background'); ?>" data-component="Media16Component" data-background="none">
	<?php if(!empty(get_field('title')) && !empty(get_field('description'))) { ?>
	<div class="text">
		<?php if(!empty(get_field('title'))) { ?>
		<div class="title" data-animation="titleReveal">
			<?php the_field('title') ?>
		</div>
		<?php }
		if(!empty(get_field('description'))) { ?>
		<div class="description">
			<?php the_field('description') ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="media">
		<?php if(get_field('image')) { ?>
		<picture>
			<img src="<?php echo get_field('image')['sizes']['mobile'] ?>" alt="<?php echo get_field('image')['alt'] ?>" draggable="false">
		</picture>
		<?php } ?>
	</div>
</div>