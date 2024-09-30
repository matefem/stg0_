<?php
    $p = getParams(array("article" => array(), "classes" =>  ""));
    $post = $p["article"];

    $authors = get_field("authors", $post);
    $authorsNames = [];
    $authorsPictures = [];
    if ($authors && sizeof($authors) > 0) {
        foreach($authors as $author) {
            if ($author["capstan_id"] && get_permalink($author["capstan_id"])) {
                $authorsNames[] = '<a href="'.get_permalink($author["capstan_id"]).'">'.$author["name"].'</a>';
            }
            else {
                $authorsNames[] = '<span>'.$author["name"].'</span>';
            }
            if ($author["image"]) $authorsPictures[] = '<img src="' .$author["image"]["url"]. '" alt="'.$author["name"].'" />';
        }
    }
?>

<div class="post-author">

    <?php if (sizeof($authorsPictures) > 0) { ?>
    <div>
        <?php  echo implode('', $authorsPictures); ?>
    </div>
    <?php } ?>
    <span>
        <?php t("Publié le");?> <?php echo get_the_date('d/m/Y'); ?>
        <?php
            if (sizeof($authorsNames) > 0) {
                echo ' '.t("par"). '<span>' .implode(' & ', $authorsNames).'</span>';
            }
        ?>
    </span>

    <button class="button-print" onClick="window.print()">
        <i class="icon-print"></i>
        <span><?php t("Imprimer l'article"); ?></span>
    </button>
</div>