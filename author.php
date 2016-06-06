<?php 
/**
 * The Template for showing Author Pages
 *
 * @link https://codex.wordpress.org/Author_Templates
 *
 * @package WordPress
 * @subpackage Beyond_2016
 * @since Beyond 2016 1.0
 */
global $post;

$authorid = get_the_author_meta("ID");
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

get_header(); ?>

	<div id="content" class="site-content">
		<div id="primary" class="content-area no-sidebar">
			<main id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : 
				the_post();
				$user =  get_user_meta($authorid);
				var_dump($user);
			?>
				<article id="author-<?php echo $authorid; ?>" <?php post_class(); ?>>
					<header class="page-header">
		                <h1 class="page-title author"><?php printf( __( 'Author Archives: %s', 'twentyeleven' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
		            </header>

	            	<div class="entry-content">
			            <div id="author-info">
			                <div id="author-avatar">
			                    <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyeleven_author_bio_avatar_size', 60 ) ); ?>
			                </div><!-- #author-avatar -->
			                <div id="author-description">
			                    <h2><?php printf( __( 'About %s', 'twentyeleven' ), get_the_author() ); ?></h2>
			                    <?php the_author_meta( 'user_description' ); ?>
			                    <p><?php echo $user['facebook'][0]; ?></p>
			                </div><!-- #author-description -->
			            </div><!-- #entry-author-info -->
			        </div>
		        </article>

		        <div class="author-recent-posts">
				<?php
					
					$args = array(
						'numberposts' => 3,
						'post_type' => 'post',
						'post_status' => 'publish',
						'suppress_filters' => false,
						'author' => $authorid
					);
					
					$postsheading = 'Latest Posts by ' . $user['first_name'][0];
					
					do_action('beyond2016_recent_posts', $args, $postsheading);
					
				?>
	            
				</div>

			<?php // If no content, include the "No posts found" template.
			else :
				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

			</main><!-- .site-main -->
		</div><!-- .content-area -->
	</div>

<?php get_footer(); ?>
