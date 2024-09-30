<?php
	$background = get_field('background');
?>
<div class="module module-02 <?php the_field('align'); ?> <?php echo $background; ?>" data-background="<?php echo ($background == 'white'?'dark':'none'); ?>">
	
	<div class="title" data-animation="titleReveal">
		<?php the_field('title') ?>
	</div>

	<div class="description"><?php the_field('content') ?></div>


	<?php 
		if(!empty(get_field('links'))) {
			foreach(get_field('links') as $link) {
	?>
	<ul class="cta">
		<li>
			<a href="<?php echo $link['link']['url']; ?>" _target="<?php echo $link['link']['target']; ?>" class="link-arrow-top" data-animation="linkArrowTopReveal"><span class="overflow-h inline-block"><span class="inline-block inner"><?php echo $link['link']['title']; ?></span></span> <i class="icon-arrow-top"></i></a>
		</li>
		</ul>
	<?php
		}}
	?>
</div>