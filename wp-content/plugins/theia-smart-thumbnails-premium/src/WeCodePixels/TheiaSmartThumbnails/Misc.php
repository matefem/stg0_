<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails;

class Misc {
    /**
     * This variable stores the last post ID used in "get_attached_file" or "wp_get_attachment_metadata".
     * These functions are always called before "image_resize_dimensions".
     * Using this, we can get the post's meta data in "image_resize_dimensions".
     */
    public static $last_post_id = null;

    public static function staticConstruct() {
        add_filter( 'image_resize_dimensions', __NAMESPACE__ . '\\Misc::image_resize_dimensions', 100, 6 );
        add_action( 'wp_get_attachment_metadata', __NAMESPACE__ . '\\Misc::wp_get_attachment_metadata', 10, 2 );
        add_action( 'add_attachment', __NAMESPACE__ . '\\Misc::add_attachment', 10, 1 );
        add_action( 'get_attached_file', __NAMESPACE__ . '\\Misc::get_attached_file', 10, 2 );
        add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\Misc::admin_enqueue_scripts' );
        add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\Misc::wp_enqueue_scripts' );
        add_filter( 'wp_image_editors', __NAMESPACE__ . '\\Misc::wp_image_editors', 100, 1 );
        add_filter( 'plugin_action_links_' . plugin_basename( THEIA_SMART_THUMBNAILS_MAIN ), __NAMESPACE__ . '\\Misc::plugin_action_links' );
        add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\Misc::enqueue_block_editor_assets' );
    }

    public static function get_attached_file( $file, $attachment_id ) {
        self::$last_post_id = $attachment_id;

        return $file;
    }

    public static function wp_get_attachment_metadata( $data, $post_id ) {
        self::$last_post_id = $post_id;

        return $data;
    }

    public static function add_attachment( $post_id ) {
        self::$last_post_id = $post_id;
    }

    public static function image_resize_dimensions( $something, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
        if ( ! $crop || self::$last_post_id === null ) {
            return null;
        }

        $aspect_ratio = $orig_w / $orig_h;

        if ( class_exists( 'WeCodePixels\\TheiaSmartThumbnails\\TheiaImageEditor' ) && TheiaImageEditor::$enable_crop_to_fit ) {
            $s_x          = 0;
            $s_y          = 0;
            $dst_canvas_w = $new_w = $dest_w;
            $dst_canvas_h = $new_h = $dest_h;
            $crop_w       = $orig_w;
            $crop_h       = $orig_h;
            $dest_x       = 0;
            $dest_y       = 0;
            $crop_ratio   = $dest_w / $dest_h;

            if ( $aspect_ratio < $crop_ratio ) {
                // Use maximum height. Will add space on the left and right.
                $new_w  = $orig_w / $orig_h * $new_h;
                $dest_x = ( $dst_canvas_w - $new_w ) / 2;
            } else {
                // Use maximum width. Will add space on the top and bottom.
                $new_h  = $orig_h / $orig_w * $new_w;
                $dest_y = ( $dst_canvas_h - $new_h ) / 2;
            }
        } else {
            // Get focus point.
            // If TheiaImageEditor is not active, then this method has been called by a 3rd party plugin such as Royal Slider, in which case we enable the focus point.
            if ( ! TheiaImageEditor::$active || apply_filters( 'tst_misc_enable_focus_point', true ) ) {
                $focus_point = PostOptions::get_meta( self::$last_post_id, $orig_w, $orig_h );
            } else {
                return null;
            }

            $dest_x = $dest_y = 0;

            if ( ! Options::get( 'enlargeSmallImages' ) ) {
                $new_w = min( $dest_w, $orig_w );
                $new_h = min( $dest_h, $orig_h );
            } else {
                $new_w = $dest_w;
                $new_h = $dest_h;
            }

            if ( ! $new_w ) {
                $new_w = intval( $new_h * $aspect_ratio );
            }

            if ( ! $new_h ) {
                $new_h = intval( $new_w / $aspect_ratio );
            }

            $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

            $crop_w = round( $new_w / $size_ratio );
            $crop_h = round( $new_h / $size_ratio );

            $s_x = floor( ( $orig_w - $crop_w ) * $focus_point[0] );
            $s_y = floor( ( $orig_h - $crop_h ) * $focus_point[1] );

            // The canvas is the same as the resulting image.
            $dst_canvas_w = $new_w;
            $dst_canvas_h = $new_h;
        }

        // If the resulting image would be the same size or larger we don't want to resize it
        if (
            $new_w >= $orig_w &&
            $new_h >= $orig_h &&
            ! Options::get( 'enlargeSmallImages' )
        ) {
            return false;
        }

        return array(
            (int) $dest_x,
            (int) $dest_y,
            (int) $s_x,
            (int) $s_y,
            (int) $new_w,
            (int) $new_h,
            (int) $crop_w,
            (int) $crop_h,
            (int) $dst_canvas_w,
            (int) $dst_canvas_h
        );
    }

    // Enqueue JavaScript and CSS for the admin interface.
    // Must be included in a lot of places: post pages, CPT pages, media pages, etc.
    public static function admin_enqueue_scripts()
    {
        // Admin JS
        wp_register_script( 'theiaSmartThumbnails-admin.js', plugins_url( 'dist/js/tst-admin.js', THEIA_SMART_THUMBNAILS_MAIN ), array( 'jquery' ), THEIA_SMART_THUMBNAILS_VERSION, true );
        wp_enqueue_script( 'theiaSmartThumbnails-admin.js' );

        // Admin CSS
        wp_register_style( 'theiaSmartThumbnails-admin', plugins_url( 'dist/css/admin.css', THEIA_SMART_THUMBNAILS_MAIN ), THEIA_SMART_THUMBNAILS_VERSION );
        wp_enqueue_style( 'theiaSmartThumbnails-admin' );
    }

    public static function wp_enqueue_scripts()
    {
        // Compatibility with Beaver Builder.
        if (class_exists('FLBuilderModel') && \FLBuilderModel::is_builder_active()) {
            self::admin_enqueue_scripts();
        }
    }

    // Get all thumbnails sizes used by the current theme, including the default ones defined by WordPress.
    public static function get_image_sizes( $size = '' ) {
        global $_wp_additional_image_sizes;
        $sizes                        = array();
        $get_intermediate_image_sizes = get_intermediate_image_sizes();

        // Create the full array with sizes and crop info
        foreach ( $get_intermediate_image_sizes as $_size ) {
            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
                $sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
                $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                $sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                $sizes[ $_size ] = array(
                    'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
                    'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                    'crop'   => $_wp_additional_image_sizes[ $_size ]['crop']
                );
            }
        }

        // Get only 1 size if found.
        if ( $size ) {
            if ( isset( $sizes[ $size ] ) ) {
                return $sizes[ $size ];
            } else {
                return false;
            }
        }

        return $sizes;
    }

    public static function get_image_sizes_for_picker() {
        // Get sizes.
        $new_sizes = array();
        $sizes     = Options::get( 'previewSizes' );
        $sizes     = explode( "\n", $sizes );
        foreach ( $sizes as $size ) {
            $values                                                     = explode( "x", $size );
            $new_sizes[ $values[0] . ' &times; ' . $values[1] . ' px' ] = array(
                'width'  => $values[0],
                'height' => $values[1]
            );
        }
        unset( $size );

        return $new_sizes;
    }

    public static function hex_to_rgb( $hex ) {
        list( $r, $g, $b ) = sscanf( $hex, "#%02x%02x%02x" );

        return array( $r, $g, $b );
    }

    public static function echo_regenerate_all_thumbnails_notice() {
        if ( class_exists( 'RegenerateThumbnails' ) ) {
            $url = get_admin_url( null, 'tools.php?page=regenerate-thumbnails' );
        } else {
            $url = 'https://wordpress.org/plugins/regenerate-thumbnails/';
        }
        ?>
        <div id="poststuff">
            <div class="postbox">
                <div class="inside">
                    <p>
                        After changing these settings, you might want to
                        <a href="<?= htmlspecialchars( $url ) ?>">regenerate all existing
                            thumbnails</a>.
                    </p>
                </div>
            </div>
        </div>
        <?php
    }

    public static function get_pro_only_notice() {
        return '';
//		return '<br><span class="theiaSmartThumbnails_proOnly">' . (THEIA_SMART_THUMBNAILS_IS_PRO ? 'PRO' : 'PRO only') . '</span>';
    }

    // Courtesy of http://stackoverflow.com/a/16076965/148388
    public static function get_request_scheme() {
        $isSecure = false;
        if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
            $isSecure = true;
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || ! empty( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) {
            $isSecure = true;
        }

        return $isSecure ? 'https' : 'http';
    }

    public static function wp_image_editors( $args = array() ) {
        TheiaImageEditor::$active = true;

        // Add our editor first in line.
        array_unshift( $args, 'WeCodePixels\\TheiaSmartThumbnails\\TheiaImageEditor' );

        return $args;
    }

    public static function plugin_action_links( $links ) {
        $mylinks = array(
            '<a href="' . admin_url( 'options-general.php?page=theia-smart-thumbnails' ) . '">Settings</a>',
        );

        return array_merge( $mylinks, $links );
    }

    public static function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'theiaSmartThumbnails-gutenberg.js',
            plugins_url( 'dist/js/tst-gutenberg.js', THEIA_SMART_THUMBNAILS_MAIN ),
            [ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-compose', 'lodash' ],
            THEIA_SMART_THUMBNAILS_VERSION
        );

        wp_localize_script( 'theiaSmartThumbnails-gutenberg.js', 'tstData', array(
            'sizes'   => Misc::get_image_sizes_for_picker(),
            'restApi' => rest_url( 'theia-smart-thumbnails/v1' )
        ) );
    }
}
