<?php

// php wp-content/plugins/news-synchronisation_60fps/api.php synchroniseall true/false (force update)
// php wp-content/plugins/news-synchronisation_60fps/api.php synchronise true/false (force update)

require_once(__DIR__ . '/../../../vendor/autoload.php');


class API
{

    private $_baseUrl = "https://news.capstan.fr/api-capstan/";
    private $_client;
    private $_token = "TWPTWMiaudRpGBonlfQrsG78UUdi2nBK8MLHIPQmSL7aNbAs3s0wo1SrTVjD";
    private $_forceUpdate;

    function __construct($forceUpdate = false)
    {
        $this->_client = new GuzzleHttp\Client(['base_uri' => $this->_baseUrl, 'verify' => false]);
        $this->_forceUpdate = $forceUpdate;

        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // ini_set('error_reporting', 1);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2G');
        // error_reporting(E_ALL);
        error_reporting(E_ERROR | E_PARSE);
    }

    function test()
    {
        echo $this->call("articles")["code"];
    }

    function getArticle($id = 1)
    {
        try {
            return $this->call("articles/" . $id)["body"]["data"];
        } catch (Exception $e) {
            return false;
        }
    }

    function saveFile($url, $postId, $filename = null)
    {
        global $wpdb;

        if (empty($url)) {
            return 0;
        }
        $uploadDir = wp_upload_dir();

        if (!$filename) $filename = basename($url);

        $filename = iconv('UTF-8', 'ASCII//TRANSLIT', $filename);
        $filenameNoChars = strtolower(preg_replace('/[^0-9.\-A-Za-z]/', '', $filename));

        $query = $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_title = %s AND post_parent = %d", $filename, $postId);
        $existAndAttached = $wpdb->get_results($query, ARRAY_A);

        $destination = '';

        if (count($existAndAttached) > 0) {
            $destination = $existAndAttached[0]['guid'];
        } else {
            $destination = $uploadDir['path'] . '/' . $filenameNoChars;
        }

        $picture = file_get_contents($url, false, stream_context_create(array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        )));

        if (!$picture) {
            echo 'Could not get picture ' . $url . ' on post ' . $postId;
            file_put_contents(__DIR__ . "/logs-pictures.txt", "\nFAIL : " . $url, FILE_APPEND);
            return null;
        } else {
            // file_put_contents(__DIR__."/logs-pictures.txt", "\nOK : " . $url. " push in ".$destination, FILE_APPEND);
        }

        $exist = (count($existAndAttached) > 0 ? true : false) || file_exists($destination);

        file_put_contents($destination, $picture);

        $filetype = wp_check_filetype($destination, null);

        $attachment = array(
            'guid'           => $destination,
            'post_mime_type' => $filetype['type'],
            'post_title'     => $filename,
            'post_content'   => '',
            'post_status'    => 'publish'
        );

        if ($exist) {
            $attach_id = attachment_url_to_postid($uploadDir['url'] . '/' . $filenameNoChars);

            // file_put_contents(__DIR__."/logs-pictures.txt", "\nExist, attach : " . $attach_id. " with ".$uploadDir['url'] . '/' . $filenameNoChars, FILE_APPEND);
            if ($attach_id) wp_update_attachment_metadata($attach_id, $attachment);
            else {
                $attach_id = wp_insert_attachment($attachment, $destination, $postId);
            }
        } else {
            // file_put_contents(__DIR__."/logs-pictures.txt", "\Insert : " . $attachment. " dest ".$destination . ' with postid' .$postId, FILE_APPEND);
            $attach_id = wp_insert_attachment($attachment, $destination, $postId);
        }
        return $attach_id;
    }

    function syncThemes()
    {
        $res = $this->call("themes");
        $themes = array_map(function ($obj) {
            return $obj["name"];
        }, $res["body"]["data"]);
        foreach ($themes as $theme) {
            wp_insert_term($theme, "post-theme");
        }

        return $themes;
    }

    function syncTypes()
    {
        $res = $this->call("types");
        $types = array_map(function ($obj) {
            return $obj["name"];
        }, $res["body"]["data"]);

        foreach ($types as $type) {
            wp_insert_term($type, "post-type");
        }
        return $types;
    }

    function synchroniseOne($id)
    {
        $article = $this->getArticle($id);

        // $article = Array( "id" => 1882, "title" => "Lorem ipsum dolor sit amet", "type" => Array ( "key" => "briefs", "name" => "Brèves" ), "format" => "default", "content" =>
        // "Lorem ipsum dolor sit amet", "published_at" => "1670922960", "themes" => Array ( Array ( "id" => 20, "name" => "Covid-19" ) ) );

        if ($article) {
            return $this->saveArticle($article);
        }
        return false;
    }

    function synchronise($full = false)
    {
        $page = 1;
        $totalPages = 1;
        $totalArticlesSync = 0;
        global $wpdb;

        if ($full) $this->_forceUpdate = true;

        $articleIds = array();

        $isRuning = @file_get_contents(__DIR__ . "/is-running.txt");

        // if (!empty($isRuning) && $isRuning > 0) {echo 'already runing'; return;}
        file_put_contents(__DIR__ . "/logs.txt", "\nStarting at " . date("Y-m-d H:i:s"), FILE_APPEND);
        file_put_contents(__DIR__ . "/is-running.txt", time());

        // Clean foreing keys
        $query = 'DELETE FROM wp_postmeta WHERE wp_postmeta.meta_key = "capstan_news_id" AND wp_postmeta.post_id NOT IN (SELECT id from wp_posts WHERE post_type = "post")';
        $wpdb->query($query);

        do {
            // echo "articles?page=".$page.'<br/>';
            $res = $this->call("articles?page=" . $page);
            if ($res["code"] != 200) {
                exit("Lancement de la synchronisation impossible : code " . $res["code"]);
            }

            $articles = $res["body"]["data"];

            $totalPages = $res["body"]["meta"]["last_page"];
            $maximumPage = $full ? $res["body"]["meta"]["last_page"] : min(2, $res["body"]["meta"]["last_page"]);

            // TO DEBUG ONE ARTICLE
            // $articles = [$this->getArticle(116)["body"]["data"]];
            // print_r($articles[0]);
            // die();

            foreach ($articles as $article) {
                $articleIds[] = $article["id"];

                if ($page > $maximumPage) {
                    continue;
                }

                $result = $this->saveArticle($article);
                if ($result) {
                    $totalArticlesSync++;
                }
            }
            $page++;
        } while ($page < $totalPages);

        $query = 'DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT ID FROM wp_posts)';
        $wpdb->query($query);
        $wpdb->query($query);

        // Clean duplicates
        $query = 'SELECT MAX(wp_posts.ID) as id, count(wp_posts.ID) as count FROM  wp_posts INNER JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.id AND wp_postmeta.meta_key = "capstan_news_id" WHERE wp_posts.post_type = "post" GROUP BY wp_postmeta.meta_value HAVING count > 1';
        $results = $wpdb->get_results($query);
        if (!empty($results) && sizeof($results) > 0) {
            foreach ($results as $res) {
                if ($res->id > 0) wp_delete_post($res->id);
            }
        }


        if (sizeof($articleIds) > 10) {
            $query = 'DELETE FROM wp_posts WHERE wp_posts.post_type = "post" AND wp_posts.id NOT IN (SELECT post_id FROM wp_postmeta WHERE wp_postmeta.meta_key = "capstan_news_id" AND wp_postmeta.meta_value IN (' . implode(',', $articleIds) . '))';
            $postsDeleted = $wpdb->query($query);

            echo '<br/>' . $postsDeleted . ' article(s) supprimés<br/>';
        }
        file_put_contents(__DIR__ . "/logs.txt", "\Ending at " . date("Y-m-d\TH:i:sO"), FILE_APPEND);
        file_put_contents(__DIR__ . "/is-running.txt", "0");

        echo '<br/>Synchronisation terminée.<br/>' . $totalArticlesSync . ' article(s) mis à jour';
    }

    private function saveArticle($article, $onlyPublished = false)
    {
        $date = new DateTimeZone('Europe/Paris');
        $offset = $date->getOffset(new DateTime());

        $params = array(
            'numberposts'    => 1,
            'post_type'        => 'post',
            'meta_key'        => 'capstan_news_id',
            'meta_value'    => $article["id"],
            'post_status'  => ['publish', 'future', 'private', 'pending']
        );
        if ($onlyPublished) {
            $params['post_status'] = 'publish';
        }

        $existingPost = get_posts($params);
        $existingPost = !empty($existingPost) && sizeof($existingPost) > 0 ? $existingPost[0]->ID : null;

        if (!$existingPost || ($existingPost && $this->_forceUpdate)) {

            if ($offset == 3600) {
                $date = date('Y-m-d H:i:s', strtotime('+1 hour', $article["published_at"]));
            } else $date = date('Y-m-d H:i:s', strtotime('+2 hours', $article["published_at"]));


            $post = array(
                'ID' => $existingPost,
                'post_name' => $article["id"] . '-' . wp_strip_all_tags($article["title"]),
                'post_title' => wp_strip_all_tags($article["title"]),
                'post_content' => $article["content"],
                'post_status' => 'publish',
                'post_date' =>  $date,
                'post_category' => array(get_category_by_slug($article["format"])->term_id)
            );

            if ($existingPost) {
                wp_update_post($post);
                $postId = $existingPost;
            } else {
                $postId = wp_insert_post($post);
            }

            // Acf
            update_field("capstan_news_id", $article["id"], $postId);

            // Picture
            if (isset($article["image"]) && !empty($article["image"]["large"])) {
                $pictureId = $this->saveFile($article["image"]["large"], $postId);
                if ($pictureId) set_post_thumbnail($postId, $pictureId);
            }

            // Embed video or podcast
            if (isset($article["embed"])) {

                if (!empty($article["embed"]["thumb"])) {
                    $pictureId = $this->saveFile($article["embed"]["thumb"], $postId, "thumbnail-" . $postId . '.jpg');
                    if ($pictureId) set_post_thumbnail($postId, $pictureId);
                }

                if (!empty($article["embed"]["url"])) {
                    update_field("video", $article["embed"]["url"], $postId);
                }
            }

            // Files
            if (isset($article["files"]) && sizeof($article["files"]) > 0) {
                $filesData = array();

                foreach ($article["files"] as $file) {
                    $fileId = $this->saveFile($file["url"], $postId, $file["full_name"]);

                    $filesData[] = array("file" => $fileId);
                }
                update_field("files", $filesData, $postId);
            }


            // Authors
            if (isset($article["authors"]) && sizeof($article["authors"]) > 0) {
                $authorData = array();

                foreach ($article["authors"] as $author) {
                    if (isset($author["image"])) {
                        $fileId = $this->saveFile($author["image"]["small"], $postId, $postId . '-' . $author["full_name"] . '.jpg');
                    } else {
                        $fileId = null;
                    }

                    $authorData[] = array("name" => $author["full_name"], "image" => $fileId, "capstan_id" => @$author["capstan_id"]);
                }
                update_field("authors", $authorData, $postId);
            }

            // Taxonomies
            wp_set_post_terms($postId, $article["type"]["name"], "post-type");
            foreach ($article["themes"] as $theme) {
                wp_set_post_terms($postId, $theme["name"], "post-theme");
            }

            return true;
        }
        return false;
    }

    private function call($url)
    {
        $headers = [
            'Capstan-Authorization' => $this->_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $res = $this->_client->request('GET', $this->_baseUrl . $url, ['headers' => $headers]);

        return ["code" => $res->getStatusCode(), "body" => json_decode($res->getBody()->getContents(), true)];
    }
}


if (isset($argv[1])) {
    ini_set("display_errors", 0);
    define('WP_USE_THEMES', false);
    require_once(__DIR__ . '/../../../wp-load.php');

    ini_set("display_errors", 1);

    $action = $argv[1];
    $api = new API($argv[2]);
    echo 'start at ' . date("h:i:sa");

    if ($action == "synchronise") {
        echo $api->synchronise(false);
    } else if ($action == "synchroniseall") {
        echo $api->synchronise(true);
    }
    echo 'end at ' . date("h:i:sa");
}
