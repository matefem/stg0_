<?php 
if(get_field('head')=="1") {
	$head = "is-head";
}
else {
	$head = "";
}
?>

<div class="module module-40 separate <?php echo $head; ?>" data-background="dark">
	<div class="infos">
		<div class="title size-<?php the_field('title_size'); ?>" data-animation="titleReveal">
			<?php the_field('title') ?>
		</div>

		<div class="description"><?php the_field('description') ?></div>
	</div>

	<?php if(!empty(get_field('image'))) { ?>
	<div class="image">

		<picture>
			<source srcset="<?php echo get_field('image')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg"/>
			<img src="<?php echo get_field('image')['sizes']['medium_large'] ?>" alt="" draggable="false"/>
		</picture>
			<?php
			if(!empty(get_field('image')['caption'])) { ?>
				<div class="caption"><?php echo get_field('image')['caption']; ?></div>
			<?php } ?>
	</div>
	<?php } ?>
</div>