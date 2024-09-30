<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Pro;

use WeCodePixels\TheiaSmartThumbnails\Options;
use WeCodePixels\TheiaSmartThumbnails\TheiaImageEditor;

class ProMisc {
    public static function staticConstruct() {
        add_action( 'wp_get_attachment_metadata', __NAMESPACE__ . '\\ProMisc::wp_get_attachment_metadata', 10, 2 );
        add_filter( 'tst_misc_enable_focus_point', __NAMESPACE__ . '\\ProMisc::tst_misc_enable_focus_point', 10, 1 );
    }

    public static function wp_get_attachment_metadata( $data, $post_id ) {
        // Do cache-busting for thumbnails.
        if (
            ProPostOptions::is_compatible_post( get_post( $post_id ) ) &&
            Options::get( 'cacheBusting' ) &&
            is_array( $data ) &&
            array_key_exists( 'sizes', $data ) &&
            array_key_exists( 'tst_thumbnail_version', $data )
        ) {
            foreach ( $data['sizes'] as &$size ) {
                $key = 'theia_smart_thumbnails_file_version';

                // Parse filename as a URL, as it may already contain a version query-string.
                $parts = parse_url( $size['file'] );
                if ( $parts !== false && isset( $parts['path'] ) && isset( $parts['query'] ) ) {
                    $path = $parts['path'];
                    parse_str( $parts['query'], $query_data );
                } else {
                    $path       = $size['file'];
                    $query_data = array();
                }

                // Add or replace version number.
                $query_data [ $key ] = $data['tst_thumbnail_version'];
                $size['file']        = $path . '?' . http_build_query( $query_data );
            }
        }

        return $data;
    }

    public static function tst_misc_enable_focus_point( $enable ) {
        if ( TheiaImageEditor::$sizes_options === null ) {
            if ( false == Options::get( 'allowOtherPlugins' ) ) {
                return false;
            } else {
                return true;
            }
        }

        return TheiaImageEditor::$sizes_options['use_focus_point_mode'] == Options::USE_FOCUS_POINT_MODE_YES;
    }
}
