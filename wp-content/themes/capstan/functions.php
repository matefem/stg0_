<?php

if (!session_id()) {
	session_set_cookie_params(864000); // 10 jours
    session_start();
}


require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/resources.php';
require_once get_template_directory() . '/inc/wordpress-behavior.php';
require_once get_template_directory() . '/inc/pictures.php';
require_once get_template_directory() . '/inc/menus.php';
require_once get_template_directory() . '/inc/modules.php';
require_once get_template_directory() . '/inc/acf-local-json.php';
require_once get_template_directory() . '/inc/acf-wysiwyg.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/taxonomy.php';
require_once get_template_directory() . '/inc/tiny-mce.php';
require_once get_template_directory() . '/inc/theme.php';
require_once get_template_directory() . '/inc/translation.php';
require_once get_template_directory() . '/inc/ICS.php';
require_once get_template_directory() . '/inc/notfound.php';
// require_once get_template_directory() . '/inc/smtp.php';

require_once get_template_directory() . '/endpoints/articles.php';
require_once get_template_directory() . '/endpoints/article.php';
require_once get_template_directory() . '/endpoints/lawyers.php';
require_once get_template_directory() . '/endpoints/formations.php';
require_once get_template_directory() . '/endpoints/message.php';
require_once get_template_directory() . '/endpoints/careers.php';
require_once get_template_directory() . '/endpoints/formation.php';

require_once get_template_directory() . '/endpoints/account/index.php';


include_once(get_template_directory() . '/account/index.php');

// function wpb_admin_account(){
     
// }
//     add_action('init','wpb_admin_account');
?>