<?php 

/*
 * Matt2015 Functions
 * -- Enqueue Parent Styles
 * -- Enqueue RRSB script on posts
 * -- Customizer Additions
 * -- Register Sidebars for Archive, Page, and Post
 * -- Custom Metaboxes for Homepage Template
 * -- Tame Yoast SEO metabox

  ========================================== */ 
define('Matt2015_dir', get_stylesheet_directory_uri());

/**
 * Enqueue Parent Styles. . . it's faster
 *
 * @since Twenty Fifteen 1.0
 */
add_action( 'wp_enqueue_scripts', 'enqueue_2015_style' );

function enqueue_2015_style() {
    wp_enqueue_style( 'twentyfifteen-css', get_template_directory_uri() . '/style.css' );
}

/**
 * Enqueue RRSB on Single Posts Only
 *
 * @since Twenty Fifteen 1.0
 */
add_action( 'wp_enqueue_scripts', 'enqueue_rrsb_script' );

function enqueue_rrsb_script() {
	// if(is_single('post')) :
		wp_enqueue_script( 'rrsb-js', get_stylesheet_directory_uri() . '/js/rrssb.min.js', array(), '1.0', true );
	// endif;
}

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */

//Enqueue Styles for Customizer
add_action( 'customize_controls_print_styles', 'matt2015_customizer_style' );

function matt2015_customizer_style() {
    wp_enqueue_style( 'matt2015-customizer', get_stylesheet_directory_uri() . '/Customizer/matt2015-customizer.css' );
}

//Customizer Starts Here
new matt2015_theme_customizer();

class matt2015_theme_customizer
{
    public function __construct()
    {
        add_action( 'customize_register', array(&$this, 'matt2015_customizer' ));
    }

    public function matt2015_customizer( $wp_customize )
    {
        $this->matt2015_additions( $wp_customize );
    }

    private function matt2015_additions( $wp_customize )
    {
        //Add Archive Section
		$wp_customize->add_section( 'archive_settings', array(
            'title'          => __('Archive Settings', 'matt2015'),
            'priority'       => 85,
        ) );
		
		// Archive Layout Chooser
        require_once dirname(__FILE__) . '/Customizer/layout/layout-picker-custom-control.php';
        $wp_customize->add_setting( 'archive_layout', array(
            'default'        => '',
        ) );
        $wp_customize->add_control( new Matt2015_Layout_Picker_Custom_Control( $wp_customize, 'archive_layout', array(
            'label'   => __('Choose Your Archive Layout', 'matt2015'),
            'section' => 'archive_settings',
            'settings'   => 'archive_layout',
            'priority' => 2,
			'type' => 'radio',
			'transport' => 'refresh',
			'choices' => array(
				'full_content' => __('Full Content', 'matt2015'),
				'excerpt' => __('Excerpt', 'matt2015'),
				'fancy_rollover' => __('Fancy Rollover', 'matt2015')
			)
        ) ) );
		
		// Set Excerpt Length for Archive Pages
        $wp_customize->add_setting( 'excerpt_length', array(
            'default'        => '25',
        ) );

        $wp_customize->add_control( 'excerpt_length', array(
            'label'   => __('Set excerpt length', 'matt2015'),
            'section' => 'archive_settings',
            'type'    => 'number',
            'priority' => 3,
			'transport' => 'refresh',
        ) );
		
		// Add Site Logo (In pre-existing Header section)
        $wp_customize->add_setting( 'header_logo', array(
            'default'        => '',
        ) );

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'header_logo', array(
            'label'   => __('Header Logo', 'matt2015'),
            'section' => 'header_image',
			'description' => __('For adding a Logo at the top of the sidebar', 'matt2015'),
            'settings'   => 'header_logo',
            'priority' => 1,
			'transport' => 'refresh',
        ) ) );
		
		// Tagline Options (in pre-existing Site Title section)
        $wp_customize->add_setting( 'tagline_options', array(
            'default'        => '1',
        ) );

        $wp_customize->add_control( 'tagline_options', array(
            'label'   => __('Header Text Positioning', 'matt2015'),
            'section' => 'title_tagline',
            'type'    => 'select',
			'default' => 'none',
            'choices' => array(
				'none' => __("Do not display it at all", 'matt2015'),
				'above-large' => __("Above Logo only on large screens", 'matt2015'),
				'above-both' => __("Above Logo on large and to the right on smaller screens", 'matt2015'),
				'below-large' => __("Below Logo only on large screens", 'matt2015'),
				'below-both' => __("Below Logo on large and to the right on smaller screens",'matt2015'),
				'right' => __("To the right on small screens only", 'matt2015'),
			),
            'priority' => 10,
			'transport' => 'refresh',
        ) );
		
		// Disable Archive Description in Navigation
        $wp_customize->add_setting( 'disable_nav_desc', array(
            'default'        => '1',
        ) );

        $wp_customize->add_control( 'disable_nav_desc', array(
            'label'   => __('Display Archive Descriptions in Navigation?', 'matt2015'),
            'section' => 'nav',
            'type'    => 'checkbox',
            'priority' => 3,
			'transport' => 'refresh',
        ) );
		
		// Disable Archive Description in Navigation
        $wp_customize->add_setting( 'display_yoast_breadcrumbs', array(
            'default'        => 'no',
        ) );

        $wp_customize->add_control( 'display_yoast_breadcrumbs', array(
            'label'   => __('Display Yoast SEO Breadcrumbs?', 'matt2015'),
            'section' => 'nav',
            'type'    => 'select',
            'priority' => 4,
			'transport' => 'refresh',
			'choices' => array(
				'onposts' => __('Posts only', 'matt2015'),
				'onpages' => __('Pages only', 'matt2015'),
				'both' => __('Both Pages and Posts','matt2015'),
				'no' => __('No thanks', 'matt2015'),
			)
        ) );
		
		// Disable Archive Description in Navigation
        $wp_customize->add_setting( 'social_nav_position', array(
            'default'        => 'below',
        ) );

        $wp_customize->add_control( 'social_nav_position', array(
            'label'   => __('Position of Social Links Navigation', 'matt2015'),
            'section' => 'nav',
            'type'    => 'select',
            'priority' => 4,
			'transport' => 'refresh',
			'choices' => array(
				'top' => __('Below Site Title/Logo', 'matt2015'),
				'below' => __('Below Navigation', 'matt2015'),
				'footer' => __('In the Footer','matt2015')
			)
        ) );
		
		//Add Footer Section
		$wp_customize->add_section( 'footer_settings', array(
            'title'          => __('Footer Settings','matt2015'),
            'priority'       => 95,
        ) );
		
		 // Customize Footer Text
        $wp_customize->add_setting( 'custom_footer_text', array(
            'default'        => ' ',
        ) );

        $wp_customize->add_control( 'custom_footer_text', array(
            'label'   => __('Customize Footer Text','matt2015'),
            'section' => 'footer_settings',
            'type'    => 'text',
            'priority' => 1,
			'transport' => 'refresh',
        ) );
		
		//Add Archive Section
		$wp_customize->add_section( 'social_sharing', array(
            'title'          => __('Social Sharing', 'matt2015'),
            'priority'       => 95,
			// 'description' => __('Enable these sharing buttons to the bottom of all single posts.', 'matt2015'),
        ) );
		
		$wp_customize->add_setting( 'enable_rrssb', array(
            'default'        => 'yes',
        ) );

        $wp_customize->add_control( 'enable_rrssb', array(
            'label'   => __('Enable Ridiculously Responsive Social Sharing Buttons at the bottom of every single post page?  ', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'select',
			'transport' => 'refresh',
			'priority' => 1,
			'choices' => array(
				'no' => __('No thanks!','matt2015'),
				'yes' => __('Yes please!','matt2015'),
			)
        ) );
		
		$wp_customize->add_setting( 'social_sharing_position', array(
            'default'        => 'bottom',
        ) );

        $wp_customize->add_control( 'social_sharing_position', array(
            'label'   => __('Position of social sharing buttons on post.', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'select',
			'transport' => 'refresh',
			'priority' => 1,
			'choices' => array(
				'bottom' => __('Bottom of the post','matt2015'),
				'top' => __('Top of the post','matt2015'),
				'both' => __('Both bottom and top','matt2015'),
			)
        ) );
		
		$wp_customize->add_setting( 'enable_email', array(
            'default'        => '0',
        ) );

        $wp_customize->add_control( 'enable_email', array(
            'label'   => __('Email', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'checkbox',
			'transport' => 'refresh',
			'priority' => 2,
        ) );
		
		$wp_customize->add_setting( 'enable_fb', array(
            'default'        => '0',
        ) );

        $wp_customize->add_control( 'enable_fb', array(
            'label'   => __('Facebook', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'checkbox',
			'transport' => 'refresh',
			'priority' => 3,
        ) );
		
		$wp_customize->add_setting( 'enable_twitter', array(
            'default'        => '0',
        ) );

        $wp_customize->add_control( 'enable_twitter', array(
            'label'   => __('Twitter', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'checkbox',
			'transport' => 'refresh',
			'priority' => 4,
        ) );
		
		$wp_customize->add_setting( 'enable_google', array(
            'default'        => '0',
        ) );

        $wp_customize->add_control( 'enable_google', array(
            'label'   => __('Google', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'checkbox',
			'transport' => 'refresh',
			'priority' => 5,
        ) );
		
		$wp_customize->add_setting( 'enable_linkedin', array(
            'default'        => '0',
        ) );

        $wp_customize->add_control( 'enable_linkedin', array(
            'label'   => __('LinkedIn', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'checkbox',
			'transport' => 'refresh',
			'priority' => 6,
        ) );
		
		$wp_customize->add_setting( 'enable_reddit', array(
            'default'        => '0',
        ) );

        $wp_customize->add_control( 'enable_reddit', array(
            'label'   => __('Reddit', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'checkbox',
			'transport' => 'refresh',
			'priority' => 7,
        ) );
		
		$wp_customize->add_setting( 'enable_pinterest', array(
            'default'        => '0',
        ) );

        $wp_customize->add_control( 'enable_pinterest', array(
            'label'   => __('Pinterest', 'matt2015'),
            'section' => 'social_sharing',
            'type'    => 'checkbox',
			'transport' => 'refresh',
			'priority' => 8,
        ) );

		//Remove Display Header Text Checkbox
		$wp_customize->remove_control( 'display_header_text' );
		
    }

}

//First Remove the Navigation Description
function remove_nav_desc() {
		remove_filter( 'walker_nav_menu_start_el', 'twentyfifteen_nav_description', 10, 3 );
}

add_action( 'init', 'remove_nav_desc' );

//Then add my own with the theme_mod check
function matt15_twentyfifteen_nav_description( $item_output, $item, $depth, $args ) {
	$disabledesc = get_theme_mod('disable_nav_desc' );
	
		if ( $disabledesc == '1' && 'primary' == $args->theme_location && $item->description ) {
			$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
		}

		return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'matt15_twentyfifteen_nav_description', 10, 4 );

add_action('customize_controls_enqueue_scripts', 'matt15_conditional_social_customizer');

function matt15_conditional_social_customizer() {
    wp_enqueue_script( 'conditional-settings', get_stylesheet_directory_uri() . '/Customizer/conditional-settings.js', array( 'jquery', 'customize-controls' ), false, true );
}


/**
 * SIDEBAR Registration.
 * -- Archive Sidebar
 * -- Post (Footer) Sidebar
 * -- Page (Footer) Sidebar
 *
 * @since Twenty Fifteen 1.0
 */
 function matt2015_archive_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Archive Sidebar', 'matt2015' ),
        'id' => 'archive-sidebar',
        'description' => __( 'Widgets in this area will be shown at the top of the Archive Page.', 'matt2015' ),
        'before_title' => '<h4>',
        'after_title' => '</h4>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
    ) );
}

 function matt2015_page_footer_widgets_init() {
	 register_sidebar( array(
        'name' => __( 'Page Footer', 'matt2015' ),
        'id' => 'page-footer',
        'description' => __( 'Widgets in this area will be shown at the bottom of each Page.', 'matt2015' ),
        'before_title' => '<h4>',
        'after_title' => '</h4>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
    ) );
}

 function matt2015_post_footer_widgets_init() {
	
	register_sidebar( array(
        'name' => __( 'Post Footer', 'matt2015' ),
        'id' => 'post-footer',
        'description' => __( 'Widgets in this area will be shown at the bottom of each Post.', 'matt2015' ),
        'before_title' => '<h4>',
        'after_title' => '</h4>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
    ) );
}
add_action( 'widgets_init', 'matt2015_archive_widgets_init' );
add_action( 'widgets_init', 'matt2015_page_footer_widgets_init' );
add_action( 'widgets_init', 'matt2015_post_footer_widgets_init' );

//Custom MetaBoxes for Page Templates
if (is_admin()) {
	require_once('meta-boxes/meta-box-homepage.php');
	require_once('meta-boxes/meta-box-thankyou.php');
}

//Tame Yoast MetaBox
function yoast_dont_boast() {
     return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoast_dont_boast');

function dedo_custom_output( $styles ) {
	
	$styles['flat_button'] = array(
 		'name'		=> __( 'Icon Link', 'delightful-downloads' ),
 		'format'	=> '<div class="download button">
							<a href="%url%" title="%title%" rel="nofollow"><span class="genericon genericon-cloud-download"></span> %title%</a>
 						</div>'
	);

	return $styles;
}
add_filter( 'dedo_get_styles', 'dedo_custom_output' );

/*
 * Max Excerpt Character Length for RSSB Descriptions
 */
 
function the_excerpt_max_charlength($charlength) {
   $excerpt = get_the_excerpt();
   $charlength++;
   if(strlen($excerpt)>$charlength) {
	   $subex = substr($excerpt,0,$charlength-5);
	   $exwords = explode(" ",$subex);
	   $excut = -(strlen($exwords[count($exwords)-1]));
	   if($excut<0) {
			echo substr($subex,0,$excut);
	   } else {
			echo $subex;
	   }
	   echo "[...]";
   } else {
	   echo $excerpt;
   }
}

/*NOT INCLUDED IN BASE CHILD THEME*/
/*Delightful Downloads Fix*/
add_filter( 'dedo_clear_output_buffers', '__return_false' );

add_image_size( 'featured-image', 825 );