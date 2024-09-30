<?php
/**
 * The template for displaying the 404 template in the Twenty Twenty theme.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

<main id="site-content" role="main" class="page-404">
	<div class="section-inner thin error404-content">
		<div class="title">404</div>
		<div class="content">
			<h2><?php _e("Cette page n'existe pas...", 'capstan'); ?></h2>
			<p><?php _e("Nous nous excusons pour le désagrément", 'capstan'); ?></p>
			<a href="<?php echo get_site_url(); ?>" class="link-plus"><?php __("RETOUR À L'ACCUEIL", 'capstan'); ?><i class="icon-right"></i></a>
		</div>
	</div>
</main>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php
get_footer();
