<div class="module module-27" data-background="none">
	
	<div class="content">
		<div class="text">
			<div class="title" data-animation="titleReveal"><?php the_field('title') ?></div>
			<div class="description"><?php the_field('description') ?></div>
		</div>
		<div class="image overflow-h" data-animation="imageReveal">
			<picture>
				<source srcset="<?php echo get_field('image')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
				<img src="<?php echo get_field('image')['sizes']['medium_large'] ?>" alt="<?php echo get_field('image')['alt'] ?>" draggable="false">
			</picture>
		</div>
	</div>

</div>