<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails;

if (!class_exists('\\WP_Image_Editor_GD')) {
	require_once ABSPATH . WPINC . '/class-wp-image-editor.php';
	require_once ABSPATH . WPINC . '/class-wp-image-editor-gd.php';
}

class TheiaImageEditor extends \WP_Image_Editor_GD {
    public static $active = false;

	public $size;

	public $image;

	public $file;

	/*
	 * Contains info for the current thumbnail type.
	 * e.g. If we are resizing a thumbnail to size "medium", then this variable will contain info for the "medium" thumbnail type.
	 * This might be null if image_resize_dimensions is called using custom functions (e.g. from a plugin such as MetaSlider).
	 */
	public static $sizes_options = null;

	public static $enable_crop_to_fit = false;

	public function update_size_public($dst_canvas_w, $dst_canvas_h) {
		return parent::update_size($dst_canvas_w, $dst_canvas_h);
	}

	public function get_current_mime_type() {
		return $this->mime_type;
	}

	protected function _resize( $max_w, $max_h, $crop = false ) {
		self::$sizes_options = null;

		// Use standard resize if cropping is disabled.
		if ( ! $crop ) {
			return parent::_resize( $max_w, $max_h, $crop );
		}

		// Get thumbnail ID using the given arguments.
		$thumbnail_id = null;
		$sizes        = Misc::get_image_sizes();
		foreach ( $sizes as $key => $value ) {
			if ( $value['width'] == $max_w && $value['height'] == $max_h && $value['crop'] == $crop ) {
				$thumbnail_id = $key;
				break;
			}
		}

		// Use standard resize if the thumbnail ID could not be found.
		if ( !Options::get( 'enableForUndefinedImageSizes' ) && $thumbnail_id === null ) {
			return parent::_resize( $max_w, $max_h, $crop );
		}

		// Get options.
		self::$sizes_options = Options::get_sizes_options_for_thumbnail( $thumbnail_id );

		// Get post by file if necessary.
		if (!Misc::$last_post_id && Options::get('findImageIdFromImageFile')) {
			// Get image URL.
			{
				$image_url   = $this->file;
				$upload_info = wp_upload_dir();
				$upload_dir  = $upload_info['basedir'];
				$upload_url  = $upload_info['baseurl'];
				// Remove $upload_dir from beginning of path.
				if ( substr( $image_url, 0, strlen( $upload_dir ) ) == $upload_dir ) {
					$image_url = substr( $image_url, strlen( $upload_dir ) );
				}
				$image_url = $upload_url . $image_url;
			}

			// Get post based on URL.
			{
				global $wpdb;
				$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
				$image_id   = $attachment[0];
				if ( null === $image_id ) {
					return parent::_resize( $max_w, $max_h, $crop );
				}
				Misc::$last_post_id = $image_id;
			}
		}

		// Apply further filters.
		$return = apply_filters('tst_theia_image_editor_gd_after', 'resize', $max_w, $max_h, $crop, $thumbnail_id, $this);
		if ($return === 'resize') {
			return parent::_resize( $max_w, $max_h, $crop );
		}
		else if ($return !== null) {
			return $return;
		}

		return new \WP_Error( 'image_resize_error', __( 'Image resize failed.' ), $this->file );
	}
}
