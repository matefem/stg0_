<?php

function sixtyfps_404redirect(){
    if (is_404()){
        $currentUrl = home_url($_SERVER['REQUEST_URI']);
        if (preg_match('/\/articles\/[0-9]*/', $currentUrl, $matches)) {

            $id = str_replace('/', '', str_replace('articles', '', $matches[0]));
            if (is_numeric($id)) {
                require_once(__DIR__.'/../../../plugins/news-synchronisation_60fps/api.php');

                $api = new API();
                $result = $api->synchroniseOne($id);
                // if false => article already exist

                if ($result) {
                    header("Refresh:0");
                    die();
                }
            }
        }
    }
}
add_action( 'template_redirect', 'sixtyfps_404redirect' );