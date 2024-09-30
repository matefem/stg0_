<?php


if (!function_exists('is_ajax')) {
	function is_ajax() {
		return ! empty( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ]) == 'xmlhttprequest';
	}
}

function getTemplate($name, $params = array(), $return = false) {
	if ($return) {ob_start();}
	set_query_var('params', $params);
	get_template_part('templates/' . $name);

	if ($return) {
		$output = ob_get_clean();
		return $output;
	}
}

function getParams($defaultParams = array()) {
	$p = get_query_var('params');
	if (empty($p)) {$p = array();}
	return array_merge($defaultParams, $p);
}

function current_location() {
    if (isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
        $protocol = 'https://';
    } else {
        $protocol = 'http://';
    }
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}


if (!function_exists('capstan_escape_value')) {
	function capstan_escape_value($data) {
		if(empty($data)) return null;
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
}

if (!function_exists('getPageByFilename')) {
	function getPageByFilename($filename = '', $parent = -1) {
		if ($parent != -1) {
			$pageParent = getPageByFilename($parent);
			if (isset($pageParent) && isset($pageParent->ID)) {$parent = $pageParent->ID;}
			else {$parent = -1;}
		}

		$args = array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $filename,
			'parent' => $parent
		);
		$pages = get_pages($args);

		if (sizeof($pages) == 1) {
			return $pages[0];
		}
		return $pages;
	}
}


function french_date($str) {
	if(get_locale() == 'en_US') return $str;
	$str = mb_strtolower($str);

	$str = str_replace("monday", "Lundi", $str);
	$str = str_replace("tuesday", "Mardi", $str);
	$str = str_replace("wednesday", "Mercredi", $str);
	$str = str_replace("thursday", "Jeudi", $str);
	$str = str_replace("friday", "Vendredi", $str);
	$str = str_replace("saturday", "Samedi", $str);
	$str = str_replace("sunday", "Dimanche", $str);

	$str = str_replace("january", "janvier", $str);
	$str = str_replace("february", "f&eacute;vrier", $str);
	$str = str_replace("march", "mars", $str);
	$str = str_replace("april", "avril", $str);
	$str = str_replace("may", "mai", $str);
	$str = str_replace("june", "juin", $str);
	$str = str_replace("july", "juillet", $str);
	$str = str_replace("august", "août", $str);
	$str = str_replace("september", "septembre", $str);
	$str = str_replace("october", "octobre", $str);
	$str = str_replace("november", "novembre", $str);
	$str = str_replace("december", "d&eacute;cembre", $str);

	return utf8_encode($str);
}

if ( ! function_exists( 'capstan_template_redirect' ) ) {
	function capstan_template_redirect() {
		if ( is_ajax() ) {
			header( 'X-WP-BODYCLASSES: ' . implode( " ", get_body_class() ) );
			header( 'X-Meta-Title: ' . utf8_uri_encode( wp_get_document_title() ) );
		}
	}
}

add_action( 'template_redirect', 'capstan_template_redirect' );

function getAllStaffFunctions() {
	return Array(
			'1_associate' => __('Avocat Associé', 'capstan'),
			'2_counsel' => __('Avocat of Counsel', 'capstan'),
			'3_senior' => __('Avocat Senior', 'capstan'),
			'4_lawyer' => __('Avocat', 'capstan'),
			'5_juriste' => __('Juriste', 'capstan'),
			'6_staff' => __('Personnel Administratif', 'capstan')
		);
}

function getContractType($name) {
	return Array(
			'cdi' => __('CDI', 'capstan'),
			'cdd' => __('CDD', 'capstan'),
			'stage' => __('Stage', 'capstan'),
			'collaboration' => __('Collaboration libérale', 'capstan')
		)[mb_strtolower($name)];
}

if (!function_exists('t') ) {
	function t($key, $display = true) {
		if ($display) _e($key, 'capstan');
		else return __($key, 'capstan');
	}
}

/* function getLawyerFunctionFromStatus() {

	$lawyerFunctionStatusMapping = Array(
		"Avocat" => "4_lawyer",
		"Avocat associé" => "1_associate",
		"Secrétaire" => "5_staff",
		"Assistant responsable de bureau" => "5_staff",
		"Avocat of Counsel" => "2_counsel",
		"Collaborateur" => "4_lawyer",
		"Comptable" => "5_staff",
		"Avocat Senior" => "3_senior",
		"Assistant de direction" => "5_staff",
		"Associé" => "1_associate",
		"Responsable Administrative et Comptable" => "5_staff",
		"Responsable administrative et comptable" => "5_staff"
	);


		$queryLawyers = new WP_Query(
			array(
		    	'post_type' => 'lawyer',
				'orderby' => 'rand',
		    	'posts_per_page' => -1
			)
		);


		$n = 0;
		while($queryLawyers->have_posts()) {
			$queryLawyers->the_post();
			echo get_field('function'). " -- ".get_field('status').' ++';
			echo " * ".get_field('status') . " --> ".$lawyerFunctionStatusMapping[get_field('status')];
			update_field('function', $lawyerFunctionStatusMapping[get_field('status')] ? $lawyerFunctionStatusMapping[get_field('status')] : '');
			echo "\n";
		}

		wp_reset_postdata();
}


getLawyerFunctionFromStatus(); */
?>