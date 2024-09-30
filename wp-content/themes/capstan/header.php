<?php
	if(!is_ajax()) {?>
	<!doctype html>
		<html <?php language_attributes(); ?>>
		<head>

			<!-- Google Tag Manager -->
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-P7B74K');</script>
			<!-- End Google Tag Manager -->

			<base href="<?php echo home_url(); ?>/"/>
			<link rel="preconnect" href="<?php echo current_location(); ?>" crossorigin />

			<meta charset="<?php bloginfo( 'charset' ); ?>" />
			<meta name="robots" content="noindex, nofollow">
			<meta name="referrer" content="no-referrer">
			<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
			<meta name="author" content="60fps">

			<meta name="apple-itunes-app" content="app-id=id1258806290">
			<meta name="google-play-app" content="app-id=weblogin.capstannews">

			<meta name="smartbanner:title" content="Capstan news">
			<meta name="smartbanner:author" content="Capstan news">
			<meta name="smartbanner:price" content="FREE">
			<meta name="smartbanner:price-suffix-apple" content=" - On the App Store">
			<meta name="smartbanner:price-suffix-google" content=" - In Google Play">
			<meta name="smartbanner:icon-apple" content="icon-capstan-news.png">
			<meta name="smartbanner:icon-google" content="icon-capstan-news.png">
			<meta name="smartbanner:button" content="VIEW">
			<meta name="smartbanner:button-url-apple" content="https://apps.apple.com/fr/app/capstan-news/id1258806290">
			<meta name="smartbanner:button-url-google" content="https://play.google.com/store/apps/details?id=weblogin.capstannews">
			<meta name="smartbanner:enabled-platforms" content="android,ios">
			<meta name="smartbanner:close-label" content="Close">

			<link rel="alternate" type="application/rss+xml" title="Capstan News" href="https://news.capstan.fr/articles/feed" />

			<style>.smartbanner{position: fixed; z-index:1000}</style>

			<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

			<?php

				$title = '';
				$description = '';
				$picture = '';

				$post = get_post();
				if(!empty($post->post_type)) {
					switch($post->post_type) {
						case 'lawyer':
							$title = get_field('name').' - Capstan Avocats';
							$picture = get_field('image')['sizes']['medium'];
							$description = str_replace("\n", '', strip_tags(get_field('description')));
							break;
						case 'office':
						case 'career':
						case 'formation':
							$title = str_replace("\n", '', strip_tags(get_field('title'))).' - Capstan Avocats';
							if (!empty(get_field('image'))) $picture = get_field('image')['sizes']['medium'];
							$description = str_replace("\n", '', strip_tags(get_field('description')));
							break;
						case 'post':
							$title = str_replace("\n", '', strip_tags($post->post_title));
							$picture = @wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'mobile')[0];
							$description = str_replace("\n", '', strip_tags($post->post_excerpt));
							break;

					}
				}
			?>

			<?php if($description != '') { ?>
				<meta name="description" content="<?php echo $description; ?>" />
				<meta property="og:description" content="<?php echo $description; ?>" />
			<?php } ?>
			<?php if($title != '') { ?>
				<meta property="og:title" content="<?php echo $title;?>" />
			<?php } ?>
			<?php if($picture != '') { ?>
				<meta property="og:image" content="<?php echo $picture;?>" />
			<?php } ?>


			<?php wp_head(); ?>

		</head>

		<body <?php body_class(); ?>>

			<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P7B74K" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->

			<?php wp_body_open(); ?>
			<?php getTemplate('partials/header'); ?>
			<style type="text/css">
				#loader {
					position: fixed;
					top: 0;
					left: 0;
					z-index: 10;
					width: 100%;
					height: 100%;
					display: flex;
					align-items: center;
					justify-content: center;
					overflow: hidden
				}

				#loader .overlay {
					position: absolute;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					will-change: transform;
					transform-origin: center;
				}

				#loader .overlay-red {
					background: #E62612;
				}
				#loader .overlay-dark {
					background: #222222;
				}
			</style>
			<div id="loader">
				<div class="overlay-red overlay"></div>
				<div class="overlay-dark overlay"></div>
			</div>
			<div id="root" class="content">
<?php } ?>