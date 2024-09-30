<?php

if (!function_exists('capstan_account_status')) {
    function capstan_account_status() { // Pour mettre à jour le header avec le status du compte sans recharger le site
        ob_start();

        $user = Account::getUser();
        if (isset($user) && !empty($user)) {?>
            <a href="<?php echo get_permalink(getPageByFilename("account.php")); ?>" class="connected"><span><?php echo substr($user["first_name"], 0, 1) . substr($user["last_name"], 0, 1); ?></span></a>
        <?php }
        else { ?>
            <a href="<?php echo get_permalink(getPageByFilename("profile.php")); ?>" class="not-connected"><svg width="19" height="19" xmlns="http://www.w3.org/2000/svg"><path d="M17.941 19v-2.118a4.235 4.235 0 0 0-4.235-4.235h-8.47A4.235 4.235 0 0 0 1 16.882V19m8.47-9.53a4.235 4.235 0 1 0 0-8.47 4.235 4.235 0 0 0 0 8.47Z" stroke="#000" stroke-width=".8" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <?php }

        $content = ob_get_contents();
        ob_end_clean();
        echo json_encode(["success" => true, "result" => htmlentities(trim($content))]);
        die();
    }
}
add_action( 'wp_ajax_nopriv_capstan_account_status', 'capstan_account_status');
add_action( 'wp_ajax_capstan_account_status', 'capstan_account_status');


?>