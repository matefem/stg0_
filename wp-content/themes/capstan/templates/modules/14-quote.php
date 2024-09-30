<div class="module module-14" data-background="dark">
	
	<div class="content">
		<div class="overflow-h" data-animation="YReveal"><i class="icon-quote"></i></div>
		<div class="text" data-animation="titleReveal"><?php the_field('content'); ?></div>
	</div>
	<div class="person">
		<?php if(get_field('author_isLawyer') == '1') {
			$authorId = get_field('author_lawyer');
			?>
			<div class="thumb">
				<img src="<?php echo get_field('image', $authorId)['sizes']['thumbnail'] ?>" alt="<?php echo get_field('image', $authorId)['alt'] ?>">
			</div>
			<a href="<?php echo get_permalink($authorId); ?>"><div class="infos">
				<div class="name"><?php echo get_field('name', $authorId); ?></div>
				<div class="job"><?php echo get_field('status', $authorId); ?></div>
			</div></a>
		<?php } else { ?>
			<div class="infos">
				<div class="name"><?php echo get_field('author_external'); ?></div>
			</div>
		<?php } ?>
	</div>

</div>