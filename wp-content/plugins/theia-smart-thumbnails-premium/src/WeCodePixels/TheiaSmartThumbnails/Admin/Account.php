<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Admin;

use WeCodePixels\TheiaSmartThumbnailsFramework\Freemius;

class Account
{
    public function echoPage()
    {
        /** @var $theia_smart_thumbnails_fs Freemius */
        global $theia_smart_thumbnails_fs;
        $theia_smart_thumbnails_fs->add_filter('hide_account_tabs', function () {
            return true;
        });

        if ( $_GET['page'] !== 'theia-smart-thumbnails-account' ) {
            $theia_smart_thumbnails_fs->_account_page_load();
        }

        $theia_smart_thumbnails_fs->_account_page_render();
    }
}
