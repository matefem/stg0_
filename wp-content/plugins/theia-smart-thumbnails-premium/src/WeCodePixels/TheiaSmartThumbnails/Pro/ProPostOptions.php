<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Pro;

use WeCodePixels\TheiaSmartThumbnails\Misc;
use WeCodePixels\TheiaSmartThumbnails\Options;
use WeCodePixels\TheiaSmartThumbnails\PostOptions;

class ProPostOptions {
    const META_POSITION_PICKER = 'theiaSmartThumbnails_positionPicker';

    public static function staticConstruct() {
        add_action( 'attachment_fields_to_edit', __NAMESPACE__ . '\\ProPostOptions::attachment_fields_to_edit', 20, 2 );
        add_action( 'attachment_fields_to_save', __NAMESPACE__ . '\\ProPostOptions::attachment_fields_to_save', 10, 2 );
    }

    public static function is_compatible_post( $post ) {
        if ( is_array( $post ) ) {
            /* @var $post array */
            if ( array_key_exists( 'ID', $post ) ) {
                $post = get_post( $post['ID'] );
            } else {
                return false;
            }
        }

        /* @var $post \WP_Post */
        if ( $post->post_type != 'attachment' ) {
            return false;
        }

        $split = explode( '/', $post->post_mime_type );
        if ( $split[0] != 'image' ) {
            return false;
        }

        return true;
    }

    // Add fields to the Media Upload dialog.
    public static function attachment_fields_to_edit( $form_fields, $post ) {
        if ( ! ProPostOptions::is_compatible_post( $post ) ) {
            return $form_fields;
        }

        if ( ! is_array( $form_fields ) ) {
            $form_fields = array();
        }

        // Get saved values.
        $focus_point = PostOptions::get_meta( $post->ID );

        // Create HTML.
        $image       = wp_get_attachment_image_src( $post->ID, apply_filters( 'tst_post_options_preview_image_size', 'large' ) );
        $image_id    = 'theiaSmartThumbnails_picker_image_' . $post->ID;
        $preview_id  = 'theiaSmartThumbnails_picker_preview_' . $post->ID;
        $key         = 'theiaSmartThumbnails_showPreview';
        $showPreview = isset( $_COOKIE[ $key ] ) ? $_COOKIE[ $key ] === 'true' : false;
        ob_start();
        ?>
        <div class="theiaSmartThumbnails_mediaUpload">
            <p>
                <?php echo __( 'Click on the <strong>point of interest</strong> - the area you want included in the thumbnails. Drag your cursor to experiment.', 'theia-smart-thumbnails' ); ?>
            </p>
            <div id="<?php echo $image_id; ?>" class="_picker"><img src="<?php echo $image[0]; ?>"></div>

            <p>
                <?php echo __( '<strong>Preview</strong> - this is how the thumbnails will look, depending on their size.', 'theia-smart-thumbnails' ); ?>
                <input type="button"
                       class="button _previewButton"
                       onclick="tst.togglePreview(this)"
                       value="<?php echo !$showPreview ? 'Show' : 'Hide' ?> Preview">
            </p>
            <div id="<?php echo $preview_id ?>"
                 class="_preview"
                 style="<?php echo !$showPreview ? 'display: none' : '' ?>"></div>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();

        // The script will initialize the picker. If the elements do not yet exist, it will wait a while until retrying.
        $script = '
			jQuery(document).ready(function() {
				tst.createPickerDelayed({
					attachmentId: "' . $post->ID . '",
					image: "#' . $image_id . '",
					input: "input[name=\'attachments[' . $post->ID . '][' . PostOptions::META_POSITION . ']\']",
					preview: "#' . $preview_id . '",
					sizes: ' . json_encode( Misc::get_image_sizes_for_picker() ) . ',
					position: {
						x: ' . number_format( $focus_point[0], 10 ) . ',
						y: ' . number_format( $focus_point[1], 10 ) . '
					}
				});
			});
		';
        $html   .= '<script type="text/javascript">' . $script . '</script>';

        // Add form field
        $form_fields[ PostOptions::META_POSITION ]        = array(
            'label' => '',
            'input' => 'text'
        );
        $form_fields[ ProPostOptions::META_POSITION_PICKER ] = array(
            'label' => __( 'Theia Smart Thumbnails' ),
            'input' => 'html',
            'html'  => $html
        );

        // Return fields
        return $form_fields;
    }

    // Save submitted fields from the Media Upload dialog.
    public static function attachment_fields_to_save( $post, $attachment ) {
        if ( ! ProPostOptions::is_compatible_post( $post ) ) {
            return $post;
        }

        if ( isset( $attachment[ PostOptions::META_POSITION ] ) && $attachment[ PostOptions::META_POSITION ] ) {
            require_once( ABSPATH . 'wp-admin/includes/image.php' );

            $previous_position = PostOptions::get_meta( $post['ID'] );
            $position          = json_decode( $attachment[ PostOptions::META_POSITION ] );
            update_post_meta( $post['ID'], PostOptions::META_POSITION, $position );

            // Update the thumbnail if the position changed
            if ( $previous_position[0] != $position[0] || $previous_position[1] != $position[1] ) {
                $old_image_path = get_attached_file( $post['ID'] );
                $new_image_path = $old_image_path;

                // Replace the old thumbnails if the option is set to true.
                if ( Options::get( 'allowThumbsReplacing' ) === true ) {
                    $info = pathinfo( $old_image_path );
                    // Regex pattern to find all matching files of different sizes.
                    $pattern = '/^' . preg_quote( $info['dirname'] . '/' . $info['filename'] . '-', '/' ) . '[\d]+x[\d]+.' . preg_quote( $info['extension'], '/' ) . '$/';
                    foreach ( glob( $info['dirname'] . '/' . $info['filename'] . '-*x*.' . $info['extension'] ) as $file_to_check ) {
                        if ( preg_match( $pattern, $file_to_check ) ) {
                            unlink( $file_to_check );
                        }
                    }
                }

                // Generate thumbnails
                @set_time_limit( 900 ); // 5 minutes per image should be PLENTY.
                $focus_pointdata = wp_generate_attachment_metadata( $post['ID'], $new_image_path );

                // Cache-busting
                $previous_metadata = wp_get_attachment_metadata( $post['ID'] );
                if ( is_array( $previous_metadata ) && array_key_exists( 'tst_thumbnail_version', $previous_metadata ) ) {
                    $focus_pointdata['tst_thumbnail_version'] = $previous_metadata['tst_thumbnail_version'] + 1;
                } else {
                    $focus_pointdata['tst_thumbnail_version'] = 2;
                }

                // Update metadata
                wp_update_attachment_metadata( $post['ID'], $focus_pointdata );
            }
        }

        return $post;
    }
}
