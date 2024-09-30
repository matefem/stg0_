<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Pro;

use WeCodePixels\TheiaSmartThumbnails\Misc;
use WeCodePixels\TheiaSmartThumbnails\Options;
use WeCodePixels\TheiaSmartThumbnails\TheiaImageEditor;

class ProClassTheiaImageEditor {
    public static function staticConstruct() {
        add_filter( 'tst_theia_image_editor_gd_after', __NAMESPACE__ . '\\ProClassTheiaImageEditor::tst_theia_image_editor_gd_after', 10, 6 );
    }

    /**
     * @param $return
     * @param $max_w
     * @param $max_h
     * @param $crop
     * @param $thumbnail_id
     * @param $that TheiaImageEditor
     *
     * @return null|resource|string
     */
    public static function tst_theia_image_editor_gd_after( $return, $max_w, $max_h, $crop, $thumbnail_id, $that ) {
        // Use standard resize if crop-to-fit is disabled.
        if ( TheiaImageEditor::$sizes_options['crop_to_fit_mode'] == 0 ) {
            return 'resize';
        }

        $background_color = Misc::hex_to_rgb( TheiaImageEditor::$sizes_options['crop_to_fit_background_color'] );

        // Match background
        if ( TheiaImageEditor::$sizes_options['crop_to_fit_mode'] == Options::CROP_TO_FIT_MODE_MATCH_BACKGROUND ) {
            $pixels     = array(
                array( 0, 0 ),
                array( 0, $that->size['height'] - 1 ),
                array( $that->size['width'] - 1, 0 ),
                array( $that->size['width'] - 1, $that->size['height'] - 1 )
            );
            $same_color = true;
            foreach ( $pixels as $p ) {
                $color_integer = imagecolorat( $that->image, $p[0], $p[1] );
                $r             = ( $color_integer >> 16 ) & 0xFF;
                $g             = ( $color_integer >> 8 ) & 0xFF;
                $b             = $color_integer & 0xFF;

                // Use euclidian distance to compute color difference.
                $diff = sqrt( pow( $r - $background_color[0], 2 ) + pow( $g - $background_color[1], 2 ) + pow( $b - $background_color[2], 2 ) );

                // Accept similar colors, to account for JPEG artifacts.
                if ( $diff >= 5 ) {
                    $same_color = false;
                    break;
                }
            }

            if ( $same_color == false ) {
                return 'resize';
            }
        }

        // Resize using the crop-to-fit method.
        {
            TheiaImageEditor::$enable_crop_to_fit = true;
            $dims                                      = image_resize_dimensions( $that->size['width'], $that->size['height'], $max_w, $max_h, $crop );
            TheiaImageEditor::$enable_crop_to_fit = false;

            if ( ! $dims ) {
                return new \WP_Error( 'error_getting_dimensions', __( 'Could not calculate resized image dimensions' ), $that->file );
            }
            list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

            if ( count( $dims ) >= 10 ) {
                $dst_canvas_w = $dims[8];
                $dst_canvas_h = $dims[9];
            } else {
                $dst_canvas_w = $dst_w;
                $dst_canvas_h = $dst_h;
            }

            // Create empty image.
            $resized = wp_imagecreatetruecolor( $dst_canvas_w, $dst_canvas_h );

            // Fill background.
            if (
                TheiaImageEditor::$sizes_options['crop_to_fit_background_color'] == '' &&
                in_array( $that->get_current_mime_type(), array( 'image/png', 'image/gif' ) )
            ) {
                // Transparent
                $color = imagecolorallocatealpha( $resized, 0, 0, 0, 127 );
            } else {
                // Of given color.
                $color = imagecolorallocate( $resized, $background_color[0], $background_color[1], $background_color[2] );
            }
            imagefill( $resized, 0, 0, $color );

            // Copy the new resized image.
            imagecopyresampled( $resized, $that->image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );

            if ( is_resource( $resized ) ) {
                $that->update_size_public( $dst_canvas_w, $dst_canvas_h );

                return $resized;
            }
        }

        return null;
    }
}
