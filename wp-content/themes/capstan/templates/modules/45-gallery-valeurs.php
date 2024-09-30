<div class="module module-45" data-background="dark" data-component="Gallery45Component">
	<div class="title" data-animation="titleReveal"><?php the_field('title'); ?></div>
	<div class="gallery">
		<ul>
			<?php foreach (get_field('gallery') as $i => $item) { ?>
				<li>
					<div class="number"><?php echo (($i+1)<10?'0':'').($i+1) ?></div>
					<div class="infos">
						<div class="item-title"><?php echo $item['title']; ?></div>
						<div class="description"><?php echo $item['description']; ?></div>
					</div>
					<img src="<?php echo $item['image']['sizes']['medium_large']; ?>" alt="<?php echo $item['image']['alt'];?>"/>
				</li>
			<?php } ?>
		</ul>
	</div>
	<div class="image-container">
		<div class="inner">
			<img src=""/>
		</div>
	</div>
</div>