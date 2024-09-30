<div class="module module-33" data-background="dark">

	<div class="content">
		<div class="introduction">
			<div class="title" data-animation="titleReveal"><?php the_field('title') ?></div>
			<div class="description"><?php the_field('description') ?></div>
		</div>

		<div class="liste">
			<?php 

			foreach (get_field('metrics') as $metric) { ?>
			<div class="item">
				<div class="number overflow-h" data-animation="YReveal"><div><?php echo $metric['number']; ?></div></div>
				<div class="subtitle"><?php echo $metric['description']; ?></div>
			</div>
			<?php } ?>
		</diV>

	</div>


</div>