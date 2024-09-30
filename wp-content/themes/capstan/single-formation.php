<?php
	get_header();
	$dates = get_field('dates');

	$calendar = new ICS(get_field('title'));
	if(!empty($dates)) foreach($dates as $date) {
		$start = DateTime::createFromFormat('d/m/Y H\hi', trim($date['day']).' '.trim($date['hour_start']));
		$end = DateTime::createFromFormat('d/m/Y H\hi', trim($date['day']).' '.trim($date['hour_end']));

		if(!empty($start) && !empty($end))
			$calendar->add($start->format('U'), $end->format('U'), get_field('title'), get_field('title'), get_permalink());
	}
?>

<section class="page white">

	<?php getTemplate('partials/contact'); ?>

    <div class="module module-29" data-background="none" data-component="Formation29Component">

		<div class="hero">
			<picture>
				<source srcset="<?php echo get_field('image')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
				<img src="<?php echo get_field('image')['sizes']['mobile'] ?>" alt="" draggable="false">
			</picture>
			<div class="overlay"></div>
			<div class="text">
				<div class="title">
					<?php the_field('title'); ?>
				</div>
				<div class="infos">
					<div class="city">
						<div class="image">
							<img src="./wp-content/themes/capstan/resources/assets/img/icons/geopoint.svg"/>
						</div>
						<div class="info-text"><?php the_field('city', get_field('office')); ?></div>
					</div>
					<div class="year">
						<div class="image">
							<img src="./wp-content/themes/capstan/resources/assets/img/icons/calendar.svg"/>
						</div>
						<div class="info-text">
							<?php if(count(explode('/', $dates[0]['day'])) > 2) { echo explode('/', $dates[0]['day'])[2]; } ?>
						</div>
					</div>
					<div class="name">
						<div class="image">
							<img src="./wp-content/themes/capstan/resources/assets/img/icons/formation.svg"/>
						</div>
						<div class="info-text"><?php echo (get_field('type') == 'multi' ? __('Formation récurrente') : __('Formation ponctuelle')) ?></div>
					</div>
				</div>
				<button class="subscribe">S'inscrire</button>
			</div>
		</div>

		<div class="main">

			<div class="left">

				<div class="dates">
					<div class="title"><?php _e('Dates de la formation', "capstan"); ?></div>
					<ul>
						<?php if(!empty($dates)) foreach($dates as $date) {
							$datetime = DateTime::createFromFormat("d/m/Y", trim($date['day']));
						?>
						<li>
							<span class="date"><?php echo french_date(strftime("%A %d %B %Y", $datetime->format('U'))); ?></span>
							<span class="time"><?php echo $date['hour_start']; ?> - <?php echo $date['hour_end']; ?></span>
						</li>
						<?php } ?>
					</ul>
				</div>

				<div class="formateurs">
					<div class="title"><?php _e('Formateurs', 'capstan'); ?></div>
					<ul>
						<?php if(!empty(get_field('tutors'))) foreach(get_field('tutors') as $tutor) {
							$isLawyer = $tutor['isLawyer'] == '1';
							$id = $tutor['tutor'];
						?>
						<li>
							<div class="image">
								<picture>
									<img src="<?php echo $isLawyer ? get_field('image', $id)['sizes']['medium'] :  $tutor['external']['image']['sizes']['medium']; ?>" alt="" draggable="false">
								</picture>
							</div>
							<div class="infos">
								<div class="name"><?php echo $isLawyer ? get_field('name', $id) : $tutor['external']['name']; ?></div>
								<div class="job"><?php echo $isLawyer ? get_field('status', $id) : $tutor['external']['status']; ?></div>
								<div class="icons">
									<?php
									if($isLawyer) {
										$socials = get_field('socials', $id);
										if(!empty($socials['phone'])) { ?>
											<a href="tel:<?php echo str_replace(' ', '', $socials['phone']);?>"><i class="icon-phone"></i></a>
										<?php }

										if(!empty($socials['mail'])) { ?>
											<a href="mailto:<?php echo $socials['mail'];?>"><i class="icon-mail"></i></a>
										<?php }

										if(!empty($socials['linkedin'])) { ?>
											<a href="<?php echo $socials['linkedin']['url'];?>" target="<?php echo $socials['linkedin']['target']; ?>"><i class="icon-in"></i></a>
										<?php }
									} else {
										if(!empty($tutor['external']['phone'])) { ?>
											<a href="tel:<?php echo str_replace(' ', '', $tutor['external']['phone']);?>"><i class="icon-phone"></i></a>
										<?php }

										if(!empty($tutor['external']['mail'])) { ?>
											<a href="mailto:<?php echo $tutor['external']['mail'];?>"><i class="icon-mail"></i></a>
										<?php }
									} ?>
								</div>
							</div>
						</li>
						<?php } ?>
					</ul>
				</div>

				<div class="infos adress">
					<div class="title"><?php _e('Adresse', 'capstan'); ?></div>
					<ul>
						<li><span class="name"><?php _e('Adresse', 'capstan'); ?></span><span class="value"><?php echo get_field('adress')['adress']; ?></span></li>
						<?php if(!empty(get_field('adress')['phone'])) { ?>
						<li><span class="name"><?php _e('Téléphone', 'capstan'); ?></span><span class="value"><?php echo get_field('adress')['phone']; ?></span></li>
						<?php } ?>
						<?php if(!empty(get_field('adress')['phone'])) { ?>
						<li><span class="name"><?php _e('Mail', 'capstan'); ?></span><span class="value"><?php echo get_field('adress')['mail']; ?></span></li>
						<?php } ?>
					</ul>
					<a href="<?php echo get_field('adress')['map_link']; ?>"><?php _e("Voir l'itinéraire ", 'capstan'); ?><i class="icon-arrow"></i></a>
				</div>

			</div>

			<div class="right">

				<div class="infos">
					<div class="description"><?php the_field('description'); ?></div>
					<div class="title"><?php the_field('title'); ?></div>
					<?php if(!empty(get_field('files'))) {
						foreach(get_field('files') as $file) {
					?>
						<a href="<?php echo $file['file']; ?>" target="_blank"><?php echo $file['titre'] ?><i class="icon icon-arrow-top"></i></a><br/>
					<?php }} ?>

					<?php if(!empty(get_field('label')['logo'])) { ?>
						<div class="label">
							<img src="<?php echo get_field('label')['logo']['url']; ?>" alt="<?php echo get_field('label')['logo']['alt']; ?>" draggable="false">
							<?php if(!empty(get_field('label')['percent']) || !empty(get_field('label')['title'])) { ?>
								<div class="text">
									<?php if(!empty(get_field('label')['percent'])) { ?>
										<span class="percent"><?php echo get_field('label')['percent']; ?>% </span>
									<?php }
									if(!empty(get_field('label')['title'])) { ?>
										<span class="content"> <?php echo get_field('label')['title']; ?></span>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>

				<div class="planning">
					<div class="title"><?php _e('Dates et programme', 'capstan'); ?></div>
					<div class="list">
					<?php if(!empty($dates)) foreach($dates as $date) {
						$datetime = DateTime::createFromFormat("d/m/Y", trim($date['day']));
					?>
						<div class="date"><?php echo french_date(strftime("%A %d %B %Y", $datetime->format('U'))); ?></div>
						<ul>
						<?php foreach($date['subjects'] as $subject) { ?>
							<li><?php echo $subject['text']; ?></li>
						<?php } ?>
						</ul>
					<?php } ?>
					</div>
				</div>

				<div class="form inscription-form" data-id="<?php the_ID(); ?>" data-mailto="<?php the_field('mail'); ?>">
					<div class="title"><?php _e("Demande d’information et inscription", 'capstan'); ?></div>
					<form>
						<div class="input">
							<input type="email" name="email" placeholder="Email" class="input-mail" required/>
						</div>
						<div class="input">
							<input type="text" name="firstname" placeholder="Prénom"  class="input-firstname" required/>
						</div>
						<div class="input">
							<input type="text" name="lastname" placeholder="Nom"  class="input-lastname" required/>
						</div>
						<div class="input">
							<input type="text" name="business" placeholder="Entreprise"  class="input-business"/>
						</div>
						<div class="input">
							<input type="text" name="role" placeholder="Rôle"  class="input-role"/>
						</div>
						<button type="submit">
							<?php _e("S'enregistrer", 'capstan'); ?><i class="icon icon-arrow-top"></i>
						</button>
					</form>
				</div>

				<div class="form confirm">
					<div class="title"><?php _e('Félicitations, votre inscription a bien été prise en compte.', 'capstan'); ?></div>
					<div class="description"><?php _e('Vous recevrez un mail de confirmation dans quelques minutes.', 'capstan'); ?></div>

					<div class="add">
						<span><?php _e('Vous pouvez ajouter cette formation à votre calendrier.', 'capstan'); ?></span>
						<a href="#" class="add-formation-to-calendar" data-info="<?php echo htmlspecialchars($calendar->getData()) ?>" download="<?php echo $post->post_name; ?>.ics"><?php _e('Ajouter au calendrier', 'capstan'); ?> <i class="icon icon-arrow-top"></i></a>
					</div>

				</div>

			</div>

		</div>

	</div>

</section>


<?php get_footer(); ?>