<?php
/*
Plugin Name: Capstan - News synchronisation
Plugin URI: http://60fps.fr
Description: Synchronisation des news depuis https://news.capstan.fr/
Author: 60fps
Version: 1.0
Author URI: http://60fps.fr
*/


if (!function_exists('newssynchronisation_60fps_menu')) {
	function newssynchronisation_60fps_menu() {
        add_options_page( 'Synchroniser les news', 'Synchroniser les news', 'manage_options', 'newssynchronisation_60fps', 'newssynchronisation_60fps' );
	}
}


function newssynchronisation_60fps() {
    $action = "";
    if (isset($_GET["60fpsaction"])) $action = $_GET["60fpsaction"];
    ?>

    <div class="wrap">
        <h1><?php echo 'Synchronisation des news depuis l\'api news.capstan'; ?></h1>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <td>
                    <?php
                    if (!empty($action)) {
                        // require_once(__DIR__."/api.php");
                        // $api = new API();

                        if ($action == "synchronise") {
                            // echo $api->synchronise(false);
                            exec("php ".__DIR__."/api.php synchronise false");
                        }
                        else if ($action == "synchroniseall") {
                            // echo $api->synchronise(true);
                            exec("php ".__DIR__."/api.php synchroniseall true");
                        }

                        echo 'OK'; die();
                    }
                    else { ?>
                        <a href="options-general.php?page=newssynchronisation_60fps&60fpsaction=synchronise" class="button-primary">Lancer la synchronisation</a>
                        <a href="options-general.php?page=newssynchronisation_60fps&60fpsaction=synchroniseall" class="button-primary">Lancer une synchronisation complète (long +/-15 minutes)</a>
                    <?php } ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <br/><br/><br/>
    </div>
<?php
}

add_action( 'admin_menu', 'newssynchronisation_60fps_menu' );

?>


