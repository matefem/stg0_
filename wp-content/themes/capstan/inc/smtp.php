<?php
    function my_phpmailer_configuration( $phpmailer ) {
        $phpmailer->isSMTP();     
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->SMTPAuth = true; // Indispensable pour forcer l'authentification
        $phpmailer->Port = 587;
        $phpmailer->Username = 'noreplyweb@capstan.fr';
        $phpmailer->Password = 'zertiP67f4dx5d$6$LM$477w@5@Naxx5477w@5@6$LM$477w@5@Naxx5NPaxx5dPmd';

        // Configurations complémentaires
        $phpmailer->SMTPSecure = "tls"; // Sécurisation du serveur SMTP : ssl ou tls
        $phpmailer->From = "noreplyweb@capstan.fr"; // Adresse email d'envoi des mails
        //$phpmailer->FromName = "Nom Exemple"; // Nom affiché lors de l'envoi du mail
    }
    add_action( 'phpmailer_init', 'my_phpmailer_configuration' );
?>