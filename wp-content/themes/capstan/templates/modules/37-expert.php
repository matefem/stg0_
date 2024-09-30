<div class="module module-37" data-background="dark">

	<div class="content">
		
		<div class="liste">
			<?php
			$experts = get_field('experts');
			for($i = 0;$i < count($experts);$i++) {
				if($i % 7 == 3) { ?>
				<div class="item empty"></div>
				<div class="item empty"></div>
				<?php }
				$expert = $experts[$i]; ?>
				<div class="item">
					<div class="number overflow-h" data-animation="YReveal"><div><?php echo (($i+1)<10?'0':'').($i+1) ?> </div> </div>
					<div class="text">
						<div class="subtitle"><?php echo $expert['title'] ?></div>
						<div class="description"><?php echo $expert['description'] ?></div>
					</div>
				</div>
			<?php } ?>
		</diV>
	</div>

</div>