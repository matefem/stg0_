<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Admin;

use WeCodePixels\TheiaSmartThumbnails\Misc;

class Dashboard {
    public function echoPage() {
        ?>
        <p>
            You are using
            <a href="https://wecodepixels.com/theia-smart-thumbnails-for-wordpress/?utm_source=theia-smart-thumbnails-for-wordpress"
               target="_blank"><b>Theia Smart Thumbnails</b></a>
            version <b class="theiaSmartThumbnails_adminVersion"><?php echo THEIA_SMART_THUMBNAILS_VERSION; ?></b>, developed
            by
            <a href="https://wecodepixels.com/?utm_source=theia-smart-thumbnails-for-wordpress"
               target="_blank"><b>WeCodePixels</b></a>.
            <br>
        </p>
        <br>

        <h3><?php _e( "Support", 'theia-smart-thumbnails' ); ?></h3>

        <p>
            1. If you have any problems or questions, you should first check
            <a href="https://wecodepixels.com/theia-smart-thumbnails-for-wordpress/docs/?utm_source=theia-smart-thumbnails-for-wordpress"
               target="_blank">
                The Documentation</a>.
        </p>

        <form method="post" action="options.php">
            <?php settings_fields( 'tst_options_dashboard' ); ?>

            <p>
                2. If the plugin is misbehaving, try to <input name="tst_dashboard[reset_to_defaults]"
                                                               type="submit"
                                                               class="button"
                                                               value="Reset to Default Settings"
                                                               onclick="if(!confirm('Are you sure you want to reset all settings to their default values?')) return false;">
                .
            </p>
        </form>

        <p>
            3. Deactivate all plugins. If the issue is solved, then re-activate them one-by-one to pinpoint the
            exact cause.
        </p>

        <p>
            4. If your issue persists, please
            <a href="?page=theia-smart-thumbnails&tab=contact">Contact Us</a>.
            <?php echo Misc::get_pro_only_notice(); ?>
        </p>
        <br>

        <iframe class="theiaSmartThumbnails_news" src="https://wecodepixels.com/theia-smart-thumbnails-for-wordpress/news" scrolling="no"></iframe>
        <script src="<?php echo plugins_url( '/dist/js/iframeResizer.min.js', THEIA_SMART_THUMBNAILS_MAIN ) ?>"></script>
        <script>
            iFrameResize({}, '.theiaSmartThumbnails_news');
        </script>
        <?php
    }
}
