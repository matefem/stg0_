<?php

if (!function_exists('capstan_filter_careers')) {

	function capstan_filter_careers() {
		$city 	= capstan_escape_value($_POST['city']);
		$poste 	= capstan_escape_value($_POST['poste']);

		$query = Array(
			'post_type' => 'career',
			'post_status' => 'publish',
			'posts_per_page' => 4,
			'order' => 'ASC',
			'meta_query' => []
		);

		// print_r($city);
		// print_r($poste);

		if (!empty($city) && $city != 'all') {
			$officeQuery =  new WP_Query(Array(
				'post_type' => 'office',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'meta_query' => [[
					'key' => 'city',
					'value' => $city

				]]

			));

			if(!empty($officeQuery->posts)) {
				$officeId = $officeQuery->posts[0]->ID;
				$query['meta_query'][] = Array(
					'key' => 'place',
					'value' => $officeId
				);
			}
		}

		if (!empty($poste) && $poste != 'all') {
			$query['meta_query'][] = Array(
				'key' => 'title',
				'value' => $poste,
				'compare' => 'LIKE'
			);
		}

		$wpQuery = new WP_Query($query);

		$data = [];

		while($wpQuery->have_posts()) {
			$wpQuery->the_post();

			$postID = get_the_ID();
			$data[] = Array(
				// 'ID' 		=> $postID,
				'link'		=> get_permalink($postID),
				'title'		=> get_field('title', $postID),
				'contract'	=> get_field('contract', $postID),
				'city'		=> get_field('city', get_field('place', $postID)),
				'time'		=> round((time() - intval(get_the_time('U'))) / (3600 * 24) + 1)
			);
		}

		echo json_encode($data);
		die();
	}
}

add_action( 'wp_ajax_nopriv_capstan_filter_careers', 'capstan_filter_careers' );
add_action( 'wp_ajax_capstan_filter_careers', 'capstan_filter_careers' );
?>