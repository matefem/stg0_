<div class="module module-31" data-background="dark">
	
	<div class="content">
		<div class="infos">
			<div class="title" data-animation="titleReveal"><?php the_field('title') ?></div>
			<div class="description"><?php the_field('description') ?></div>
		</div>

		<a href="<?php echo get_field('link')['url'] ?>" target="<?php echo get_field('link')['target'] ?>" class="link-arrow-top"><?php echo get_field('link')['title'] ?><i class="icon-arrow-top"></i></a>
	</div>

</div>	