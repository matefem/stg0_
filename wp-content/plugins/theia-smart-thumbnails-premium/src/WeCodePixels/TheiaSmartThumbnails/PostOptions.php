<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails;

class PostOptions {
    const META_POSITION = 'theiaSmartThumbnails_position';

    // Get the saved crop position of an image.
    public static function get_meta( $postId, $orig_w = null, $orig_h = null ) {
        $focus_point = get_post_meta( $postId, PostOptions::META_POSITION, true );

        if ( ! $focus_point ) {
            if (
                $orig_w !== null &&
                $orig_h !== null &&
                $orig_w < $orig_h &&
                Options::get( 'portraitUpperByDefault' ) == true
            ) {
                $focus_point = array( 0.5, 0 );
            } else {
                $focus_point = array( (float) Options::get( 'default_focal_point_x' ), (float) Options::get( 'default_focal_point_y' ) );
            }
        }

        return $focus_point;
    }
}
