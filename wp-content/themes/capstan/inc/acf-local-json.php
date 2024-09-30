<?php

add_filter( 'acf/settings/save_json', 'capstan_acf_json_save_point' );

function capstan_acf_json_save_point( $path ) {
    $path = get_template_directory() . '/acf';
    return $path;
}

add_filter( 'acf/settings/load_json', 'capstan_acf_json_load_point' );

function capstan_acf_json_load_point( $paths ) {
    unset( $paths[0] );
    $paths[] = get_template_directory() . '/acf';
    return $paths;

}
