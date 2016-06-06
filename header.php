<?php
/**
 * The template for displaying the header
 *
 * Displays all of the &lt;head&gt; section and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
 $displayheader = get_theme_mod('tagline_options');
?><!DOCTYPE html>
<!-- IN THE CLOUD!! -->
<html <?php language_attributes(); ?> class="no-js">
<head>
<!-- On the "E" -->
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'twentyfifteen' ); ?></a>

	<div id="sidebar" class="sidebar <?php echo $displayheader; ?>">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<?php
					$logourl = get_theme_mod('header_logo');
					
					$logo = ( !empty($logourl) ) ? '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home" class="logo"><img src="' . $logourl . '" class="logo"></a>' : '' ;
					
					$description = get_bloginfo( 'description', 'display' );
					
					$blogname = get_bloginfo( 'name' );
					
					$showdesc = ( ! empty( $description ) || is_customize_preview() ) ? '<p class="site-description">' . esc_html( $description ) . '</p>' : ' ' ;
					
					$blogdesc = '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $blogname . '</a></h1>' . $showdesc . '';
					
					$blogdeschiddensmall = '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $blogname . '</a></h1>' . $showdesc ;
					
					$blogdeschiddenlarge = '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . $blogname . '</a></h1>' . $showdesc ;
					
					switch ($displayheader) {
					case 'none':
						echo $logo;
					break;
					case 'above-large' :
						echo $blogdeschiddensmall;
						echo $logo;
					break;
					case 'above-both' :
						echo $blogdesc;
						echo $logo;
					break;
					case 'below-large':
						echo $logo;
						echo $blogdeschiddensmall;
					break;
					case 'below-both' :
						echo $logo;
						echo $blogdesc;
					break;
					case 'right' :
						echo $logo;
						echo $blogdeschiddenlarge;
					break;
					default:
						echo $logo;
					}
				?>
				<button class="secondary-toggle"><?php esc_html_e( 'Menu and widgets', 'twentyfifteen' ); ?></button>
			</div><!-- .site-branding -->
		</header><!-- .site-header -->

		<?php get_sidebar(); ?>
	</div><!-- .sidebar -->

	<div id="content" class="site-content">
