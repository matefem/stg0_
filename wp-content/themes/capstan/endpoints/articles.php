<?php

if (!function_exists('capstan_filter_articles')) {

	function capstan_filter_articles() {
		$search = capstan_escape_value($_POST['search']);
		$theme = capstan_escape_value($_POST['theme']);
		$type = capstan_escape_value($_POST['type']);
		$notin 	= capstan_escape_value($_POST['notin']);
		$isLoadMore = capstan_escape_value($_POST['isLoadMore']);
		$favoris = capstan_escape_value($_POST['favoris']);
		if(!empty($_POST['max'])) $max 	= capstan_escape_value($_POST['max']);
		else $max = $isLoadMore == 'true' ? 9 : 8;


		$query = [
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => $max,
			'orderby' => 'date',
			'order' => 'DESC',
			'meta_query' => [],
			'tax_query' => []
		];

		if (!empty($search)) {
			$query['s'] = $search;
		}

		if (!empty($notin) && $isLoadMore == 'true') {
			$query['post__not_in'] = explode(',', $notin);
		}


		if (!empty($theme)) {
			$query['tax_query'][] = ['taxonomy' => 'post-theme', 'field' => '', 'terms' => $theme];
		}

		if (!empty($type)) {
			$query['tax_query'][] = ['taxonomy' => 'post-type', 'field' => 'id', 'terms' => $type];
		}

		if ($favoris == 'true') {
			$user = Account::getUser();
			if (isset($user["favorites"])) {
				$user["favorites"][] = 'issue#28099';
				$query['meta_query'][] = ['key' => 'capstan_news_id', 'value' => $user["favorites"], 'compare' => 'IN'];
			}
		}


		$wpQuery = new WP_query($query);
		$articles = $wpQuery->posts;
		$hasMorePages = $wpQuery->post_count < $wpQuery->found_posts;

		if ($hasMorePages) http_response_code(206);

		foreach ($articles as $article) {
			getTemplate("partials/article-card", array("article" => $article));
		}
		die();
	}
}

add_action( 'wp_ajax_nopriv_capstan_filter_articles', 'capstan_filter_articles' );
add_action( 'wp_ajax_capstan_filter_articles', 'capstan_filter_articles' );
?>