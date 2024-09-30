<?php /* Template Name: Page : Profile */ ?>


<?php
    $user = Account::getUser();
    if (isset($user) && !empty($user)) {
        wp_redirect(get_permalink(getPageByFilename("account.php"))); exit();
    }
?>


<?php get_header()?>

<section id="profile">

    <div class="profile-container">
        <?php
            include_once(__DIR__."/templates/pages/profile/welcome.php");
            include_once(__DIR__."/templates/pages/profile/sign-up.php");
            include_once(__DIR__."/templates/pages/profile/created.php");
        ?>

    </div>
</section>

<?php
get_footer();
?>