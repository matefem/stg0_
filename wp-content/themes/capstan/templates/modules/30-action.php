<div class="module module-30" data-background="dark">
	<div class="content">
		<div class="introduction">
			<div class="title" data-animation="titleReveal"><?php the_field('title') ?></div>
			<?php if(get_field('link')): ?>
				<a href="<?php echo get_field('link')['url'] ?>" target="<?php echo get_field('link')['target']; ?>" class="link-arrow-top"><?php echo get_field('link')['title'] ?><i class="icon-arrow-top"></i></a>
			<?php endif; ?>
		</div>

		<?php
		$actions = get_field('actions');
		for($i = 0; $i < count($actions); $i++) {?>
		<div class="item">
			<div class="number"><?php echo (($i+1)<10?'0':'').($i+1) ?></div>
			<div class="text">
				<div class="subtitle" data-animation="titleReveal"><?php echo $actions[$i]['title']; ?></div>
				<?php if($actions[$i]['description']!="") { ?><div class="description"><?php echo $actions[$i]['description']; ?></div><?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
</div>