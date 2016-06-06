<?php /*

  This file is part of a child theme called Matt 2016.
  Functions in this file will be loaded before the parent theme's functions.
  For more information, please read https://codex.wordpress.org/Child_Themes.

*/

// this code loads the parent's stylesheet (leave it in place unless you know what you're doing)

function matt2016_theme_enqueue_styles() {
    wp_enqueue_style('beyond2016-main-css', get_template_directory_uri() . '/main.css');
    wp_enqueue_style('beyond2016-child', get_stylesheet_directory_uri() . '/style.css', array('beyond2016-main-css'));
}
add_action('wp_enqueue_scripts', 'matt2016_theme_enqueue_styles');

/*  Add your own functions below this line.
    ======================================== */ 

add_filter( 'get_the_archive_title', 'custom_give_archive_title');

function custom_give_archive_title( $title ) {

    if( is_post_type_archive('give_forms') ) {

        $title = __( 'My Plugins and Themes', 'beyond2016' );

    }

    return $title;

}

//add_filter('beyond2016_after_content_single', 'matt2016_add_cc_licensing');

function matt2016_add_cc_licensing() {

  //$content = the_content();
  
  if (is_singular('post')) {

    $cc = '<p prefix="dct: http://purl.org/dc/terms/ cc: http://creativecommons.org/ns#" class="cc-block"><a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="CC BY-NC-SA 4.0" class="cc-button     lazyloaded" src="https://www.mattcromwell.com/wp-content/plugins/creative-commons-configurator-1/media/cc/by-nc-sa/4.0/80x15.png" data-lazy-src="https://www.mattcromwell.com/wp-content/plugins/creative-commons-configurator-1/media/cc/by-nc-sa/4.0/80x15.png" width="80" height="15"><noscript>&lt;img alt="CC BY-NC-SA 4.0" class="cc-button" src="https://www.mattcromwell.com/wp-content/plugins/creative-commons-configurator-1/media/cc/by-nc-sa/4.0/80x15.png" width="80" height="15" /&gt;</noscript></a><a href="https://www.mattcromwell.com/building-front-end-profile-editor-caldera-forms/" title="Permalink to Caldera Forms Makes for a Great Profile Editor"><link href="http://purl.org/dc/dcmitype/Text" rel="dct:type" property="dct:type"><span property="dct:title">Caldera Forms Makes for a Great Profile Editor</span></a> by <a href="https://www.mattcromwell.com/author/matt/" property="cc:attributionName" rel="cc:attributionURL">Matt Cromwell</a> is licensed under a <a rel="license" target="_blank" href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>';
  
    
  } 
  return $cc;
}
