<div class="module module-24" data-background="dark">

	<?php 

	if(!empty(get_field('title')) && !empty(get_field('description'))) {?>
	<div class="introduction">
		<div class="title" data-animation="titleReveal"><?php the_field('title') ?></div>
		<div class="description"><?php the_field('description') ?> </div>
	</div>
	<?php } ?>
	
	<div class="columns">
		<?php
		foreach(get_field('awards') as $award) { ?>
		<div class="line">
			<div class="subtitle"><?php echo $award['subtitle'] ?></div>
			<div class="name"><?php echo $award['name'] ?></div>
		</div>
		<?php } ?>
	</div>
</div>