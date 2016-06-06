<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
$socialpos = get_theme_mod('social_sharing_position');
$hasthumb = get_the_post_thumbnail();
$breadcrumbs = get_theme_mod('display_yoast_breadcrumbs');

if (($breadcrumbs == 'onposts') || ($breadcrumbs == 'both')) {
	$hasbread = 'hasbread';
} else {
	$hasbread = 'nobread';
}

if(empty($hasthumb)) {$withthumb = 'nothumb';} else {$withthumb = 'hasthumb';}

?>
	<header class="entry-header custom-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>
	</header><!-- .entry-header -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// Post thumbnail.
		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
			the_post_thumbnail('featured-image');
		}
	?>
	<div class="extras <?php echo $withthumb, ' ', $hasbread; ?>">
	<?php 
		if (($breadcrumbs == 'onposts') || ($breadcrumbs == 'both')) {
			if ( function_exists('yoast_breadcrumb') ) {
				yoast_breadcrumb('<p id="breadcrumbs">','</p>');
			} 
		}
	?>
		<?php
			if((is_single()) && (('top' == $socialpos) || ('both' == $socialpos))) { ?>
				<div class="rrssb-top"> 
					<?php get_template_part('partials/social', 'sharing'); ?>
				</div> 
				<?php
			}
		?>
	</div>
	<div class="entry-content">
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				esc_html__( 'Continue reading %s', 'twentyfifteen' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'twentyfifteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'twentyfifteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php
		// Author bio.
		if ( is_single() && get_the_author_meta( 'description' ) ) :
			get_template_part( 'author-bio' );
		endif;
		?>
		<?php
		if((is_single()) && (('bottom' == $socialpos) || ('both' == $socialpos))) { ?>
			<div class="rrssb-bottom"> 
				<?php	get_template_part('partials/social', 'sharing'); ?>
			</div>
			<?php
		} ?>
	<footer class="entry-footer">
		<?php if ((is_single()) && (is_active_sidebar( 'post-footer' )) ) : ?>
			<div class="post-footer-sidebar">
					<?php dynamic_sidebar( 'post-footer' ); ?>
			</div>
		<?php endif; ?>
		<?php twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( esc_html__( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
