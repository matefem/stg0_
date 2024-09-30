<?php get_header(); ?>

<?php
	$art = get_queried_object();

    $thumbnail = get_post_thumbnail_id($art->ID);
	$video = get_field("video", $art);
	$category = get_the_category($art->ID)[0]->slug;

	$articlesRandom = get_posts(
		array(
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => 4,
			'exclude'  => $art->ID
	));
?>


<section id="article" class="page white" data-component="Article" data-id="<?php echo $art->ID; ?>">
	<div class="breadcrumb white" data-component="BreadcrumbComponent">
		<a href="<?php echo get_site_url(); ?>">Accueil</a> <i class="icon-arrow2"></i>
		<a href="<?php echo get_permalink(getPageByFilename("articles.php")); ?>"><?php t('Capstan news'); ?></a> <i class="icon-arrow2"></i>
		<a href="<?php echo get_permalink(); ?>"><?php echo mb_substr(str_replace("\n", '', strip_tags(trim(html_entity_decode($art->post_title, ENT_QUOTES, 'UTF-8')))), 0, 200)."..."; ?></a>
	</div>

	<?php getTemplate('partials/contact'); ?>


	<div class="post-head">
		<h3 class="post-type"><?php echo @get_the_terms($art, "post-type")[0]->name; ?></h3>
		<h1 class="post-title"><?php echo get_the_title(); ?></h1>

		<?php getTemplate("partials/article-authordate", array("article" => $art)); ?>

		<?php if (!empty($video)) { ?>
			<div class="video <?php echo $category; ?>">
				<iframe src="<?php echo $video; ?>" frameborder="0" allow="autoplay"></iframe>
			</div>
		<?php }
		else if (!empty($thumbnail)) { ?>
			<picture class="picture">
				<source srcset="<?php echo wp_get_attachment_image_src($thumbnail, 'large')[0]; ?>" media="(min-width: 960px)">
				<img src="<?php echo wp_get_attachment_image_src($thumbnail)[0]; ?>" alt="" draggable="false">
			</picture>
		<?php } ?>
	</div>

	<div class="post-content">
		<div class="post-themes">
			<?php if (!empty(get_the_terms($art, "post-theme"))) {
				foreach(get_the_terms($art, "post-theme") as $theme) {
				echo '<span>'.$theme->name.'</span>';
			}} ?>
		</div>

		<?php the_content($art); ?>


		<?php getTemplate("partials/article-authordate", array("article" => $art)); ?>
		<?php getTemplate("partials/article-files", array("article" => $art)); ?>
	</div>


	<div class="post-random">
		<div class="random-content">
			<h4><?php t('Vous pourriez <i>également</i> lire...') ;?></h4>
			<?php foreach ($articlesRandom as $article) { ?>
					<?php getTemplate("partials/article-card", array("article" => $article, "classes" => 'no-separator-desktop left-picture-desktop')); ?>
			<?php } ?>
		</div>

		<?php getTemplate("partials/article-app"); ?>
	</div>


	<?php getTemplate("partials/article-readingbar", array("article" => $art)); ?>


</section>


<?php get_footer(); ?>