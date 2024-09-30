<?php

$modules = array(
	"module-01-hero"				=> ['format-image'],
	"module-02-text"				=> ['editor-justify'],
	"module-03-gallery"				=> ['images-alt', '03 Galerie simple'],
	"module-04-video"				=> ['video-alt3', '04 Image Intercallaire'],
	"module-05-bureau"				=> ['admin-home', '06 Slideshow bureaux'],
	"module-06-inter"				=> ['admin-site-alt3', '06 International'],
	"module-07-three"				=> ['images-alt2', '07 3 Images'],
	"module-08-gallery2"			=> ['images-alt', '08 Galerie témoignages'],
	"module-09-contact"				=> ['format-chat'],
	// ?? "module-10-news"				=> ['welcome-widgets-menus'],
	"module-11-head"				=> ['button'],
	"module-12-grid"				=> ['screenoptions', '12 Grille avocats'],
	// "module-13-profil"				=> [],
	"module-14-quote"				=> ['format-quote', '14 Citation'],
	"module-15-text-2col"			=> ['columns', ' 15 Texte 2 colonnes'],
	"module-16-media"				=> ['admin-media'],
	"module-18-bureau"				=> ['admin-multisite', '19 Grille bureaux'],
	"module-19-image"				=> ['format-image'],
	// "module-20-listing"				=> ['editor-ul'],
	// "module-21-equipe"				=> ['groups'],
	"module-22-formation"			=> ['welcome-learn-more', '22 Formations'],
	"module-23-postes"				=> ['businessman', '23 Postes par bureau'],
	"module-24-awards"				=> ['awards'],
	"module-25-grid-image"			=> ['grid-view', '25 Grille d\'images'],
	"module-26-gallery3"			=> ['images-alt'],
	"module-27-text-img"			=> ['align-right', '27 Texte + image'],
	"module-28-formation"			=> ['list-view', '28 Formations'],
	// ?? "module-29-formation-details"	=> ['welcome-learn-more'],
	"module-30-action"				=> ['editor-ol'],
	"module-31-contact2"			=> [],
	"module-32-guide"				=> [],
	"module-33-metrics"				=> ['chart-area'],
	"module-34-arguments"			=> [],
	"module-35-expertises"			=> ['calculator'],
	"module-36-two"					=> ['columns', '36 Deux images'],
	"module-37-expert"				=> ['admin-users'],
	"module-38-fiche-contact"		=> ['id'],
	"module-39-contact"				=> ['testimonial', '39 Formulaire de contact'],
	"module-40-head-img"			=> [],
	"module-41-talent-head"			=> ['schedule'],
	"module-42-gallery4"			=> ['images-alt', '42 Galerie 4'],
	"module-43-selection-postes"	=> ['businessman', '43 Sélection postes'],
	"module-44-hero-2-images"		=> ['format-gallery'],
	"module-45-gallery-valeurs"		=> ['menu', '45 Galerie valeurs'],
	"module-46-globe"				=> ['admin-site-alt'],
);


function capstan_register_modules() {

	if (function_exists('acf_register_block')) {
		global $modules;

		foreach ($modules as $k => $v) {
			$file = str_replace('module-', '', $k);
			
			$icon = isset($v[0]) ? $v[0] : 'format-aside';

			$title = "Module";

			if(!empty($v[1])) {
				$title = $v[1];
			} else {
				$title = explode('-', $file);

				for($i = 0;$i < count($title); $i++) {
					$tmp = mb_strtoupper($title[$i][0]).substr($title[$i], 1, strlen($title[$i]));
					$title[$i] = $tmp;
				}
				$title = implode(' ', $title);
			}
			
			$title = __($title);

			acf_register_block(array(
				'name'				=> $k,
				'title'				=> $title,
				'render_template'   => 'templates/modules/'.$file.'.php',
				'category'			=> 'formatting',
				'icon'				=> $icon
			));
		}
	}
}
add_action('acf/init', 'capstan_register_modules');



/* add_filter('allowed_block_types', function($block_types, $post) {
	// $template = str_replace('.php', '', get_page_template_slug($post));
	// $postType = get_post_type($post);
	// $category = get_the_category($post);

	global $modules;

	$allowedModules = [];
	foreach($modules as $k => $v) {
		$allowedModules[] = 'acf/'.$k;
	}

	return $allowedModules;
}, 10, 2); */


// add_filter('use_block_editor_for_post', '__return_false', 5);

?>