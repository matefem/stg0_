<div class="module module-41" data-component="TalentHead41Component" data-background="none">
	<div class="container">
		<div class="asides">
			<?php
			$n = -1;
			$images = get_field('images');

			for($i = 0;$i < 5;$i++) {
				if($i == 2) { ?>
				<div class="column maincol">
					<div class="careers">
						<i class="icon-icon"></i>
						<?php _e('Carrières', 'capstan') ?>
					</div>
				</div>
				<?php } else { ?>
			<div class="column">
				<?php for($j = 0;$j < 3;$j++) {
				$n++; ?>
				<div class="image">
					<?php if(!empty($images[$n]['image']['sizes']['medium_large'])) { ?>
					<img src="<?php echo $images[$n]['image']['sizes']['medium_large'] ?>" alt="<?php echo $images[$n]['image']['alt'] ?>" class="secondary-img"/>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
			<?php }} ?>
		</div>
		<div class="main">
			<div class="main-bg">
				<picture class="fit">
					<source srcset="<?php echo get_field('main_image')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
					<img src="<?php echo get_field('main_image')['sizes']['medium_large'] ?>" alt="<?php echo get_field('main_image')['alt'] ?>" draggable="false">
				</picture>
			</div>
			<div class="text">
				<div class="title">
					<?php the_field('title') ?>
				</div>
				<div class="scroll-cta">
					<span><?php the_field('scroll_text') ?></span>
					<i class="icon-arrow-top2"></i>
				</div>
			</div>
		</div>
	</div>
</div>