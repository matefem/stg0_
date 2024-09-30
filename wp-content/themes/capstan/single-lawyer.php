<?php get_header(); ?>
<?php
	$postID = get_the_ID();
	the_post($postID);

	$socials 	= get_field('socials');
	$officeID	= get_field('office');
?>
<section class="page white">

	<?php getTemplate('partials/contact'); ?>
	
	<div class="module module-13">
		<div class="infos">
			<div class="text">
				<div class="title" data-animation="titleReveal"><?php the_field('name'); ?></div>
				<div class="job"><?php the_field('status'); ?> — <?php the_field('city', $officeID); ?></div>
				<div class="description"><?php the_field('description'); ?></div>
				<div class="icons">
					<?php
					if(!empty($socials['phone'])) { ?>
						<a href="tel:<?php echo str_replace(' ', '', $socials['phone']);?>"><i class="icon-phone"></i></a>
					<?php }

					if(!empty($socials['mail'])) { ?>
						<a href="mailto:<?php echo $socials['mail'];?>"><i class="icon-mail"></i></a>
					<?php }

					if(!empty($socials['linkedin'])) { ?>
						<a href="<?php echo $socials['linkedin']['url'];?>" target="<?php echo $socials['linkedin']['target']; ?>"><i class="icon-in"></i></a>
					<?php } ?>
				</div>
			</div>
			<div class="card hide-mobile">
				<a href="<?php echo get_permalink($officeID) ;?>"><div class="title" data-animation="titleReveal"><?php echo strip_tags(get_field('title', $officeID)); ?></div></a>

				<ul>
					<li>
						<span class="name"><?php _e('Adresse', 'capstan'); ?></span>
						<span class="value"><?php echo get_field('infos', $officeID)['address']; ?></span>
					</li>
					<li>
						<span class="name"><?php _e('Téléphone', 'capstan'); ?></span>
						<a class="value" href="tel:<?php echo str_replace(' ', '', get_field('infos', $officeID)['phone']); ?>">
							<?php echo get_field('infos', $officeID)['phone']; ?>
						</a>
					</li>
					<li>
						<span class="name"><?php _e('Mail', 'capstan'); ?></span>
						<a class="value" href="mailto:<?php echo get_field('infos', $officeID)['mail']; ?>">
							<?php echo get_field('infos', $officeID)['mail']; ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="image overflow-h" data-animation="imageReveal">
			<picture>
				<source srcset="<?php echo get_field('image')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
				<img src="<?php echo get_field('image')['sizes']['medium_large'] ?>" alt="<?php echo get_field('image')['alt'] ?>" draggable="false">
			</picture>
		</div>
		<div class="card hide-desktop">
			<a href="<?php echo get_permalink($officeID) ;?>"><div class="title"><?php echo strip_tags(get_field('title', $officeID)); ?></div></a>

			<ul>
				<li>
					<span class="name"><?php _e('Adresse', 'capstan'); ?></span>
					<span class="value"><?php echo get_field('infos', $officeID)['address']; ?></span>
				</li>
				<li>
					<span class="name"><?php _e('Téléphone', 'capstan'); ?></span>
					<a class="value" href="tel:<?php echo str_replace(' ', '', get_field('infos', $officeID)['phone']);?>">
						<?php echo get_field('infos', $officeID)['phone']; ?>
					</a>
				</li>
				<li>
					<span class="name"><?php _e('Mail', 'capstan'); ?></span>
					<a class="value" href="mailto:<?php echo get_field('infos', $officeID)['mail']; ?>">
						<?php echo get_field('infos', $officeID)['mail']; ?>
					</a>
				</li>
			</ul>
		</div>

	</div>
    <?php the_content(); ?>
</section>


<?php get_footer(); ?>