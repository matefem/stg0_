<?php

/*
 * Copyright 2012-2024, Theia Smart Thumbnails, WeCodePixels, https://wecodepixels.com
 */

namespace WeCodePixels\TheiaSmartThumbnails\Admin;

class Admin {
    public static function staticConstruct() {
        add_action( 'admin_init', __NAMESPACE__ . '\\Admin::admin_init' );
        add_action( 'admin_menu', __NAMESPACE__ . '\\Admin::admin_menu' );
    }

    public static function admin_init() {
        register_setting( 'tst_options_general', 'tst_general', __NAMESPACE__ . '\\Admin::validate' );
        register_setting( 'tst_options_sizes', 'tst_sizes', __NAMESPACE__ . '\\Admin::validate' );
        register_setting( 'tst_options_dashboard', 'tst_dashboard', __NAMESPACE__ . '\\Admin::validate' );
    }

    public static function admin_menu() {
        add_options_page( 'Theia Smart Thumbnails Settings', 'Theia Smart Thumbs', 'manage_options', 'theia-smart-thumbnails', __NAMESPACE__ . '\\Admin::do_page' );
    }

    public static function do_page() {
        $tabs = array(
            'dashboard' => array(
                'title' => __( "Dashboard", 'theia-smart-thumbnails' ),
                'class' => __NAMESPACE__ . '\\Dashboard',
            ),
            'general'   => array(
                'title' => __( "General", 'theia-smart-thumbnails' ),
                'class' => __NAMESPACE__ . '\\General',
            ),
            'sizes'     => array(
                'title' => __( "Thumbnail Sizes", 'theia-smart-thumbnails' ),
                'class' => __NAMESPACE__ . '\\Sizes',
            ),
            'account'   => array(
                'title' => __( "Account", 'theia-smart-thumbnails' ),
                'class' => __NAMESPACE__ . '\\Account',
            ),
            'contact'   => array(
                'title' => __( "Contact Us", 'theia-smart-thumbnails' ),
                'class' => __NAMESPACE__ . '\\Contact',
            )
        );
        if ( array_key_exists( 'tab', $_GET ) && array_key_exists( $_GET['tab'], $tabs ) ) {
            $current_tab = $_GET['tab'];
        } else {
            $current_tab = 'dashboard';
        }
        ?>

        <div class="wrap">
            <h2 class="theiaSmartThumbnails_adminTitle">
                <a href="https://wecodepixels.com/theia-smart-thumbnails-for-wordpress/?utm_source=theia-smart-thumbnails-for-wordpress"
                   target="_blank">
                    <img src="<?php echo plugins_url( '/assets/images/theia-smart-thumbnails-thumbnail.png', THEIA_SMART_THUMBNAILS_MAIN ); ?>">
                </a>

                Theia Smart Thumbnails

                <a class="theiaSmartThumbnails_adminLogo"
                   href="https://wecodepixels.com/?utm_source=theia-smart-thumbnails-for-wordpress"
                   target="_blank">
                    <img src="<?php echo plugins_url( '/assets/images/wecodepixels-logo.png', THEIA_SMART_THUMBNAILS_MAIN ); ?>">
                </a>
            </h2>

            <h2 class="nav-tab-wrapper">
                <?php
                foreach ( $tabs as $id => $tab ) {
                    $class = 'nav-tab';
                    if ( $id == $current_tab ) {
                        $class .= ' nav-tab-active';
                    }
                    ?>
                    <a href="?page=theia-smart-thumbnails&tab=<?php echo $id; ?>"
                       class="<?php echo $class; ?>"><?php echo $tab['title']; ?></a>
                    <?php
                }
                ?>
            </h2>
            <div class="theia-smart-thumbnails-admin-<?= $current_tab ?>">
                <?php
                $class = $tabs[ $current_tab ]['class'];
                $page  = new $class;
                $page->echoPage();
                ?>
            </div>
        </div>
        <?php
    }

    public static function validate( $input ) {
        return $input;
    }
}
