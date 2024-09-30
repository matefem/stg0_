<div class="module module-39" data-component="Contact39Component" data-background="dark">
	<?php 
		$query = new WP_Query(
			array(
		    	'post_type' => 'office',
				'posts_per_page' => -1,
				'order' => 'ASC'
			)
		);

		$offices = Array();

		while($query->have_posts()) {
			$query->the_post();

			$postID = get_the_ID();

			 $offices[] = Array(
				'title' => strip_tags(get_field('title', $postID)),
				'image' => get_field('image', $postID),
				'url'	=> get_permalink($postID)
			); 
		}
		wp_reset_postdata();
	?>

	<div class="content">
		<div class="global-fade"></div>
		<form method="post" class="contact-form">
			<div class="introduction">
				<div class="title">
					<div class="image first overflow-h" data-animation="imageReveal">
						<picture>
							<img src="<?php echo get_field('left_image')['sizes']['mobile'] ?>" alt="<?php echo get_field('left_image')['alt']?>" draggable="false">
						</picture>
					</div>
					<div class="image second overflow-h" data-animation="imageReveal">
						<picture>
							<img src="<?php echo get_field('right_image')['sizes']['mobile'] ?>" alt="<?php echo get_field('right_image')['alt']?>" draggable="false">
						</picture>
					</div>
					<div data-animation="titleReveal" class="title-inner"><?php the_field('title') ?></div>
				</div>
				
				<div class="select message-subject">
					<ul>
						<li class="active">
							<i class="icon-contact1"></i>
							<span class="value"><?php _e('Un accompagnement', 'capstan'); ?></span>
						</li>
						<li>
							<i class="icon-contact2"></i>
							<span class="value"><?php _e('Une canditature', 'capstan'); ?></span>
						</li>
						<li>
							<i class="icon-contact3"></i>
							<span class="value"><?php _e('Un autre sujet', 'capstan'); ?></span>
						</li>
						<li>
							<i class="icon-contact4"></i>
							<span class="value"><?php _e('Presse', 'capstan'); ?></span>
						</li>
					</ul>

					<select class="subject message-subject-select" name="subject">
						<option><?php _e('Un accompagnement', 'capstan'); ?></option>
						<option><?php _e('Une canditature', 'capstan'); ?></option>
						<option><?php _e('Un autre sujet', 'capstan'); ?></option>
						<option><?php _e('Presse', 'capstan'); ?></option>
					</select>
				</div>
				<div class="form">	
					<div class="questions">
						<?php _e("Bonjour à l’équipe Capstan,", 'capstan'); ?><br/>
						<?php _e('J\'aimerais être contacté par le bureau de ' , 'capstan'); ?>
						<div class="select message-type select-openable"><span class="preview"><?php _e('Paris', 'capstan'); ?></span> <i class="icon-up"></i>
							<select class="type message-type-select" name="type">
								<?php foreach ($offices as $office) { ?>
									<option><?php echo $office['title']; ?></option>
								<?php } ?>
							</select>
						</div><br />
						<?php _e("au sujet de  :", 'capstan'); ?><br />
						<div class="input-wrapper required"><textarea class="textarea message-content" name="message" placeholder="<?php _e('Votre message', 'capstan'); ?>" required></textarea></div>
					</div>

					<div class="answers">
						<?php _e("Je travaille chez ", 'capstan'); ?>
						<div class="input-wrapper"><input type="text" value="" name="name" placeholder="<?php _e('Nom de votre entreprise', 'capstan'); ?>" class="message-name"/></div>
						<?php _e(" au poste de  ", 'capstan'); ?>
						<div class="input-wrapper"><input type="text" value="" name="poste" placeholder="<?php _e('Nom de votre fonction', 'capstan'); ?>" class="message-poste"/></div><br/>
						<?php _e("Vous pouvez me joindre via l’adresse mail suivante", 'capstan'); ?>
						<div class="input-wrapper required"><input type="email" value="" placeholder="<?php _e('Votre email', 'capstan'); ?>" name="email" required class="message-mail"/></div> <br class="hide-mobile" />
						<?php _e("ou par téléphone au", 'capstan'); ?>
						<div class="input-wrapper"><input type="text" value="" name="telephone" placeholder="<?php _e('Votre téléphone', 'capstan'); ?>" class="message-phone"/></div>
					</div>

					<div class="end">
						<div class="select message-formule select-openable">
							<span class="preview">Cordialement</span> <i class="icon-up"></i>
							<select class="formule message-formule-select" name="formule">
								<option><?php _e('Cordialement', 'capstan'); ?></option>
								<option><?php _e('Bien à vous', 'capstan'); ?></option>
								<option><?php _e('À bientôt', 'capstan'); ?></option>
								<option><?php _e('Respectueusement', 'capstan'); ?></option>
								<option><?php _e('Bonne réception', 'capstan'); ?></option>
								<option><?php _e('See you', 'capstan'); ?></option>
								<option><?php _e('Regards', 'capstan'); ?></option>
								<option><?php _e('All the best', 'capstan'); ?></option>
							</select>
						</div>, 
						<div class="input-wrapper required"><input type="text" value="" name="person" required placeholder="<?php _e('Votre nom', 'capstan'); ?>" class="message-author"/></div>
					</div>
					<button type="submit"  class="submit link-arrow-top">
						<span class="overflow-h inline-block">
							<span class="inline-block inner">
								<?php _e("Envoyer votre message", 'capstan'); ?>
							</span>
						</span>
						<i class="icon-arrow-top"></i>
					</button>
				</div>
			</div>
		</form>
		<div class="feedback">
			<div class="text">
				<?php _e("Merci beaucoup pour votre message", 'capstan'); ?><br/>
				<?php _e("Nous nous recontacterons dans les meilleurs délais", 'capstan'); ?>
			</div>
			<div class="a-container">
				<span href="<?php the_permalink(); ?>" class="restart link-arrow-top">
					<span class="overflow-h inline-block">
						<span class="inline-block inner">
							<?php _e("Envoyer un nouveau message", 'capstan'); ?>
						</span>
					</span>
					<i class="icon-arrow-top"></i>
				</span>
			</div>
			<div class="a-container">
				<a href="<?php echo get_site_url(); ?>" class="link-arrow-top">
					<span class="overflow-h inline-block">
						<span class="inline-block inner">
							<?php _e("Retour à la page d'accueil", 'capstan'); ?>
						</span>
					</span>
					<i class="icon-arrow-top"></i>
				</a>
			</div>
		</div>

	</div>


</div>