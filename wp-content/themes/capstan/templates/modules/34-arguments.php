<div class="module module-34" data-background="none" data-component="Arguments34Component">


	<div class="nav">
		<div class="dots">
		</div>
	</div>

	<?php $items = get_field('arguments'); ?>
	<div class="container">
		<div class="slideshow">
			
			<div class="items">
				<?php foreach($items as $i => $item) { ?>
				<div class="item <?php echo $i == 0 ? 'active' : '' ?>">
					<div class="title"><?php echo $item['title']; ?></div>
				</div>
				<?php } ?>
			</div>

		</div>
		<div class="content">
			
			<div class="items">		
				<?php foreach($items as $i => $item) { ?>
				<div class="item <?php echo $i == 0 ? 'active' : '' ?> ">
					<div class="description"><?php echo $item['content']; ?></div>
				</div>
				<?php } ?>
			</div>

		</div>
	</div>


</div>