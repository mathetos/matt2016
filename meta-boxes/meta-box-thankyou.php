<?php
// require_once ('metabox.class.php');

function matt2015_thankyou_meta_boxes() {

		$meta_box = array(
			// Meta Box
			'id' 		=> 'referrer', // unique ID
			'title' 	=> _x( 'Restrict Page by Referrer', 'meta box', 'matt2015' ),
			'post_type'	=> 'page',
			'context'	=> 'normal', // where the meta box appear: normal (left above standard meta boxes), advanced (left below standard boxes), side
			'priority'	=> 'high', // high, core, default or low (see this: http://www.wproots.com/ultimate-guide-to-meta-boxes-in-wordpress/)
			// Fields
			'fields' => array(
				// Text Title
				'_referrer' => array(
					'name'				=> __( 'Referrer URL', 'matt2015' ),
					'desc'				=> __( "This page template will allow access to it only from the url you specify here.", 'matt2015' ),
					'type'				=> 'url', // text, textarea, checkbox, radio, select, number, upload, upload_textarea, url
					'allow_html'		=> false, // allow HTML to be used in the value (text, textarea)
				),
			),
		);
		new CT_Meta_Box( $meta_box );
}

add_action( 'admin_init', 'matt2015_thankyou_meta_boxes' );


add_action('admin_head', 'matt15_conditional_thankyou_metabox');

function matt15_conditional_thankyou_metabox() {
    global $current_screen;
    if('page' != $current_screen->id) return;

    echo <<<HTML
        <script type="text/javascript">
        jQuery(document).ready( function($) {

            /**
             * Adjust visibility of the meta box at startup
            */
            if($('#page_template').val() == 'thankyou.php') {
                // show the meta box
                $('#custom_referrer').show();
            } else {
                // hide your meta box
                $('#custom_referrer').hide();
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
                    if($(this).val() == 'thankyou.php') {
                    // show the meta box
                    $('#custom_referrer').show();
                } else {
                    // hide your meta box
                    $('#custom_referrer').hide();
                }

                // Debug only
                if (typeof console == "object") 
                    console.log ('live change value = ' + $(this).val());
            });                 
        });    
        </script>
HTML;
}