<?php

if (!function_exists('capstan_account_login')) {
    function capstan_account_login() {
        $email = capstan_escape_value($_POST['email']);
        $password = capstan_escape_value($_POST['password']);

        $errors = [];
        if(empty($email)) $errors[] = "email";
        if(empty($password)) $errors[] = "password";
        if(!empty($errors)) {echo json_encode(["success" => false, "errors"  => $errors]); die();}


        $res = Account::connect($email, $password);

        echo json_encode(["success" => true, "code" => $res["code"]]);
        die();
    }
}
add_action( 'wp_ajax_nopriv_capstan_account_login', 'capstan_account_login');
add_action( 'wp_ajax_capstan_account_login', 'capstan_account_login');
