<?php

if (!function_exists('capstan_pageview_article')) {

	function capstan_pageview_article() {
		$id = capstan_escape_value($_POST['postid']);

		if ($id) {
			$caspstanId = get_field("capstan_news_id", $id);

			require_once(__DIR__."/../../../plugins/news-synchronisation_60fps/api.php");
			$api = new API();
			$api->getArticle($caspstanId);

		}
		die();
	}
}

add_action( 'wp_ajax_nopriv_capstan_pageview_article', 'capstan_pageview_article' );
add_action( 'wp_ajax_capstan_pageview_article', 'capstan_pageview_article' );
?>