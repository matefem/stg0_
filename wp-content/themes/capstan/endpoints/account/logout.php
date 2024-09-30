<?php

if (!function_exists('capstan_account_logout')) {
    function capstan_account_logout() {

        Account::setUser(null);

        echo json_encode(["success" => true]);
        die();
    }
}
add_action( 'wp_ajax_nopriv_capstan_account_logout', 'capstan_account_logout');
add_action( 'wp_ajax_capstan_account_logout', 'capstan_account_logout');
