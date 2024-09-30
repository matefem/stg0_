<?php
    $p = getParams(array("article" => array(), "classes" =>  ""));
    $post = $p["article"];
    $thumbnail = get_post_thumbnail_id($post->ID);
    $type = trim(get_the_terms($post, "post-type")[0]->slug);
?>

<div class="article-card <?php echo $p["classes"]; ?>" data-id="<?php echo $post->ID; ?>" data-capstanid="<?php echo get_field("capstan_news_id", $post); ?>" data-follow-link>
    <div class="picture-title">
        <?php if (!empty($thumbnail)) { ?>
            <div class="picture">
                <picture>
                    <source srcset="<?php echo wp_get_attachment_image_src($thumbnail, 'mobile')[0]; ?>" media="(min-width: 960px)">
                    <img src="<?php echo wp_get_attachment_image_src($thumbnail)[0]; ?>" alt="" draggable="false">
                </picture>
                <?php if ($type == "videos") { ?>
                    <img class="video-logo" src="<?php echo get_template_directory_uri().'/resources/assets/img/news/video.jpg'; ?>" alt="" width="60" height="60"/>
                <?php } ?>
            </div>

        <?php } ?>
        <div class="title hide-desktop"><?php echo $post->post_title; ?></div>
    </div>

    <div class="text-wrapper">
        <a target="_self" href="<?php echo get_permalink($post);?>" class="title hide-mobile"><?php echo $post->post_title; ?></a>
        <div class="theme">
            <?php foreach(get_the_terms($post, "post-theme") as $theme) {
                echo '<span>'.$theme->name.'</span>';
            } ?>
        </div>
        <p><?php echo mb_substr(str_replace("\n", '', strip_tags(trim(html_entity_decode(get_the_content(), ENT_QUOTES, 'UTF-8')))), 0, 200)."..."; ?></p>

        <div class="card-footer">
            <div class="type"><?php echo get_the_terms($post, "post-type")[0]->name; ?></div>
            &nbsp;—&nbsp;
            <div class="date"><?php t('Publié le'); ?> <?php echo get_the_date('d/m/Y'); ?></div>
        </div>
    </div>

    <div class="separator"></div>
</div>