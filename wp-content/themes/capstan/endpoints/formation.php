<?php

if (!function_exists('capstan_request_formation')) {

	function capstan_request_formation() {
		$mail =		 	strip_tags(capstan_escape_value($_POST['mail']));
		$firstname =	strip_tags(capstan_escape_value($_POST['firstname']));
		$lastname =	 	strip_tags(capstan_escape_value($_POST['lastname']));
		$business =	 	strip_tags(capstan_escape_value($_POST['business']));
		$role =		 	strip_tags(capstan_escape_value($_POST['role']));
		$mailto =	 	strip_tags(capstan_escape_value($_POST['mailto']));
		$id =	 		strip_tags(capstan_escape_value($_POST['id']));

		$query = new WP_Query(
			array(
		    	'post_type' => 'formation',
		    	'posts_per_page' => 1,
		    	'post__in' => [$id]
			)
		);

		if($mailto == '') $mailto = 'formations@capstan.fr';

		$formation = $query->posts[0];

	    $headers = array('Content-Type: text/html; charset=UTF-8');

	    // ********* MAIL UTILISATEUR ********* 

	    $content = '';
	    $infos = Array(
	    	'title' => 'Demande de formation, <br/>'.get_field('title', $id),
	    	'description' => get_field('description', $id),
	    	'picture' => get_field('image', $id)['sizes']['1536x1536'],
	    	'date' => ''
	    );

		$dates = get_field('dates', $id);
		foreach($dates as $date) {
			$datetime = DateTime::createFromFormat("d/m/Y", trim($date['day']));

			$infos['date'] .= ' - '.(strftime("%A %d %B %Y", $datetime->format('U'))). ' - ';
			$infos['date'] .= $date['hour_start'].' - '.$date['hour_end'];
			$infos['date'] .= '<br/>';
		}


		ob_start();
		include_once(__DIR__.'/../mail/exports/formation.php');
		$content = ob_get_contents();
		ob_end_clean();

	    wp_mail($mail, 'Capstan - Demande de formation', $content, $headers);


	    // ********* MAIL ADMINISTRATEUR ********* 


		$content = "Bonjour, \n\n.
		Vous avez reçu une demande de formation
		Intitulé de la formation :  ".strip_tags($infos["title"])."\n
		Dates :  ".strip_tags($infos["date"])."\n\n\n


		Nom du participant : ".$firstname." ".$lastname.",\n
		Entreprise : ".$business.",\n
		Role : ".$role.",\n
		Mail : ".$mail.",\n";


		$content .= $formule.",\n";
		$content .= $author."\n";
	    wp_mail($mailto, "Capstan - Demande de formation", $content, array('charset=UTF-8'));

		$data = Array('success' => true);

		wp_reset_postdata();
		echo json_encode($data);
		// echo $content;

		die();
	}
}

add_action( 'wp_ajax_nopriv_capstan_request_formation', 'capstan_request_formation' );
add_action( 'wp_ajax_capstan_request_formation', 'capstan_request_formation' );
?>