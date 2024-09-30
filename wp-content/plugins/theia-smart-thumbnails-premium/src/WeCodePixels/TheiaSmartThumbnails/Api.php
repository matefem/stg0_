<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails;

use WeCodePixels\TheiaSmartThumbnails\Pro\ProPostOptions;

class Api {
    public function __construct() {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'theia-smart-thumbnails/v1', '/focus-point/(?P<id>\d+)', array(
                'methods'             => array( 'GET', 'POST' ),
                'callback'            => array( $this, 'callback' ),
                'args'                => array(
                    'id' => array(
                        'validate_callback' => function ( $param, $request, $key ) {
                            return is_numeric( $param );
                        }
                    ),
                ),
                'permission_callback' => function () {
                    return current_user_can( 'edit_others_posts' );
                }
            ) );
        } );
    }

    public function callback( \WP_REST_Request $data ) {
        if ( $data->get_method() === 'GET' ) {
            $this->getFocusPoint( $data );
        } else {
            $this->setFocusPoint( $data );
        }
    }

    protected function getFocusPoint( \WP_REST_Request $data ) {
        $focus_point = PostOptions::get_meta( $data->get_param( 'id' ) );
        echo json_encode( $focus_point );
        die();
    }

    protected function setFocusPoint( \WP_REST_Request $data ) {
        $post       = array(
            'ID' => $data->get_param( 'id' )
        );
        $attachment = array(
            PostOptions::META_POSITION => $data->get_param( 'focus_point' )
        );

        ProPostOptions::attachment_fields_to_save( $post, $attachment );
        echo '[]'; // We need to return a valid JSON.
        die();
    }
}
