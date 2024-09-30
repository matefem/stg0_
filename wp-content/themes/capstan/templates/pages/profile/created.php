<form name="profile-created" class="profile-content hidden">
    <div class="ariane-light-grey"><?php t("Inscription"); ?></div>
    <div class="profile-title"><?php t("Votre compte a été créé"); ?></div>

    <div class="profile-text"><?php t("Votre compte vient d’être créé avec succès. Vous êtes désormais connecté et pouvez ajouter des articles à vos favoris."); ?></div>

    <?php getTemplate("forms/button", array("url" => get_permalink(getPageByFilename("articles.php")), "title" => "Back to the news", "url-classes" => "no-history")); ?>
</form>