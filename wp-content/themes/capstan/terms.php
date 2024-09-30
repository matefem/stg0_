<?php
/*
Template Name: Page : Terms & Conditions
*/ ?>

<?php get_header(); ?>

<section class="page terms-and-condition">
	<?php getTemplate('partials/navigation'); ?>
	<?php getTemplate('partials/home-slideshow'); ?>

	<div class="content">
    	<?php the_content(); ?>
    </div>
</section>


<?php get_footer(); ?>