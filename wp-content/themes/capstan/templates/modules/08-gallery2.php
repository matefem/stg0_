<?php
	$background = get_field('background');
?>
<div class="module module-08 <?php echo $background; ?>" <?php echo ($background == 'white'?'data-background="dark"':''); ?> data-component="Gallery08Component">
	<?php
		$items = get_field('gallery');
	?>
	<div class="container">
		<?php

		foreach ($items as $item) { ?>
			<div class="item">
				<div class="image">
					<picture>
						<img src="<?php echo $item['image']['sizes']['mobile'] ?>" alt="<?php echo $item['image']['alt'] ?>" draggable="false" ondragstart="return false;">
					</picture>
				</div>

				<div class="infos">
					<div class="description"><?php echo $item['quote']; ?></div>
					<div class="name"><?php echo $item['author']; ?></div>
					<div class="job"><?php echo $item['status']; ?></div>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="nav">
		
		<div class="dots">
		</div>

	</div>

</div>