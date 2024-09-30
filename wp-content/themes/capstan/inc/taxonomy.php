<?php

add_action( 'init', function() {

		$labels = array(
		    'name' => _x( 'Thèmes', 'Thème' ),
		    'singular_name' => _x( 'Thème', 'Thème' ),
		    'search_items' =>  __( 'Chercher un thème' ),
		    'all_items' => __( 'Tous les thèmes' ),
		    'edit_item' => __( 'Editer thème' ),
		    'update_item' => __( 'Editer thème' ),
		    'add_new_item' => __( 'Ajouter un thème' ),
		    'new_item_name' => __( 'Nouveau nom du thème' ),
		    'menu_name' => __( 'Thèmes' )
		);

		register_taxonomy('post-theme', 'post', array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_rest' => false,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'post-theme' ),
		));


		$labels = array(
		    'name' => _x( 'Types', 'Type' ),
		    'singular_name' => _x( 'Type', 'Type' ),
		    'search_items' =>  __( 'Chercher un type' ),
		    'all_items' => __( 'Tous les types' ),
		    'edit_item' => __( 'Editer type' ),
		    'update_item' => __( 'Editer type' ),
		    'add_new_item' => __( 'Ajouter un type' ),
		    'new_item_name' => __( 'Nouveau nom du type' ),
		    'menu_name' => __( 'Type' )
		);

		register_taxonomy('post-type', 'post', array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_rest' => false,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'post-type' ),
		));

		register_taxonomy('post_tag', array());

	}, 0 );

?>