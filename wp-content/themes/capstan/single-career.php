<?php get_header(); ?>

<section class="page white">
    
	<?php getTemplate('partials/contact'); ?>
	
    <div class="module module-47" data-component="Career47Component">

		<div class="head">
			<div class="title"><?php the_field('title'); ?></div>

			<ul>
				<li><span class="name"><?php _e("Lieu", 'capstan'); ?> :</span><span class="value"><?php the_field('city', get_field('place')); ?></span></li>
				<li><span class="name"><?php _e("Type de contrat", 'capstan'); ?> :</span><span class="value"><?php echo getContractType(get_field('contract')); ?></span></li>
				<li><span class="name"><?php _e("Début", 'capstan'); ?> :</span><span class="value"><?php the_field('start'); ?></span></li>
			</ul>
		</div>

		<div class="menu">
			
			<ul>
				<li>
					<div data-ref="0"><span class="name">—</span><span class="value"><?php _e("À propos de Capstan", 'capstan'); ?></span></a>
				</li>
				<?php foreach (get_field('content') as $i => $item) { ?>
				<li>
					<div data-ref="<?php echo $i+1; ?>"><span class="name">—</span><span class="value"><?php echo $item['title'] ?></span></a>
				</li>
			<?php } ?>
			</ul>

		</div>

		<div class="content">
			<div data-id="<?php echo 0; ?>">
				<h2><?php _e("À propos de Capstan", 'capstan'); ?></h2>
				<p><?php the_field('about'); ?></p>
				<?php if(!empty(get_field('image'))) { ?>
				<div class="image-container">
					<picture>
						<source srcset="<?php echo get_field('image')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
						<img src="<?php echo get_field('image')['sizes']['medium_large'] ?>" alt="<?php echo get_field('image')['alt'] ?>" draggable="false">
					</picture>
				</div>
				<?php } ?>
			</div>


			<h2><?php the_field('subtitle'); ?></h2>

			<h3><?php the_field('description'); ?></h3>

			<?php foreach(get_field('content') as $i => $item) { ?>
			<div data-id="<?php echo $i+1; ?>">
				<h4><?php echo $item['title']; ?></h4>
				<?php echo $item['paragraph']; ?>
			</div>
			<?php }
			if(!empty(get_field('apply_process'))) { ?>
			<div class="items">
				<?php foreach (get_field('apply_process') as $i => $item) { ?>
				<div class="item">
					<i class="icon-arrow2 hide-mobile"></i>
					<div class="subtitle" data-animation="titleReveal"><?php echo $item['title']; ?></div>
					<div class="number"><?php echo (($i+1)<10?'0':'').($i+1) ?></div>
					<div class="description"><?php echo $item['description']; ?></div>
				</div>
				<?php } ?>
			</div>
			<?php } ?>

			<div class="actions">
				<?php
					$mail = empty(get_field('mail')) ? get_field('infos', get_field('place'))['mail'] : get_field('mail');
				?>
				<a href="mailto:<?php echo $mail; ?>" target="_blank" class="link-arrow-top" data-animation="linkArrowTopReveal" target="#"><span class="inline-block"><span class="inline-block inner"><i class="icon-arrow-top"></i>Candidater au poste</span></span> </a>
			</div>

		</div>

	</div>

</section>


<?php get_footer(); ?>