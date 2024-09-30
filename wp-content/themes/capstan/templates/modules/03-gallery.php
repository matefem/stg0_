<div class="module module-03  <?php the_field('align'); ?> <?php the_field('background'); ?>" data-component="Gallery03Component" data-background="none">
	<div class="infos">
		<div class="title" data-animation="titleReveal">
			<?php the_field('title') ?>
		</div>

		<div class="description">
			<?php the_field('description') ?>
		</div>
	</div>

	<div class="gallery">

		<?php
			foreach(get_field('gallery') as $key => $link) {
		?>
		<div class="item overflow-h" data-follow-link>
			<div class="img-container">
				<picture>
					<img src="<?php echo $link['image']['sizes']['mobile'] ?>" alt="<?php echo $link['image']['alt'] ?>" draggable="false" class="image" ondragstart="return false;">
				</picture>
			</div>

			<div class="text">
				<div class="number"><?php echo (($key + 1) < 10?'0':'').($key+1) ?></div>
				<div class="title"><?php echo $link['title'] ?></div>
				<div class="description"><?php echo $link['description'] ?></div>

				<a href="<?php echo $link['link']['url']; ?>" target="<?php echo $link['link']['target']; ?>" draggable="false"><?php echo $link['link']['title']; ?><i class="icon-arrow"></i></a>
			</div>
		</div>
		<?php
			}
		?>
	</div>
	<div class="gallery-scrollbar">
		<div class="inner"></div>
	</div>

</div>