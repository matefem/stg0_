<?php

if (!function_exists('capstan_filter_formations')) {

	function capstan_filter_formations() {
		$search = capstan_escape_value($_POST['search']);
		$office = capstan_escape_value($_POST['office']);
		$type = capstan_escape_value($_POST['type']);
		$notin 	= capstan_escape_value($_POST['notin']);

		$limit = 8;

		$query = Array(
			'post_type' => 'formation',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'meta_query' => []
		);

		if (!empty($search)) {
			$query['s'] = $search;
		}
		if (!empty($office)) {
			$query['meta_query'][] = Array(
				'key' => 'office',
				'value' => $office
			);
		}
		if (!empty($type)) {
			$query['meta_query'][] = Array(
				'key' => 'type',
				'value' => $type,
				'compare' => 'LIKE'
			);
		}

		if (!empty($notin)) {
			$query['post__not_in'] = explode(',', $notin);
		}


		$wpQuery = new WP_Query($query);
		$postCount = $wpQuery->post_count;

		// $count = wp_count_posts('lawyer')->publish;

		$now = new DateTime('NOW');

		$data = Array(
			'havemore' => false,
			'formations' => Array(),
		);

		$count = 0;
		$k = 0;
		$maxLoop = 0;
		while($k < $limit) {
			$wpQuery->the_post();

			$postID = get_the_ID();

			$date = get_field('dates', $postID);
			$datetime = DateTime::createFromFormat("d/m/Y", trim($date[0]['day']));
			$almostOneDate = false;

			$first = DateTime::createFromFormat("d/m/Y", trim($date[0]['day']));

			for($i = 0; $i < count($date);$i++) {
				$d = DateTime::createFromFormat("d/m/Y", trim($date[$i]['day']));

				if(intval($d->diff($now)->format('%R%a')) <= 0) {
					$almostOneDate = true;
				}
			}

			if($almostOneDate) {
				$infos = Array(
					'ID' 	 		=> $postID,
					'title'  		=> get_field('title', $postID),
					'type'   		=> get_field('type', $postID),
					'description'   => get_field('description', $postID),
					'subtitle' 		=> '',
					'office' 		=> get_field('office', $postID),
					'image'  		=> get_field('image', $postID)['sizes']['medium_large'],
					'alt' 	 		=> get_field('image', $postID)['alt'],
					'dates'			=> get_field('dates', $postID),
					'url' 			=> get_permalink($postID)
				);

				$infos['office'] = get_field('city', $infos['office']);

				if($infos['type'] == 'multi') {
					$infos['subtitle'] = 'Formation récurrente';

					for($i = 0;$i < count($infos['dates']);$i++) {
						$datetime = DateTime::createFromFormat("d/m/Y", trim($infos['dates'][$i]['day']));

						$d = Array(
							french_date(strftime("%A %d %B %Y", $datetime->format('U'))),
							$infos['dates'][$i]['hour_start'].' - '.$infos['dates'][$i]['hour_end']
						);
						$infos['dates'][$i] = $d;
					}
				} else {
					$infos['subtitle'] = french_date(strftime("%d %B %Y", $datetime->format('U')));
					$infos['dates'] = 'de '.$infos['dates'][0]['hour_start'].' à '.$infos['dates'][0]['hour_end'];
				}

				$data['formations'][] = $infos;
				$k++;
			}
			$count++;
			$maxLoop ++;

			if ($maxLoop >= $postCount) break;
		}
		wp_reset_postdata();


		$data['havemore'] = $postCount > $count;

		echo json_encode($data);
		die();
	}
}

add_action( 'wp_ajax_nopriv_capstan_filter_formations', 'capstan_filter_formations' );
add_action( 'wp_ajax_capstan_filter_formations', 'capstan_filter_formations' );
?>