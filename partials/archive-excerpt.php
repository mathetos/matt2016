<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// Post thumbnail.
		// twentyfifteen_post_thumbnail();
	?>

	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
			<p class="description">
				<?php 
					$yoastdesc = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);
					$excerptlength = get_theme_mod('excerpt_length', 25);
					$excerpt = get_the_excerpt();
					$content = get_the_content();
					$screenreader = '<p class="readmore"><a href="' . get_permalink() . '"><span class="screen-reader-text">' . get_the_title( ) . '</span>Read More &hellip;</a><p>';
					
					if(!empty($yoastdesc)) {
						$trimyoast = wp_trim_words($yoastdesc, $excerptlength, $screenreader);
						echo $trimyoast;
					} elseif(has_excerpt() == true) {
						$trimexcerpt = wp_trim_words( $excerpt , $excerptlength, $screenreader  );
						echo strip_shortcodes($trimexcerpt); 
					} else {
						$trimmed_content = wp_trim_words( $content, $excerptlength, $screenreader );
						echo strip_shortcodes($trimmed_content);						
					}
				?>
			</p>
			
			<?php
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

	<footer class="entry-footer">
		<?php twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( esc_html__( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->