<?php
/*
Template Name: Page : Home
*/ ?>

<?php get_header(); ?>

<section class="page">
	<?php getTemplate('partials/navigation'); ?>
	<?php getTemplate('partials/home-slideshow'); ?>

    <?php the_content(); ?>
</section>


<?php get_footer(); ?>