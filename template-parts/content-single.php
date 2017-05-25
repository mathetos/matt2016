<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php beyond2016_excerpt(); ?>

	<?php beyond2016_post_thumbnail($size = 'post-thumbnail'); ?>

	<div class="entry-content">
		<?php
			the_content();

			global $post;

		    $url = get_permalink($post->ID);
		    $title = get_the_title($post->ID);
		    $authorid = get_post_field( 'post_author', $post_id );
		    $authorurl = get_author_posts_url($authorid);
		    $authorfirst = get_the_author_meta( 'first_name', $authorid );
		    $authorlast = get_the_author_meta( 'last_name', $authorid );
		    //$authorname = $authorfirst . '&nbsp;' . $authorlast;

			?>
			<?php //var_dump($authorfirst); ?>
			<p prefix="dct: http://purl.org/dc/terms/ cc: http://creativecommons.org/ns#" class="cc-block"><a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="CC BY-NC-SA 4.0" class="cc-button     lazyloaded" src="https://mattcromwell.dev/wp-content/plugins/creative-commons-configurator-1/media/cc/by-nc-sa/4.0/80x15.png" data-lazy-src="https://mattcromwell.dev/wp-content/plugins/creative-commons-configurator-1/media/cc/by-nc-sa/4.0/80x15.png" width="80" height="15"><noscript>&lt;img alt="CC BY-NC-SA 4.0" class="cc-button" src="https://mattcromwell.dev/wp-content/plugins/creative-commons-configurator-1/media/cc/by-nc-sa/4.0/80x15.png" width="80" height="15" /&gt;</noscript></a><a href="<?php echo $url; ?>" title="<?php echo $title; ?>"<link href="http://purl.org/dc/dcmitype/Text" rel="dct:type" property="dct:type"><span property="dct:title"><?php echo $title; ?></span></a> by <a href="<?php echo $authorurl; ?>" property="cc:attributionName" rel="cc:attributionURL"><?php echo $authorfirst . '&nbsp;' . $authorlast; ?></a> is licensed under a <a rel="license" target="_blank" href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
				
			<?php

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'beyond2016' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'beyond2016' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

			if ( '' !== get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/biography' );
			}
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php beyond2016_entry_meta(); ?>
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'beyond2016' ),
					the_title( '', '', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>

        <?php
            echo '<a href="#comments">';
                comments_number( 'No Comments', 'One Comment', '% Comments' );
            echo '</a>';
        ?>

		<?php
            if ( is_singular('post') ) {

                $chatter = get_post_meta( $post->ID, 'mattc16_social_chatter', true );
	            $allchatter = explode(",", $chatter);

	            if ( !empty($chatter) ) {
		            echo '<h4>Social Chatter on this Post:</h4>';
		            foreach ( $allchatter as $tweet ) {
			            echo wp_oembed_get( $tweet );
		            }
	            }
            }
		?>

	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
