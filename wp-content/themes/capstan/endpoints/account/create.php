<?php

if (!function_exists('capstan_account_create')) {
    function capstan_account_create() {
        $email = capstan_escape_value($_POST['email']);
        $password = capstan_escape_value($_POST['password']);
        $firstname = capstan_escape_value($_POST['firstname']);
        $lastname = capstan_escape_value($_POST['lastname']);

        $errors = [];
        if(empty($email)) $errors[] = "email";
        if(empty($password)) $errors[] = "password";
        if(empty($firstname)) $errors[] = "firstname";
        if(empty($lastname)) $errors[] = "lastname";
        if(!empty($errors)) {echo json_encode(["success" => false, "errors"  => $errors]); die();}

        $res = Account::create(["email" => $email, "first_name" => $firstname, "last_name" => $lastname, "password" => $password]);

        // Send Email
        // $globalGuideUrl = get_permalink(getPageByFilename('global-guide.php'));
        // $profileUrl = get_permalink(getPageByFilename('profile.php'));
        // ob_start();
        // include_once(__DIR__.'/../../../mails/exports/account-confirmation.php');
        // $content = ob_get_contents();
        // ob_end_clean();
        // $headers = array('Content-Type: text/html; charset=UTF-8');
        // wp_mail($email, "Welcome to ius laboris", $content, $headers);

        echo json_encode(["success" => true, "code" => $res["code"], "detail" => $res["body"]]);
        die();
    }
}
add_action( 'wp_ajax_nopriv_capstan_account_create', 'capstan_account_create');
add_action( 'wp_ajax_capstan_account_create', 'capstan_account_create');
