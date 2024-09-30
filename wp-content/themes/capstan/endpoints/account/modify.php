<?php

if (!function_exists('capstan_account_update')) {
    function capstan_account_update() {
        $firstname = capstan_escape_value($_POST['firstname']);
        $lastname = capstan_escape_value($_POST['lastname']);
        $email = capstan_escape_value($_POST['email']);

        $errors = [];
        if(empty($firstname)) $errors[] = "firstname";
        if(empty($lastname)) $errors[] = "lastname";
        if(empty($email)) $errors[] = "email";
        if(!empty($errors)) {echo json_encode(["success" => false, "errors"  => $errors]); die();}

        Account::update(["email" => $email, "first_name" => $firstname, "last_name" => $lastname]);

        echo json_encode(["success" => true, "result" => true]);
        die();
    }
}
add_action( 'wp_ajax_nopriv_capstan_account_update', 'capstan_account_update');
add_action( 'wp_ajax_capstan_account_update', 'capstan_account_update');
