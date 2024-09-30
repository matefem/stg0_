<?php
function capstan_toolbars( $toolbars ) {
    $toolbars['Very Simple']    = array();
    $toolbars['Very Simple'][1] = array( 'bold', 'italic', 'underline', 'bullist', 'numlist', 'fullscreen', 'outdent', 'indent', 'undo', 'redo');

    $toolbars['Just Italic']    = array();
    $toolbars['Just Italic'][1] = array( 'italic');

    return $toolbars;
}

add_filter( 'acf/fields/wysiwyg/toolbars', 'capstan_toolbars' );
