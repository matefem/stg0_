<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Pro;

class ProOptions {
    public static function staticConstruct() {
        add_filter( 'tst_init_options_defaults', __NAMESPACE__ . '\\ProOptions::tst_init_options_defaults', 10, 1 );
    }

    public static function tst_init_options_defaults( $defaults ) {
        return $defaults;
    }
}
