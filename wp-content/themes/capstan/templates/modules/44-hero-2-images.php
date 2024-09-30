<div class="module module-44" data-background="none">
	<div class="infos">
		<div class="title" data-animation="titleReveal">
			<?php the_field('title') ?>
		</div>

		<div class="description">
			<?php the_field('description') ?>
		</div>
	</div>

	<div class="images">
		<div class="main-image fit">
			<picture>
				<source srcset="<?php echo get_field('main_image')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg"/>
				<img src="<?php echo get_field('main_image')['sizes']['medium_large'] ?>" alt="<?php echo get_field('main_image')['alt'] ?>" draggable="false"/>
			</picture>
		</div>
		<?php if(!empty(get_field('secondary_image'))) { ?>
			<div class="secondary-image fit">
				<img src="<?php echo get_field('secondary_image')['sizes']['medium_large'] ?>" alt="<?php echo get_field('secondary_image')['alt'] ?>" draggable="false"/>
			</div>
		<?php } ?>
	</div>
</div>