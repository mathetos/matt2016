<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<figure class="fancy-rollover">
		<?php
			// Post thumbnail.
			twentyfifteen_post_thumbnail();
			$excerptlength = get_theme_mod('excerpt_length');
		?>
		<figcaption>
			<h2><a href="<?php the_permalink(); ?>"><?php echo the_title();?></a></h2>
				<?php 
					echo the_date('F j, Y', '<p class="the-date">', '</p>'); 
					//echo date_i18n( get_option( 'date_format' ), strtotime( '11/15-1976' ) );
				?>
			<p class="icon-links">
				<?php //twentyfifteen_entry_meta(); 
				echo '<span class="comments-link">';
				echo comments_number( '0', '1', '%' );
				echo '</span>';
				
				?>
			</p>
			<p class="description">
				<a href="<?php the_permalink(); ?>"><?php 
					$yoastdesc = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);
					
					if(!empty($yoastdesc)) {
						echo wp_trim_words($yoastdesc, $excerptlength);
					} else {
						$content = get_the_excerpt();
						echo wp_trim_words( $content , $excerptlength );
					}
				?></a>
			</p>
		</figcaption>			
	</figure>
</article><!-- #post-## -->