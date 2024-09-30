<?php /* Template Name: Page : Account */ ?>


<?php $user = Account::getUser();
    if (!isset($user) || empty($user)) {wp_redirect(get_permalink(getPageByFilename("profile.php"))); exit();}
?>

<?php get_header()?>

<section id="account">

    <div class="account-head">
        <h1 class="head-title"><?php t("Compte"); ?></h1>
        <div class="head-subtitle"><?php echo $user["first_name"]. ' '.$user["last_name"]; ?></div>

        <div class="head-logout" data-component="AccountLogout" data-redirect="<?php echo get_permalink(getPageByFilename("profile.php")); ?>"><?php t("Déconnexion"); ?> <i class="icon icon-right"></i></div>
        <a href="<?php echo get_permalink(getPageByFilename("articles.php")); ?>?favoris=1" class="button-favoris black">
            <i class="icon-favoris"></i>
            <span><?php t('Voir mes articles favoris'); ?></span>
        </a>
    </div>

    <form data-component="AccountUpdate">

        <?php getTemplate("forms/button", array("title" => t("Mettre à jour"), "disabled" => true, "classes" => "hide-desktop")); ?>

        <div class="separator">
            <?php getTemplate("forms/text", array("label" => "Prénom", "name" => "firstname", "required" => true,
                "error" => t("Votre prénom est obligatoire"), "value" => $user["first_name"])); ?>
        </div>
        <div class="separator">
            <?php getTemplate("forms/text", array("label" => "Nom", "value" => "", "name" => "lastname", "required" => true,
                "error" => t("Votre nom est obligatoire"), "value" => $user["last_name"])); ?>
        </div>
        <div class="separator">
            <?php getTemplate("forms/text", array("label" => "Adresse email", "name" => "email", "type" => "email", "required" => true,
                "error" => "Votre email est obligatoire", "value" => $user["email"])); ?>
        </div>
        <div class="separator no-padding-top buttons-wrapper">
            <?php getTemplate("forms/button", array("title" => t("Mettre à jour", false), "disabled" => true, "classes" => "hide-mobile")); ?>
            <?php getTemplate("forms/button", array("title" => t("Changer de mot de passe", false), "classes" => "transparent button-change-password", "a-classes" => " no-history",
                "url" => "https://news.capstan.fr/password/reset", "target" => "_blank")); ?>
        </div>
        <div class="separator hide-desktop"></div>
    </form>


</section>

<?php
get_footer();
?>