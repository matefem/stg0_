<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails;

class Options {
    const CROP_TO_FIT_MODE_NO = 0;
    const CROP_TO_FIT_MODE_YES = 1;
    const CROP_TO_FIT_MODE_MATCH_BACKGROUND = 2;
    const USE_FOCUS_POINT_MODE_NO = 0;
    const USE_FOCUS_POINT_MODE_YES = 1;

    public static function staticConstruct() {
        add_action( 'init', __NAMESPACE__ . '\\Options::init' );
    }

    public static function get_default_focal_points() {
        $options = array(
            'center'       => array( 'Center', 0.5, 0.5 ),
            'top'          => array( 'Top', 0.5, 0 ),
            'top-right'    => array( 'Top-Right', 1, 0 ),
            'right'        => array( 'Right', 1, 0.5 ),
            'bottom-right' => array( 'Bottom-Right', 1, 1 ),
            'bottom'       => array( 'Bottom', 0.5, 1 ),
            'bottom-left'  => array( 'Bottom-Left', 0, 1 ),
            'left'         => array( 'Left', 0, 0.5 ),
            'top-left'     => array( 'Top-Left', 0, 0 ),
            'custom'       => array( 'Custom', null, null )

        );

        return $options;
    }

    public static function get( $optionId ) {
        $groups = array( 'tst_dashboard', 'tst_general' );
        foreach ( $groups as $groupId ) {
            $options = get_option( $groupId );
            if ( ! is_array( $options ) ) {
                continue;
            }

            if ( array_key_exists( $optionId, $options ) ) {
                return $options[ $optionId ];
            }
        }

        return null;
    }

    // Initialize options
    public static function init() {
        $defaults = array(
            'tst_dashboard' => array(
                'reset_to_defaults' => false
            ),
            'tst_general'   => array(
                'enableForUndefinedImageSizes' => true,
                'findImageIdFromImageFile'     => true,
                'default_focal_point_x'        => 0.5,
                'default_focal_point_y'        => 0.5,
                'default_focal_point'          => 'center',
                'portraitUpperByDefault'       => false,
                'allowOtherPlugins'            => true,
                'allowThumbsReplacing'         => false,
                'enlargeSmallImages'           => false,
                'cacheBusting'                 => true,
                'previewSizes'                 => Options::get_default_preview_sizes(),
                'enableInFrontEnd'             => false
            )
        );
        $defaults = apply_filters( 'tst_init_options_defaults', $defaults );

        // Reset to defaults.
        $resetToDefaults = get_option( 'tst_dashboard' );
        $resetToDefaults = is_array( $resetToDefaults ) && array_key_exists( 'reset_to_defaults', $resetToDefaults ) && $resetToDefaults['reset_to_defaults'];
        if ( $resetToDefaults ) {
            foreach ( $defaults as $groupId => $groupValues ) {
                delete_option( $groupId );
            }

            delete_option( 'tst_sizes' );
        }

        foreach ( $defaults as $groupId => $groupValues ) {
            $options = get_option( $groupId );
            $changed = false;

            // Add missing options
            foreach ( $groupValues as $key => $value ) {
                if ( isset( $options[ $key ] ) == false ) {
                    $changed         = true;
                    $options[ $key ] = $value;
                }
            }

            // Remove surplus options.
            foreach ( $options as $key => $value ) {
                if ( isset( $defaults[ $groupId ][ $key ] ) == false ) {
                    $changed = true;
                    unset( $options[ $key ] );
                }
            }

            // Sanitize options.
            foreach ( $options as $key => $value ) {
                if ( is_bool( $defaults[ $groupId ][ $key ] ) ) {
                    $options[ $key ] = ( $options[ $key ] === true || $options[ $key ] === 'true' ) ? true : false;
                    $changed         = true;
                }

                if ( is_array( $defaults[ $groupId ][ $key ] ) ) {
                    $options[ $key ] = is_array( $options[ $key ] ) ? $options[ $key ] : $defaults[ $groupId ][ $key ];
                    $changed         = true;
                }
            }

            if ( $groupId == 'tst_general' ) {
                if ( array_key_exists( $options['default_focal_point'], Options::get_default_focal_points() ) == false ) {
                    $options['default_focal_point'] = $groupValues['default_focal_point'];
                    $changed                        = true;
                }

                if ( $options['default_focal_point'] == 'custom' ) {
                    if ( $options['default_focal_point_x'] < 0 || $options['default_focal_point_x'] > 1 ) {
                        $options['default_focal_point_x'] = $groupValues['default_focal_point_x'];
                        $changed                          = true;
                    }

                    if ( $options['default_focal_point_y'] < 0 || $options['default_focal_point_y'] > 1 ) {
                        $options['default_focal_point_y'] = $groupValues['default_focal_point_y'];
                        $changed                          = true;
                    }
                } else {
                    $available_options                = Options::get_default_focal_points();
                    $options['default_focal_point_x'] = $available_options[ $options['default_focal_point'] ][1];
                    $options['default_focal_point_y'] = $available_options[ $options['default_focal_point'] ][2];
                    $changed                          = true;
                }
            }

            // Save options
            if ( $changed ) {
                update_option( $groupId, $options );
            }
        }
    }

    public static function get_default_preview_sizes() {
        return
            "200x200\n" .
            "200x150\n" .
            "200x100\n" .
            "200x50\n" .
            "150x200\n" .
            "100x200\n" .
            "50x200";
    }

    public static function get_use_focus_point_options() {
        return array(
            self::USE_FOCUS_POINT_MODE_NO  => 'No',
            self::USE_FOCUS_POINT_MODE_YES => 'Yes'
        );
    }

    public static function get_crop_to_fit_options() {
        return array(
            self::CROP_TO_FIT_MODE_NO               => 'No',
            self::CROP_TO_FIT_MODE_YES              => 'Yes',
            self::CROP_TO_FIT_MODE_MATCH_BACKGROUND => 'Only for matching backgrounds'
        );
    }

    public static function get_sizes_options_for_thumbnail( $thumbnail_id ) {
        $options = get_option( 'tst_sizes' );
        $options = is_array( $options ) ? $options : array();

        $result = array(
            'use_focus_point_mode'         => array_key_exists( 'use_focus_point_' . $thumbnail_id, $options ) ? (int) $options[ 'use_focus_point_' . $thumbnail_id ] : Options::USE_FOCUS_POINT_MODE_YES,
            'crop_to_fit_mode'             => array_key_exists( 'crop_to_fit_' . $thumbnail_id, $options ) ? (int) $options[ 'crop_to_fit_' . $thumbnail_id ] : Options::CROP_TO_FIT_MODE_NO,
            'crop_to_fit_background_color' => array_key_exists( 'crop_to_fit_' . $thumbnail_id . '_background_color', $options ) ? $options[ 'crop_to_fit_' . $thumbnail_id . '_background_color' ] : '#ffffff'
        );

        return $result;
    }
}
