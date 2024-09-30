<?php

if (!function_exists('capstan_filter_lawyers')) {

	function capstan_filter_lawyers() {

		$search = capstan_escape_value($_POST['search']);
		$office = capstan_escape_value($_POST['office']);
		$status = capstan_escape_value($_POST['status']);
		$notin 	= capstan_escape_value($_POST['notin']);
		if(!empty($_POST['max'])) $max 	= capstan_escape_value($_POST['max']);
		else $max = 8;

		$query = Array(
			'post_type' => 'lawyer',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order' => 'rand',
			'meta_query' => []
		);

		if (!empty($search)) {
			$query['meta_query'][] = Array(
				'key' => 'name',
				'value' => $search,
				'compare' => 'LIKE'
			);
		}
		if (!empty($office)) {
			$query['meta_query'][] = Array(
				'key' => 'office',
				'value' => $office
			);
		}
		if (!empty($status)) {
			$query['meta_query'][] = Array(
				'key' => 'function',
				'value' => $status,
				'compare' => 'LIKE'
			);
		}

		$countNotin = 0; 

		if (!empty($notin)) {
			$query['post__not_in'] = explode(',', $notin);
			$countNotin = count($query['post__not_in']);
		}


		$wpQuery = new WP_Query($query);

		$data = Array(
			'total' => count($wpQuery->posts) + $countNotin,
			'lawyers' => Array(),
		);

		$allLawyers = Array();
		foreach(getAllStaffFunctions() as $key => $value) {
			$allLawyers[$key] = Array();
		}

		while($wpQuery->have_posts()) {
			$wpQuery->the_post();

			$postID = get_the_ID();
			$officeID = get_field('office', $postID);
			$infos = Array(
				'ID' 	 		=> $postID,
				'title'  		=> get_field('name', $postID),
				'status' 		=> get_field('status', $postID),
				'function' 		=> get_field('function', $postID),
				'image'  		=> get_field('image', $postID)['sizes']['medium_large'],
				'alt' 	 		=> get_field('image', $postID)['alt'],
				'url'	 		=> get_permalink($postID),
				'socials' 		=> get_field('socials', $postID),
				'officeName'	=> strip_tags(get_field('title', $officeID)),
				'officeUrl'		=> get_permalink($officeID)
			);

			$allLawyers[$infos['function']['value']][] = $infos;
		}

		$n = 0;
		foreach($allLawyers as $value) {
			$i = 0;
			while($i < count($value) && $n < $max) {
				$data['lawyers'][] = $value[$i];
				$i++;
				$n++;
			}
		}

		wp_reset_postdata();

		echo json_encode($data);
		die();
	}
}

add_action( 'wp_ajax_nopriv_capstan_filter_lawyers', 'capstan_filter_lawyers' );
add_action( 'wp_ajax_capstan_filter_lawyers', 'capstan_filter_lawyers' );
?>