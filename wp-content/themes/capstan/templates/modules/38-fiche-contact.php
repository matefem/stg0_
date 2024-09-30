<div class="module module-38" data-background="none">
	<?php 
		$lawyerId = get_field('lawyer');
		$officeID = get_field('office', $lawyerId);
		$socials  = get_field('socials', $lawyerId);
	?>
	<div class="introduction">
		<div class="title" data-animation="titleReveal"><?php the_field('title') ?></div>
	</div>

	<div class="content">
		<div class="image overflow-h" data-animation="imageReveal">
			<picture>
				<source srcset="<?php echo get_field('image', $lawyerId)['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
				<img src="<?php echo get_field('image', $lawyerId)['sizes']['medium_large'] ?>" alt="<?php echo get_field('image', $lawyerId)['alt'] ?>" draggable="false">
			</picture>
		</diV>

		<div class="text">
			<div class="title" data-animation="titleReveal"><?php the_field('title') ?></div>
			<div class="name"><?php the_field('name', $lawyerId) ?></div>
			<div class="job"><?php the_field('status', $lawyerId) ?> — <?php the_field('city', $officeID); ?></div>
			<div class="description"><?php the_field('description', $lawyerId); ?></div>
			<div class="icons">
					<?php
					if(!empty($socials['phone'])) { ?>
						<a href="tel:<?php echo $socials['phone'];?>"><i class="icon-phone"></i></a>
					<?php }

					if(!empty($socials['mail'])) { ?>
						<a href="mailto:<?php echo $socials['mail'];?>"><i class="icon-mail"></i></a>
					<?php }

					if(!empty($socials['linkedin'])) { ?>
						<a href="<?php echo $socials['linkedin']['url'];?>" target="<?php echo $socials['linkedin']['target']; ?>"><i class="icon-in"></i></a>
					<?php } ?>
			</div>
		</div>

	</div>

</div>