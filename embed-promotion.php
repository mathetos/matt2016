<?php
/**
 * Contains the post embed base template
 *
 * When a post is embedded in an iframe, this file is used to create the output
 * if the active theme does not include an embed.php template.
 *
 * @package WordPress
 * @subpackage oEmbed
 * @since 4.4.0
 */

get_header( 'embedpromo' );

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		get_template_part( 'template-parts/embed', 'promocontent' );
	endwhile;
else :
	get_template_part( 'embed', '404' );
endif;

get_footer( 'embedpromo' );
