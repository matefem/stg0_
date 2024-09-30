<?php
/*
Plugin Name: Theia Smart Thumbnails
Plugin URI: https://wecodepixels.com/theia-smart-thumbnails-for-wordpress/?utm_source=theia-smart-thumbnails
Description: Gain full control over your thumbnails by customizing the cropping zone for each one of them.
Author: WeCodePixels
Author URI: https://wecodepixels.com/?utm_source=theia-smart-thumbnails
Version: 2.3.0
Update URI: https://api.freemius.com
Copyright: WeCodePixels
*/

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

define( 'THEIA_SMART_THUMBNAILS_VERSION', '2.3.0' );
define( 'THEIA_SMART_THUMBNAILS_DIR', __DIR__ );
define( 'THEIA_SMART_THUMBNAILS_MAIN', __FILE__ );
define( 'THEIA_SMART_THUMBNAILS_IS_PRO', true );

// Autoloader
require __DIR__ . '/vendor/wecodepixels/wordpress-plugin/Autoloader.php';
$autoLoader = new \WeCodePixels\TheiaSmartThumbnailsFramework\Autoloader( __DIR__, 'WeCodePixels\\TheiaSmartThumbnails' );

\WeCodePixels\TheiaSmartThumbnails\Freemius::staticConstruct();
\WeCodePixels\TheiaSmartThumbnails\Frontend::staticConstruct();
\WeCodePixels\TheiaSmartThumbnails\Misc::staticConstruct();
\WeCodePixels\TheiaSmartThumbnails\Options::staticConstruct();
\WeCodePixels\TheiaSmartThumbnails\Pro\ProClassTheiaImageEditor::staticConstruct();
\WeCodePixels\TheiaSmartThumbnails\Pro\ProMisc::staticConstruct();
\WeCodePixels\TheiaSmartThumbnails\Pro\ProOptions::staticConstruct();
\WeCodePixels\TheiaSmartThumbnails\Pro\ProPostOptions::staticConstruct();
\WeCodePixels\TheiaSmartThumbnails\Admin\Admin::staticConstruct();

$GLOBALS['theiaSmartThumbnails'] = array(
    'api' => new \WeCodePixels\TheiaSmartThumbnails\Api()
);

