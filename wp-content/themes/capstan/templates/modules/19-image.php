<?php 
if(get_field('animation')=="1") {
	$animation = "";
}
else {
	$animation = "no-animation";
}

if(get_field('head')=="1") {
	$head = "is-head";
}
else {
	$head = "";
}

?>
<div class="module module-19 <?php echo $animation; ?> <?php echo $head; ?>" data-background="none">
	
	<div class="content">

		<div class="image">
			<picture>
				<source srcset="<?php echo get_field('image')['sizes']['1536x1536'] ?>" media="(min-width: 960px)" type="image/jpeg">
				<img src="<?php echo get_field('image')['sizes']['mobile'] ?>" alt="<?php echo get_field('image')['alt'] ?>" draggable="false">
			</picture>

			<?php 
			if(get_field('redlayer')=="1") { ?>
			<img src="<?php echo get_template_directory_uri().'/resources/assets/img/module/filter.png'; ?>" alt="" draggable="false" class="overlay-filter">
			<?php } ?>
		
		</div>

		<?php 
		$title = get_field('title');

		if($title!="") { ?>
		<div class="overlay"></div>
		<?php } ?>

		<div class="text v-align">
			<div class="title" data-animation="titleReveal"><?php the_field('title'); ?></div>
		</div>
		<?php
		$keys = get_field('keywords');
		if($keys && count($keys) > 0) { ?>
		<div class="arguments">
			<ul>
				<?php

				$t=0;

				for($i = 0;$i < 4;$i++) {
					foreach ($keys as $j => $word) {
						if(!empty($word) && !empty($word['word'])) {?>
						<li class="<?php echo ($t%2)?'red':''; ?>"><?php echo $word['word']?></li>
				<?php $t++; }}
				} ?>
			</ul>
		</div>
		<?php } ?>

	</div>

</div>