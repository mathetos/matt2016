<?php
/**
 * Template Name: Thank You
 *
 * @package WordPress
 * @subpackage Matt2015
 * @since Matt2015 1.0
 */
 $TYreferrer = get_post_meta($post->ID, '_referrer', true);
 
 
 // This is to check if the request is coming from a specific URL
$ref = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// if($ref !== $TYreferrer) {
  // die("Sorry, there's nothing here for you to see at the moment.");
// }
 
 
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php 
					// echo '<h2>' . $ref  . '</h2>';
					// echo '<h2>' . $TYreferrer  . '</h2>' ; 
		?>
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post(); ?>

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
				twentyfifteen_post_thumbnail();
			?>
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
			
			<?php 
				// Previous/next post navigation.
				the_post_navigation( array(
				'next_text' => _x( '<span class="meta-nav">Next <span class="screen-reader-text">post: </span></span><span class="post-title">%title</span>', 'Next post link', 'twentyfifteen' ),
				'prev_text' => _x( '<span class="meta-nav">Previous <span class="screen-reader-text">post: </span></span><span class="post-title">%title</span>', 'Previous post link', 'twentyfifteen' )
			) );

		// End the loop.
		endwhile;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>