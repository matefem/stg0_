<div class="module module-32" data-background="none">
	
	<div class="content">
		<div class="infos">
			<div class="title" data-animation="titleReveal"><?php the_field('title') ?></div>
			<div class="description"><?php the_field('description') ?></div>
		</div>

		<div class="hr">
			<div class="image overflow-h" data-animation="imageReveal">
				<picture>
					<source srcset="<?php echo get_field('details')['image']['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
					<img src="<?php echo get_field('details')['image']['sizes']['medium_large'] ?>" alt="<?php echo get_field('details')['image']['alt'] ?>" draggable="false">
				</picture>
				<?php 
				if(get_field('redlayer')=="1") { ?>
				<img src="<?php echo get_template_directory_uri().'/resources/assets/img/module/filter.png'; ?>" alt="" draggable="false" class="overlay-filter">
				<?php } ?>
			</div>
			<div class="text">
					
				<div class="title" data-animation="titleReveal"><?php echo get_field('details')['title'] ?></div>
				<div class="description"><?php echo get_field('details')['description'] ?></div>

				<a href="<?php echo get_field('details')['link']['url'] ?>" target="<?php echo get_field('details')['link']['target'] ?>" class="link-top"><?php echo get_field('details')['link']['title'] ?><i class="icon-arrow-top"></i></a>

			</div>
		</div>
	</div>

</div>	