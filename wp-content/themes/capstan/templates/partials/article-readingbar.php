<?php
    $p = getParams(array("article" => array(), "classes" =>  ""));
    $post = $p["article"];

    $user = Account::getUser();
    $articleId = get_field("capstan_news_id", $post->ID);

    $isInFavorite = false;
    if (isset($user) && $user["favorites"]) $isInFavorite = in_array($articleId, $user["favorites"]);
?>

<div class="post-readingbar">
    <div class="readingbar-content">
        <div class="button-scrolltop">
            <i class="icon-arrow-top2"></i>
        </div>
        <span>
            <?php echo mb_substr(str_replace("\n", '', strip_tags(trim(html_entity_decode($post->post_title, ENT_QUOTES, 'UTF-8')))), 0, 50).'...'; ?>
        </span>

        <div class="hide-desktop">
            <?php if (!isset($user) || empty($user)) { ?>
                <a href="<?php echo get_permalink(getPageByFilename("profile.php"))?>" class="button-favoris-small">
                    <i class="icon-favoris"></i>
                </a>
            <?php } else { ?>
                <button class="button-favoris-small <?php if ($isInFavorite) echo 'black'; ?>">
                    <i class="icon-favoris"></i>
                </button>
            <?php } ?>
        </div>
        <div class="hide-mobile">
            <?php if (!isset($user) || empty($user)) { ?>
                <a href="<?php echo get_permalink(getPageByFilename("profile.php"))?>" class="button-favoris <?php if ($isInFavorite) echo 'black'; ?>" data-id="<?php echo $articleId;?>">
                    <i class="icon-favoris"></i>
					<span class="add"><?php t('Ajouter à mes favoris'); ?></span>
					<span class="remove"><?php t('Retirer de mes favoris'); ?></span>
                </a>
            <?php } else { ?>
                <button class="button-favoris  <?php if ($isInFavorite) echo 'black'; ?>" data-id="<?php echo $articleId;?>">
                    <i class="icon-favoris"></i>
					<span class="add"><?php t('Ajouter à mes favoris'); ?></span>
					<span class="remove"><?php t('Retirer de mes favoris'); ?></span>
                </button>
                <div class="badge-connexion">
                    <a href="<?php echo get_permalink(getPageByFilename("account.php")); ?>" class="connected"><span><?php echo substr($user["first_name"], 0, 1) . substr($user["last_name"], 0, 1); ?></span></a>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="progress"></div>

</div>