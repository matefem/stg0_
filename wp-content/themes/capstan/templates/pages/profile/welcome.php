<?php
    $redirectUrl = (isset($_GET["requested-url"]) && !empty($_GET["requested-url"]))?$_GET["requested-url"]:get_permalink(getPageByFilename("account.php"));
?>

<form name="profile-welcome" class="profile-content" data-component="ProfileWelcome" data-success-redirect="<?php echo $redirectUrl; ?>">
    <div class="ariane-light-grey"><?php t("Connexion"); ?></div>

    <div class="profile-title"><?php t("Bienvenue !"); ?></div>
    <div class="profile-text"><?php t("Veuillez saisir votre adresse email et mot de passe."); ?></div>

    <?php getTemplate("forms/text",
        array("placeholder" => t("Adresse email", false), "name" => "email", "type" => "email",
            "required" => true)); ?>

    <?php getTemplate("forms/text", array("placeholder" => t("Mot de passe", false), "name" => "password", "type" => "password",
        "required" => true, "error" => t("Le mot de passe que vous avez soumis est incorrect", false))); ?>

    <?php getTemplate("forms/button", array("title" => t("Se connecter", false), "disabled" => true)); ?>

    <div class="profile-text2 trigger-profile-navigation" data-navigation="profile-reset-password">
        <?php t("Mot de passe oublié ?"); ?>&nbsp;<b><a href="https://news.capstan.fr/password/reset" class="no-history"><?php t("Réinitialiser le mot de passe");?> <i class="icon icon-right"></i></a></b>
    </div>

    <div class="profile-footer">
        <div class="profile-text2 trigger-profile-navigation" data-navigation="profile-sign-up">
            <?php t("Vous n'avez pas de compte ?"); ?>&nbsp;<b><span><?php t("Créer un compte"); ?></span> <i class="icon icon-right"></i></b>
        </div>
    </div>

</form>