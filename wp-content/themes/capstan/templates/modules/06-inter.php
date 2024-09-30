<div class="module module-06" data-background="dark">
	
	<div class="title" data-animation="titleReveal">
		<?php echo get_field('title'); ?>
	</div>

	<?php if(get_field('link')): ?>
	<a href="<?php echo get_field('link')['url']; ?>" class="link-arrow-top" data-animation="linkArrowTopReveal" target="<?php echo get_field('link')['target']; ?>">
		<span class="overflow-h inline-block">
			<span class="inline-block inner"><?php echo get_field('link')['title']; ?></span>
		</span> 
		<i class="icon-arrow-top"></i>
	</a>
	<?php endif; ?>
	
	<div class="globe">
		<div class="globe-canvas">
			<img src="/wp-content/themes/capstan/resources/assets/img/visuel/earth.png"/>
		</div>
		<div class="carrousel v-align">
			

			<div>
				<?php
				$countries = Array(
					__('Biélorussie', 'capstan'),
					__('Bulgarie', 'capstan'),
					__('Croatie', 'capstan'),
					__('République Tchèque', 'capstan'),
					__('Estonie', 'capstan'),
					__('Hongrie', 'capstan'),
					__('Kazakhstan', 'capstan'),
					__('Lettonie', 'capstan'),
					__('Lituanie', 'capstan'),
					__('Pologne', 'capstan'),
					__('Russie', 'capstan'),
					__('Slovaquie', 'capstan'),
					__('Ukraine', 'capstan'),
					__('Argentine', 'capstan'),
					__('Brésil', 'capstan'),
					__('Chili', 'capstan'),
					__('Colombie', 'capstan'),
					__('Mexique', 'capstan'),
					__('Pérou', 'capstan'),
					__('Venezuela', 'capstan'),
					__('Australie', 'capstan'),
					__('Bahreïn', 'capstan'),
					__('Chine', 'capstan'),
					__('Inde', 'capstan'),
					__('Israel', 'capstan'),
					__('Japon', 'capstan'),
					__('Nouvelle-Zélande', 'capstan'),
					__('Arabie Saoudite', 'capstan'),
					__('Singapour', 'capstan'),
					__('Corée du Sud', 'capstan'),
					__('Thaïlande', 'capstan'),
					__('Turquie', 'capstan'),
					__('Émirats Arabes Unis', 'capstan'),
					__('Canada', 'capstan'),
					__('États-Unis', 'capstan'),
					__('Autriche', 'capstan'),
					__('Belgique', 'capstan'),
					__('Chypre', 'capstan'),
					__('Danemark', 'capstan'),
					__('Finlande', 'capstan'),
					__('Allemagne', 'capstan'),
					__('Grèce', 'capstan'),
					__('Irlande', 'capstan'),
					__('Italie', 'capstan'),
					__('Luxembourg', 'capstan'),
					__('Malte', 'capstan'),
					__('Norvège', 'capstan'),
					__('Portugal', 'capstan'),
					__('Pays-Bas', 'capstan'),
					__('Roumanie', 'capstan'),
					__('Serbie', 'capstan'),
					__('Slovénie', 'capstan'),
					__('Espagne', 'capstan'),
					__('Suède', 'capstan'),
					__('Suisse', 'capstan'),
					__('Royaume-Uni', 'capstan')
				);
				$length	  = count($countries);
				$evenLength	= $length - $length % 2;

				for($i = 0;$i < 3;$i++) { ?>
				<div class="line">
					<ul>
						<?php
						for($j = 0;$j < $evenLength / 3; $j++) { ?>
							<li><span><?php echo $countries[($j + floor($i * $length / 3)) % $length]; ?></span></li>
						<?php }

						for($j = 0;$j < $evenLength; $j++) { ?>
							<li><span><?php echo $countries[($j + floor($i * $length / 3)) % $length]; ?></span></li>
						<?php } ?>
					</ul>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>

</div>