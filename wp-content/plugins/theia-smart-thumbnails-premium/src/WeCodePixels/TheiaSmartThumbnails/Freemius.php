<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */
namespace WeCodePixels\TheiaSmartThumbnails;

class Freemius
{
    public static function staticConstruct()
    {
        // Create a helper function for easy SDK access.
        function theia_smart_thumbnails_fs()
        {
            global  $theia_smart_thumbnails_fs ;
            
            if ( !isset( $theia_smart_thumbnails_fs ) ) {
                // Include Freemius SDK.
                require_once THEIA_SMART_THUMBNAILS_DIR . '/vendor/freemius/wordpress-sdk/start.php';
                $theia_smart_thumbnails_fs = fs_dynamic_init( array(
                    'id'               => '2367',
                    'slug'             => 'theia-smart-thumbnails',
                    'type'             => 'plugin',
                    'public_key'       => 'pk_98f26dc401377bda5776327734d67',
                    'is_premium'       => true,
                    'is_premium_only'  => true,
                    'has_addons'       => false,
                    'has_paid_plans'   => true,
                    'is_org_compliant' => false,
                    'menu'             => array(
                    'slug'       => 'theia-smart-thumbnails',
                    'first-path' => 'options-general.php?page=theia-smart-thumbnails',
                    'contact'    => false,
                    'support'    => false,
                    'account'    => false,
                    'pricing'    => false,
                    'parent'     => array(
                    'slug' => 'options-general.php',
                ),
                ),
                    'is_live'          => true,
                ) );
            }
            
            return $theia_smart_thumbnails_fs;
        }
        
        // Init Freemius.
        theia_smart_thumbnails_fs();
        // Signal that SDK was initiated.
        do_action( 'theia_smart_thumbnails_fs_loaded' );
        // Init Freemius through our framework.
        global  $theia_smart_thumbnails_fs ;
        require THEIA_SMART_THUMBNAILS_DIR . '/vendor/wecodepixels/wordpress-plugin/Freemius.php';
        \WeCodePixels\TheiaSmartThumbnailsFramework\Freemius::init( $theia_smart_thumbnails_fs, __NAMESPACE__ . '\\Admin\\Admin' );
    }

}