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

/*
 *  Excludes Password Protected posts from the
 *  Archive and Category loops.
 *
 */

function exclude_passworded_posts($post) {
  global $post;
  $b16ecom_exclude = post_password_required($post->ID);

  if ($b16ecom_exclude == true) {
    $excludepass = false;
  } else {
    $excludepass = true;
  }
  return apply_filters('exclude-passworded-posts', $excludepass);
}

function b16ecom_comment_count() {

  global $post;
  $commentsnum = wp_count_comments($post->ID);

  if ($commentsnum->total_comments > 14) {
    $popular = '<p style=quot;font-size: 80%; font-style: italics; quot;><span class=&quot;genericon genericon-digg&quot;></span>Popular Post :</p>';
  } else {
    $popular = '';
  }

  return $popular;

}
