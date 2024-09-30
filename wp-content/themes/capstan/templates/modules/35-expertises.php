<div class="module module-35" data-component="Expertise35Component" data-background="none">

	<div class="content">
		
		<div class="liste">
			<?php 
			$elems = get_field('expertises');
			for($i = 0;$i < count($elems);$i++) {
				$elem = $elems[$i];
			?>
			<div class="item">
				<div class="image">

					<div class="image-container">
						<picture>
							<source srcset="<?php echo $elem['image']['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
							<img src="<?php echo $elem['image']['sizes']['medium_large'] ?>" alt="<?php echo $elem['image']['alt'] ?>" draggable="false">
						</picture>

						<?php 
						if($elem['redlayer']=="1") { ?>
						<img src="<?php echo get_template_directory_uri().'/resources/assets/img/module/filter3.png'; ?>" alt="" draggable="false" class="overlay-filter">
						<?php } ?>
					</div>

				</div>

				<div class="infos">
					<div class="text">
						<div class="number"><?php echo (($i+1)<10?'0':'').($i+1) ?></div>
						<div class="title"><?php echo $elem['title'] ?></div>
					</div>
					<div class="inner">
						<div class="description"><?php echo $elem['description'] ?></div>
						<ul>
							<?php 
							foreach ($elem['domains'] as $domain) { ?>
								<li><i class="icon-circle"></i><?php echo $domain['domain'] ?></li>
							<?php }?>
						</ul>
						<?php if($elem['link']) { ?>
							<a href="<?php echo $elem['link']['url']; ?>" target="<?php echo $elem['link']['target']; ?>" draggable="false"><?php echo $elem['link']['title']; ?><i class="icon-arrow"></i></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php }?>
		</diV>
	</div>
</div>