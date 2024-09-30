<?php

if (!function_exists('capstan_toggle_favorite')) {
    function capstan_toggle_favorite() {
        $postid = capstan_escape_value($_POST['postid']);

        $errors = [];
        if(empty($postid)) $errors[] = "postid";
        if(!empty($errors)) {echo json_encode(["success" => false, "errors"  => $errors]); die();}

        $articleId = get_field("capstan_news_id", $postid);

        $res = Account::toggleFavorite($articleId);

        echo json_encode(["success" => true, "result" => $res["code"]]);
        die();
    }
}
add_action( 'wp_ajax_nopriv_capstan_toggle_favorite', 'capstan_toggle_favorite');
add_action( 'wp_ajax_capstan_toggle_favorite', 'capstan_toggle_favorite');
