<div class="module module-25" data-background="dark">
	<div class="content">
		<?php
		foreach(get_field('elements') as $elem) { ?>
		<div class="item">
			<div class="texte">
				<div class="title" data-animation="titleReveal"><?php echo $elem['title']; ?></div>
				<div class="description"><?php echo $elem['content']; ?></div>

				<?php 
				if($elem['use_document']) { ?>
				<div class="document">
					<div class="image overflow-h">
						<picture>
							<img src="<?php echo $elem['document']['image']['sizes']['mobile']; ?>" alt="" draggable="false">
						</picture>
					</div>
					<div class="infos">
						<a href="<?php echo $elem['document']['file']['url']; ?>" class="subtitle"><?php echo $elem['document']['title']; ?></a>
						<div class="subdescription"><?php echo $elem['document']['description']; ?></div>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="image">
				<picture>
					<img src="<?php echo $elem['image']['sizes']['mobile']; ?>" alt="<?php echo $elem['image']['alt']; ?>" draggable="false">
				</picture>

				<?php 
				if(isset($elem['redlayer']) && $elem['redlayer']=="1") { ?>
				<img src="<?php echo get_template_directory_uri().'/resources/assets/img/module/filter3.png'; ?>" alt="" draggable="false" class="overlay-filter">
				<?php } ?>

			</div>

		</div>
		<?php } ?>
	</div>
</div>