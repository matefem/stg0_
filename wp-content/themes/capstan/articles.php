<?php
	/*
	Template Name: Page : Articles
	*/
	get_header();

	$post = get_queried_object();

	$articles = get_posts(
		array(
			'orderby' => 'date',
			'order' => 'DESC',
			'posts_per_page' => 14
		));

	$types = get_terms("post-type");
	$themes = get_terms("post-theme");

	$user = Account::getUser();
?>


<section id="articles" class="page white" data-component="Articles">
	<div class="breadcrumb white" data-component="BreadcrumbComponent">
		<a href="<?php echo get_site_url(); ?>">Accueil</a> <i class="icon-arrow2"></i> <a href="<?php echo get_permalink(); ?>"><?php the_title() ?></a>
	</div>

	<div class="hero">
		<h1><?php the_title() ?></h1>
		<p><?php echo get_field("description", $post); ?></p>
	</div>

	<div class="article-filters">
		<ul class="filter-types">
			<li <?php if (!isset($_GET["type"]) || empty($_GET["type"])) echo 'class="selected"'; ?>>Tout voir</li>
			<?php foreach($types as $type) { ?>
				<li data-id="<?php echo $type->term_id; ?>" <?php echo (isset($_GET["type"]) && $_GET["type"] == $type->term_id) ? 'class="selected"' : '' ?>><?php echo $type->name; ?></li>
			<?php } ?>
		</ul>
	</div>

	<div class="columns">
		<div class="left filters">
			<form class="filters-forms">
				<div class="search">
					<input name="input" type="text" placeholder="<?php t('Recherche par nom...');?>" value="<?php echo @$_GET["terme"]; ?>" autocomplete="false">
					<i class="icon-search"></i>
				</div>
				<div class="themes list">
					<span class="value"><?php t('Tous les thèmes');?></span>
					<i class="icon-arrow-bottom"></i>
					<select>
						<option value=""><?php t('Tous les thèmes');?></option>
						<?php foreach($themes as $theme) { ?>
							<option value="<?php echo $theme->term_id; ?>"><?php echo $theme->name; ?></option>
						<?php } ?>
					</select>
					<ul>
						<li data-value=""><?php t('Tous les thèmes');?></li>
						<?php foreach($themes as $theme) { ?>
							<li <?php echo (isset($_GET["theme"]) && $_GET["theme"] == $theme->term_id) ? 'class="selected"' : '' ?> data-value="<?php echo $theme->term_id; ?>"><?php echo $theme->name; ?></li>
						<?php } ?>
					</ul>
				</div>
			</form>
			<div class="buttons-w">
			<?php if (!isset($user) || empty($user)) { ?>
				<a href="<?php echo get_permalink(getPageByFilename("profile.php"))?>" class="button-favoris">
					<i class="icon-favoris"></i>
					<span><?php t('Articles favoris'); ?></span>
				</a>
			<?php } else { ?>
				<button class="button-favoris">
					<i class="icon-favoris"></i>
					<span><?php t('Articles favoris'); ?></span>
				</button>
			<?php } ?>
				<a href="https://news.capstan.fr/articles/feed" class="button-rss" target="_blank" rel="noopenner">
					<i class="icon-rss"></i>
					<span><?php t("S'abonner au flux RSS"); ?></span>
				</a>
			</div>
		</div>
		<div class="right">
			<div class="top visible">
				<div class="column-title"><?php t('à la une');?></div>

				<div class="articles-grid">
					<?php foreach ($articles as $article) { ?>
						<?php getTemplate("partials/article-card", array("article" => $article)); ?>
					<?php } ?>
				</div>

			</div>
			<div class="bottom">
				<div class="result-articles articles-grid"></div>

				<div class="button-load-more">
					<button class="link-plus"><?php t('Charger plus d\'actualités'); ?> <i class="icon-plus"></i></button>
				</div>
			</div>
		</div>
	</div>

	<?php getTemplate("partials/article-app"); ?>

</section>


<?php get_footer(); ?>