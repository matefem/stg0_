<?php
     $args = array(
        'label'           => 'Bureaux',
        'public'          => true,
        'show_ui'         => true,
        'capability_type' => 'page',
        'hierarchical'    => false,
        'rewrite'         => array(
            'slug' => 'bureau'
        ),
        'query_var'       => true,
        'menu_icon'       => 'dashicons-admin-multisite',
        'has_archive'     => false,
        'show_in_rest'    => true,
        'supports'        => array(
            'title',
            'editor',
            'custom-fields',
            'revisions',
            'author'
        )
    );
    register_post_type( 'office', $args);




    $args = array(
        'label'           => 'Collaborateurs',
        'public'          => true,
        'show_ui'         => true,
        'capability_type' => 'page',
        'hierarchical'    => false,
        'rewrite'         => array(
            'slug' => 'collaborateur'
        ),
        'query_var'       => true,
        'menu_icon'       => 'dashicons-businesswoman',
        'has_archive'     => false,
        'show_in_rest'    => true,
        'supports'        => array(
            'title',
            'editor',
            'custom-fields',
            'revisions',
            'author'
        )
    );
    register_post_type( 'lawyer', $args);

    add_filter('manage_lawyer_posts_columns', function($columns) {
        return array_merge(array_slice($columns, 0, 2), ['status' => 'Status'], ['function' => 'Fonction'], array_slice($columns, 1));
    });


add_action( 'manage_lawyer_posts_custom_column' , 'custom_lawyer_column', 10, 2);
    function custom_lawyer_column( $column, $post_id ) {
        switch ($column) {
            case 'function' :
                $terms = get_field('function', $post_id);

                if (!empty($terms) && is_string($terms['label']))
                    echo $terms['label'];
                break;
            case 'status' :
                $terms = get_field('status', $post_id);
                if (is_string( $terms ))
                    echo $terms;
                break;

    }
}




    $args = array(
        'label'           => 'Carrières',
        'public'          => true,
        'show_ui'         => true,
        'capability_type' => 'page',
        'hierarchical'    => false,
        'rewrite'         => array(
            'slug' => 'carriere'
        ),
        'query_var'       => true,
        'menu_icon'       => 'dashicons-portfolio',
        'has_archive'     => false,
        'show_in_rest'    => true,
        'supports'        => array(
            'title',
            'editor',
            'custom-fields',
            'revisions',
            'author'
        )
    );
    register_post_type( 'career', $args);




    $args = array(
        'label'           => 'Formations',
        'public'          => true,
        'show_ui'         => true,
        'capability_type' => 'page',
        'hierarchical'    => false,
        'rewrite'         => array(
            'slug' => 'formation'
        ),
        'query_var'       => true,
        'menu_icon'       => 'dashicons-welcome-learn-more',
        'has_archive'     => false,
        'show_in_rest'    => true,
        'supports'        => array(
            'title',
            'editor',
            'custom-fields',
            'revisions',
            'author'
        )
    );
    register_post_type( 'formation', $args);




    $args = array(
        'label'           => 'Expertise',
        'public'          => true,
        'show_ui'         => true,
        'capability_type' => 'page',
        'hierarchical'    => false,
        'rewrite'         => array(
            'slug' => 'expertise'
        ),
        'query_var'       => true,
        'menu_icon'       => 'dashicons-awards',
        'has_archive'     => false,
        'show_in_rest'    => true,
        'supports'        => array(
            'title',
            'editor',
            'custom-fields',
            'revisions',
            'author'
        )
    );
    register_post_type( 'expertise', $args);




?>