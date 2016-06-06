<?php
require_once ('metabox.class.php');

function matt2015_homepage_meta_boxes() {

		$meta_box = array(
			// Meta Box
			'id' 		=> 'custom_content_header', // unique ID
			'title' 	=> _x( 'Custom Content Header', 'meta box', 'matt2015' ),
			'post_type'	=> 'page',
			'context'	=> 'normal', // where the meta box appear: normal (left above standard meta boxes), advanced (left below standard boxes), side
			'priority'	=> 'high', // high, core, default or low (see this: http://www.wproots.com/ultimate-guide-to-meta-boxes-in-wordpress/)
			// Fields
			'fields' => array(
				// Text Title
				'_title' => array(
					'name'				=> __( 'Custom Text Title', 'matt2015' ),
					// 'after_name'		=> '', // (Optional), (Required), etc.
					'desc'				=> __( "This will replace the page title and will appear above the right-hand content area directly on the background color.", 'matt2015' ),
					'type'				=> 'text', // text, textarea, checkbox, radio, select, number, upload, upload_textarea, url
					'allow_html'		=> false, // allow HTML to be used in the value (text, textarea)
				),
				// Custom Image
				'_header_image' => array(
					'name'				=> __( 'Content Header Image', 'matt2015' ),
					'after_name'		=> '', // (Optional), (Required), etc.
					'desc'				=> __('This image will appear above the right-hand content area directly on the background color. It is NOT the featured image.', 'matt2015'),
					'type'				=> 'upload', // text, textarea, checkbox, radio, select, number, upload, upload_textarea, url
					'upload_button'		=> __('Choose/Upload Image', 'matt2015'), // text for button that opens media frame
					'upload_title'		=> __('Choose/Upload your Custom Content Header Image', 'matt2015'), // title appearing at top of media frame
					'upload_type'		=> 'image', // optional type of media to filter by (image, audio, video, application/pdf)
					'class'				=> 'ctmb-large', // class(es) to add to input (try try ctmb-medium, ctmb-small, ctmb-tiny)
				),
			),
		);
		new CT_Meta_Box( $meta_box );
}

add_action( 'admin_init', 'matt2015_homepage_meta_boxes' );


add_action('admin_head', 'matt15_conditional_homepage_metabox');

function matt15_conditional_homepage_metabox() {
    global $current_screen;
    if('page' != $current_screen->id) return;

    echo <<<HTML
        <script type="text/javascript">
        jQuery(document).ready( function($) {

            /**
             * Adjust visibility of the meta box at startup
            */
            if($('#page_template').val() == 'frontpage.php') {
                // show the meta box
                $('#custom_content_header').show();
            } else {
                // hide your meta box
                $('#custom_content_header').hide();
            }

            // Debug only
            // - outputs the template filename
            // - checking for console existance to avoid js errors in non-compliant browsers
            if (typeof console == "object") 
                console.log ('default value = ' + $('#page_template').val());

            /**
             * Live adjustment of the meta box visibility
            */
            $('#page_template').live('change', function(){
                    if($(this).val() == 'frontpage.php') {
                    // show the meta box
                    $('#custom_content_header').show();
                } else {
                    // hide your meta box
                    $('#custom_content_header').hide();
                }

                // Debug only
                if (typeof console == "object") 
                    console.log ('live change value = ' + $(this).val());
            });                 
        });    
        </script>
HTML;
}