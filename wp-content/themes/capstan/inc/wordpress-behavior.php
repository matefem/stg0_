<?php

// add_action('admin_menu', function () {remove_menu_page( 'edit-comments.php' );});

function remove_posts_menu()
{
  // remove_menu_page('edit.php');
  remove_menu_page('tools.php');
  // remove_menu_page('themes.php');
  remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'remove_posts_menu');

add_theme_support('post-thumbnails');

add_filter('pre_post_link', 'changePostLink', 10, 3);
function changePostLink($permalink, $post, $leavename)
{
  if (get_post_type($post) == 'post') {
    return "/articles" . $permalink;
  }
  return $permalink;
}

function wp1482371_custom_post_type_args($args, $post_type)
{
  if ($post_type == "post") {
    $args['rewrite'] = array(
      'slug' => 'articles'
    );
  }
  return $args;
}
add_filter('register_post_type_args', 'wp1482371_custom_post_type_args', 20, 2);

// Redirect article with only article_id if full string is not found
function caspstan_on_404()
{
  if (is_404()) {
    $url = parse_url($_SERVER['REQUEST_URI'])["path"];
    if (strpos('articles/', $url) >= 0) {
      $urlExplode = explode('articles/', $url);
      $urlSlug = '';
      $urlId = '';
      if ($urlExplode[1]) {
        $urlSlug = $urlExplode[1];
        $urlTab = explode('-', $urlSlug);
        $urlId = $urlTab[0];
      }
      if (!empty($urlId) && !empty($urlTab[1]) && $urlId != get_permalink()) {
        wp_safe_redirect($urlId);
      }
    }
  }
}
add_action('template_redirect', 'caspstan_on_404');

function remove_post_revisions($num, $post)
{
  if ($post->post_type == 'post') {
    return 0;
  }
  return $num;
}
add_filter('wp_revisions_to_keep', 'remove_post_revisions', 10, 2);
