<div class="module module-42" data-component="Gallery03Component" data-background="none">
	<div class="infos">
		<div class="title" data-animation="titleReveal">
			<?php the_field('title') ?>
		</div>

		<?php if(!empty(get_field('description'))) { ?>
		<div class="description">
			<?php the_field('description') ?>
		</div>
	<?php } ?>
		<div class="arrows">
			<i class="icon-right left"></i>
			<i class="icon-right right"></i>
		</div>
	</div>
	<div class="gallery">
		<?php 
			foreach(get_field('images') as $key => $image) {
		?>
		<div class="item overflow-h">
			<div class="img-container">
				<picture>
					<source srcset="<?php echo $image['image']['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
					<img src="<?php echo $image['image']['sizes']['medium_large'] ?>" alt="<?php echo $image['image']['alt'] ?>" draggable="false" class="image">
				</picture>
			</div>
		</div>
		<?php } ?>
	</div>
</div>