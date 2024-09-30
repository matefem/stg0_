<div class="module module-26" data-component="Gallery26Component" data-background="none">
	<div class="gallery">
		<div class="container">
			<?php 
			$gallery = get_field('gallery');
			foreach ($gallery as $item) { ?>
			<div class="item">
				<div class="image">
					<picture>
						<source srcset="<?php echo $item['image']['sizes']['1536x1536']; ?>" media="(min-width: 960px)" type="image/jpeg">
						<img src="<?php echo $item['image']['sizes']['medium_large']; ?>" alt="<?php echo $item['image']['alt'];?>" draggable="false" ondragstart="return false;">
					</picture>
				</div>

				<div class="text">
					<div class="category"><?php echo $item['category']; ?></div>
					<div class="title"><?php echo $item['title']; ?></div>
					<div class="description"><?php echo $item['description']; ?></div>

					<a href="<?php echo $item['link']['url']; ?>" target="<?php echo $item['link']['target']; ?>"class="cta"><?php echo $item['link']['title']; ?> <i class="icon-right"></i></a>
				</div>

			</div>
			<?php } ?>
		</div>
	</div>
</div>