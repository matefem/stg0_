<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Admin;

use WeCodePixels\TheiaSmartThumbnailsFramework\Freemius;

class Contact {
    public function echoPage() {
        /** @var $theia_smart_thumbnails_fs Freemius */
        global $theia_smart_thumbnails_fs;

        $theia_smart_thumbnails_fs->_contact_page_render();
    }
}
