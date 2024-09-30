<?php

if (!function_exists('capstan_send_message')) {

	function capstan_send_message() {
		$subject 		= strip_tags(capstan_escape_value($_POST['subject']));
		$companyname 	= strip_tags(capstan_escape_value($_POST['companyname']));
		$city 			= strip_tags(capstan_escape_value($_POST['city']));
		$message 		= strip_tags(capstan_escape_value($_POST['content']));
		$mail			= strip_tags(capstan_escape_value($_POST['mail']));
		$phone			= strip_tags(capstan_escape_value($_POST['phone']));
		$formule		= strip_tags(capstan_escape_value($_POST['formule']));
		$author			= strip_tags(capstan_escape_value($_POST['author']));
		$poste			= strip_tags(capstan_escape_value($_POST['poste']));

		$query = new WP_Query(
			array(
		    	'post_type' => 'office',
				'posts_per_page' => 1,
				'meta_query' => [
					[
						'key' => 'title', 
						'value' => $city,
						'compare' => 'LIKE',
					]
				]
			)
		);

		if($query->have_posts()) {
			$query->the_post();
			$postID = get_the_ID();
	    	$email = get_field('infos', $postID)['mail'];
		}

		$content = '';

		$content .= "Bonjour, \n\n
		Vous avez reçu une demande de contact depuis le site de Capstan.\n
		Sujet :  ".$subject."\n
		Nom de l\"entreprise : ".$companyname."\n
		Poste : ".$poste."\n
		Bureau : ".$city."\n
		Mail : ".$mail."\n
		Téléphone : ".$phone."\n
		Auteur : ".$author."\n
		Contenu : ".$message."\n";


		$content .= $formule.",\n";
		$content .= $author."\n";

	    $headers = array('charset=UTF-8');

	    wp_mail($email, 'Capstan - Demande de contact - '.$subject, $content, $headers);

		$data = Array('success' => true);

		wp_reset_postdata();
		echo json_encode($data);

		die();
	}
}

add_action( 'wp_ajax_nopriv_capstan_send_message', 'capstan_send_message' );
add_action( 'wp_ajax_capstan_send_message', 'capstan_send_message' );
?>