<form name="profile-sign-up" class="profile-content hidden" data-component="ProfileSignUp">
    <div class="ariane-light-grey"><?php _e("Inscription", "capstan"); ?></div>
    <div class="profile-title"><?php _e("Création de compte", "capstan"); ?></div>

    <div class="profile-text"><?php _e("Veuillez saisir les informations ci-dessous pour la création de votre compte", "capstan"); ?></div>

    <?php getTemplate("forms/text", array("placeholder" => "Adresse email", "name" => "email", "type" => "email", "required" => true, "error" => "Votre email est obligatoire")); ?>
    <?php getTemplate("forms/text", array("placeholder" => "Prénom", "name" => "firstname", "required" => true, "error" => "Votre prénom est obligatoire")); ?>
    <?php getTemplate("forms/text", array("placeholder" => "Nom", "name" => "lastname", "required" => true, "error" => "Votre nom est obligatoire")); ?>
    <?php getTemplate("forms/text", array("placeholder" => "Mot de passe", "name" => "password", "type" => "password", "required" => true, "error" => "Your password is mandatory")); ?>

    <?php getTemplate("forms/button", array("title" => "Créer un compte", "disabled" => true)); ?>

    <div class="profile-text"><?php _e("En créant un compte, vous acceptez", "capstan"); ?> <a href="<?php echo get_permalink(get_post(364)); ?>" class='no-history' target='_blank'>nos conditions générales d'utilisation</a><i class="icon icon-right"></i></div>

    <div class="profile-footer">
        <div class="profile-text2 trigger-profile-navigation" data-navigation="profile-welcome">
            <?php _e("Vous avez déjà un compte ?", "capstan"); ?>&nbsp;<b> <span><?php _e("Se connecter", "capstan"); ?></span> <i class="icon icon-right"></i></b>
        </div>
    </div>

</form>