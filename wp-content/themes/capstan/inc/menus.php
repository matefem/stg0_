<?php

function capstan_menus() {

	$locations = array(
		'header_id'   => __( 'Header Notre identité', 'capstan' ),
		'header_acc'   => __( 'Header Vous accompagner', 'capstan' ),
		'header_fr'   => __( 'Header En France', 'capstan' ),
		'header_others'   => __( 'Header Autres', 'capstan' ),
		'join_us'   => __( 'Header Nous Rejoindre', 'capstan' ),
		'conctact_us'   => __( 'Nav Nous contacter', 'capstan' ),
		'terms'   => __( 'Footer Terms & conditions', 'capstan' ),
		'footer'   => __( 'Footer A propos', 'capstan' ),
		'shares'   => __( 'Réseaux sociaux', 'capstan' ),
	);

	register_nav_menus( $locations );
}

add_action( 'init', 'capstan_menus' );


?>