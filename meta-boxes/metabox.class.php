<?php
/**
 * CT Meta Box
 *
 * This class can be used by themes and plugins to generate meta boxes and custom fields.
 *
 * The CTMB_URL constant must be defined in order for JS/CSS to enqueue.
 * See Church Theme Content plugin for example usage.
 *
 * @package   CT_Meta_Box
 * @copyright Copyright (c) 2013 - 2014, churchthemes.com
 * @link      https://github.com/churchthemes/ct-meta-box
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// Class may be used in both theme and plugin(s)
if ( ! class_exists( 'CT_Meta_Box' ) ) {

	/**
	 * Main class
	 *
	 * @since 0.8.5
	 */
	class CT_Meta_Box {

		/**
		 * Plugin version
		 *
		 * @since 1.0.4
		 * @var string
		 */
		public $version;

		/**
		 * Meta box configuration
		 *
		 * @since 1.0.4
		 * @var array
		 */
		public $meta_box;

		/**
		 * Constructor
		 *
		 * @since 0.8.5
		 * @access public
		 * @param array $meta_box Configuration for meta box and its fields
		 */
		public function __construct( $meta_box ) {

			// Version - used in cache busting
			$this->version = '1.0.6';

			// Prepare config
			$this->prepare( $meta_box );

			// Setup meta box
			add_action( 'load-post-new.php', array( &$this, 'setup' ) ); // setup meta boxes on add
			add_action( 'load-post.php', array( &$this, 'setup' ) ); // setup meta boxes on edit

			// Enqueue styles
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_styles' ) );

			// Enqueue scripts
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );

		}

		/**
		 * Prepare config
		 *
		 * This sets $this->meta_box and adds filtering for the enablement and overriding of fields.
		 *
		 * @since 0.8.5
		 * @access public
		 * @param array $meta_box Configuration for meta box and its fields
		 */
		public function prepare( $meta_box ) {

			// Get fields
			$fields = $meta_box['fields'];

			// Fill array of visible fields with all by default
			$visible_fields = array();
			foreach ( $fields as $key => $field ) {
				$visible_fields[] = $key;
			}

			// Let themes/plugins set explicit visibility for fields of specific post type
			$visible_fields = apply_filters( 'ctmb_visible_fields-' . $meta_box['post_type'], $visible_fields, $meta_box['post_type'] );

			// Let themes/plugins override specific data for field of specific post type
			$field_overrides = apply_filters( 'ctmb_field_overrides-' . $meta_box['post_type'], array(), $meta_box['post_type'] ); // by default no overrides

			// Loop fields to modify them with filtered data
			foreach ( $fields as $key => $field ) {

				// Selectively override field data based on filtered array
				if ( ! empty( $field_overrides[$key] ) && is_array( $field_overrides[$key] ) ) {
					$meta_box['fields'][$key] = array_merge( $field, $field_overrides[$key] ); // merge filtered in data over top existing data
				}

				// Set visibility of fields based on filtered or unfiltered array
				$meta_box['fields'][$key]['hidden'] = ! in_array( $key, (array) $visible_fields ) ? true : false; // set hidden true if not in array

			}

			// Make config accessible
			$this->meta_box = $meta_box;

		}

		/**
		 * Setup meta box
		 *
		 * @since 0.8.5
		 * @access public
		 */
		public function setup() {

			$screen = get_current_screen();

			// Specified post type only
			if ( $screen->post_type == $this->meta_box['post_type'] ) {

				// Add meta boxes
				add_action( 'add_meta_boxes', array( &$this, 'add' ) );

				// Save meta boxes
				add_action( 'save_post', array( &$this, 'save' ), 10, 2 ); // note: this always runs twice (once for revision, once for post)

			}

		}

		/**
		 * Add meta box
		 *
		 * @since 0.8.5
		 * @access public
		 */
		public function add() {

			// Add meta box
			add_meta_box(
				$this->meta_box['id'],
				$this->meta_box['title'],
				array( &$this, 'output' ),
				$this->meta_box['post_type'],
				$this->meta_box['context'],
				$this->meta_box['priority']
			);

			// Hide if no visible fields
			add_action( 'admin_head', array( &$this, 'hide' ) );

			// Dynamically show/hide fields based on page template selection
			add_action( 'admin_head', array( &$this, 'page_template_fields' ) );

		}

		/**
		 * Hide meta box
		 *
		 * If no visible fields in meta box, hide it.
		 *
		 * @since 0.8.5
		 * @access public
		 */
		public function hide() {

			$visible = false;

			// Loop fields to find at least one that is visible
			$fields = $this->meta_box['fields'];
			foreach ( $fields as $field ) {

				// Visible?
				if ( empty( $field['hidden'] ) ) {
					$visible = true;
					break;
				}

			}

			// Output CSS to <head> to hide meta box and Screen Options control
			if ( ! $visible ) {

				?>
				<style type="text/css">
				#<?php echo $this->meta_box['id']; ?>,
				label[for="<?php echo $this->meta_box['id']; ?>-hide"] {
					display: none;
				}
				</style>
				<?php

			}

		}

		/**
		 * Page template fields
		 *
		 * If page_templates is specified, that field will dynamically show/hide depending on user's page template selection
		 *
		 * @since 0.8.5
		 * @access public
		 */
		public function page_template_fields() {

			// Only on pages
			$screen = get_current_screen();
			if ( 'page' != $screen->post_type ) {
				return;
			}

			// Loop fields
			$fields = $this->meta_box['fields'];
			foreach ( $fields as $key => $field ) {

				// Only if has page_templates and not a field that is always hidden
				if ( ! empty( $field['page_templates'] ) && empty( $field['hidden'] ) ) {

					$json_page_templates = json_encode( $field['page_templates'] );

					// Output JavaScript to <head> to handle field visibility based on page template selection
					?>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
						ctmb_page_template_field_visibility( '<?php echo $key; ?>', <?php echo $json_page_templates; ?> ); // First load
						$( '#page_template' ).change( function() { // Changed page template
							ctmb_page_template_field_visibility( '<?php echo $key; ?>', <?php echo $json_page_templates; ?> );
						});
					});
					</script>
					<?php

				}

			}

		}

		/**
		 * Meta box output
		 *
		 * @since 0.8.5
		 * @access public
		 * @param object $post Post object
		 * @param array $args Arguments
		 */
		public function output( $post, $args ) {

 			// Nonce security
			wp_nonce_field( $this->meta_box['id'] . '_save', $this->meta_box['id'] . '_nonce' );

			// Loop fields
			$fields = $this->meta_box['fields'];
			foreach ( $fields as $key => $field ) {

				// Output field
				$this->field_output( $key, $field );

			}

		}

		/**
		 * Field output
		 *
		 * @since 0.8.5
		 * @access public
		 * @global object $wp_locale
		 * @param string $key Field key
		 * @param array $field Field configuration
		 */
		public function field_output( $key, $field ) {

			global $wp_locale;

			/**
			 * Field Data
			 */

			// Store data in array so custom output callback can use it
			$data = array();

			// Get field config
			$data['key'] = $key;
			$data['field'] = $field;

			// Prepare strings
			$data['value'] = $this->field_value( $data['key'] ); // saved value or default value if is first add or value not allowed to be empty
			$data['esc_value'] = esc_attr( $data['value'] );
			$data['esc_element_id'] = 'ctmb-input-' . esc_attr( $data['key'] );

			// Prepare styles for elements (core WP styling)
			$default_classes = array(
				'text'				=> 'regular-text',
				'url'				=> 'regular-text',
				'upload'			=> 'regular-text',
				'upload_textarea'	=> '',
				'textarea'			=> '',
				'checkbox'			=> '',
				'radio'				=> '',
				'select'			=> '',
				'number'			=> 'small-text',
				'date'				=> '',

			);
			$classes = array();
			$classes[] = 'ctmb-' . $data['field']['type'];
			if ( ! empty( $default_classes[$data['field']['type']] ) ) {
				$classes[] = $default_classes[$data['field']['type']];
			}
			if ( ! empty( $data['field']['class'] ) ) {
				$classes[] = $data['field']['class'];
			}
			$data['classes'] = implode( ' ', $classes );

			// Common attributes
			$data['common_atts'] = 'name="' . esc_attr( $data['key'] ) . '" class="' . esc_attr( $data['classes'] ) . '"';
			if ( ! empty( $data['field']['attributes'] ) ) { // add custom attributes
				foreach ( $data['field']['attributes'] as $attr_name => $attr_value ) {
					$data['common_atts'] .= ' ' . $attr_name . '="' . esc_attr( $attr_value ) . '"';
				}
			}

			// Field container classes
			$data['field_class'] = array();
			$data['field_class'][] = 'ctmb-field';
			if ( ! empty( $data['field']['hidden'] ) ) { // Hidden (for internal use only, via prepare() filter)
				$data['field_class'][] = 'ctmb-hidden';
			}
			if ( ! empty( $data['field']['field_class'] ) ) {
				$data['field_class'][] = $data['field']['field_class']; // append custom classes
			}
			$data['field_class'] = implode( ' ', $data['field_class'] );

			// Field container styles
			$data['field_attributes'] = '';
			if ( ! empty( $data['field']['field_attributes'] ) ) { // add custom attributes
				foreach ( $data['field']['field_attributes'] as $attr_name => $attr_value ) {
					$data['field_attributes'] .= ' ' . $attr_name . '="' . esc_attr( $attr_value ) . '"';
				}
			}

			/**
			 * Form Input
			 */

			// Use custom function to render custom field content
			if ( ! empty( $data['field']['custom_field'] ) ) {
				$input = call_user_func( $data['field']['custom_field'], $data );
			}

			// Standard output based on type
			else {

				// Switch thru types to render differently
				$input = '';
				switch ( $data['field']['type'] ) {

					// Text
					// URL
					case 'text':
					case 'url': // same as text

						$input = '<input type="text" ' . $data['common_atts'] . ' id="' . $data['esc_element_id'] . '" value="' . $data['esc_value'] . '" />';

						break;

					// Textarea
					case 'textarea':

						$input = '<textarea ' . $data['common_atts'] . ' id="' . $data['esc_element_id'] . '">' . esc_textarea( $data['value'] ) . '</textarea>';

						// special esc func for textarea

						break;

					// Checkbox
					case 'checkbox':

						$input  = '<input type="hidden" ' . $data['common_atts'] . ' value="" />'; // causes unchecked box to post empty value (helps with default handling)
						$input .= '<label for="' . $data['esc_element_id'] . '">';
						$input .= '	<input type="checkbox" ' . $data['common_atts'] . ' id="' . $data['esc_element_id'] . '" value="1"' . checked( '1', $data['value'], false ) . '/>';
						if ( ! empty( $data['field']['checkbox_label'] ) ) {
							$input .= ' ' . $data['field']['checkbox_label'];
						}
						$input .= '</label>';

						break;

					// Radio
					case 'radio':

						if ( ! empty( $data['field']['options'] ) ) {

							foreach ( $data['field']['options'] as $option_value => $option_text ) {

								$esc_radio_id = $data['esc_element_id'] . '-' . $option_value;

								$input .= '<div>';
								$input .= '	<label for="' . $esc_radio_id . '">';
								$input .= '		<input type="radio" ' . $data['common_atts'] . ' id="' . $esc_radio_id . '" value="' . esc_attr( $option_value ) . '"' . checked( $option_value, $data['value'], false ) . '/> ' . esc_html( $option_text );
								$input .= '	</label>';
								$input .= '</div>';

							}

						}

						break;

					// Select
					case 'select':

						if ( ! empty( $data['field']['options'] ) ) {

							$input .= '<select ' . $data['common_atts'] . ' id="' . $data['esc_element_id'] . '">';
							foreach ( $data['field']['options'] as $option_value => $option_text ) {
								$input .= '<option value="' . esc_attr( $option_value ) . '" ' . selected( $option_value, $data['value'], false ) . '> ' . esc_html( $option_text ) . '</option>';
							}
							$input .= '</select>';

						}

						break;

					// Number
					case 'number':

						$input = '<input type="number" ' . $data['common_atts'] . ' id="' . $data['esc_element_id'] . '" value="' . $data['esc_value'] . '" />';

						break;

					// Upload - Regular (URL)
					case 'upload':

						$upload_button = isset( $data['field']['upload_button'] ) ? $data['field']['upload_button'] : '';
						$upload_title = isset( $data['field']['upload_title'] ) ? $data['field']['upload_title'] : '';
						$upload_type = isset( $data['field']['upload_type'] ) ? $data['field']['upload_type'] : '';

						$input  = '<input type="text" ' . $data['common_atts'] . ' id="' . $data['esc_element_id'] . '" value="' . $data['esc_value'] . '" />';
						$input .= ' <input type="button" value="' . esc_attr( $upload_button ) . '" class="upload_button button ctmb-upload-file" data-ctmb-upload-type="' . esc_attr( $upload_type ) . '" data-ctmb-upload-title="' . esc_attr( $upload_title ) . '" /> ';

						break;

					// Upload - Textarea (URL or Embed Code)
					case 'upload_textarea':

						$upload_button = isset( $data['field']['upload_button'] ) ? $data['field']['upload_button'] : '';
						$upload_title = isset( $data['field']['upload_title'] ) ? $data['field']['upload_title'] : '';
						$upload_type = isset( $data['field']['upload_type'] ) ? $data['field']['upload_type'] : '';

						// Textarea (URL or Embed Code)
						$input = '<textarea ' . $data['common_atts'] . ' id="' . $data['esc_element_id'] . '">' . esc_textarea( $data['value'] ) . '</textarea>';

						$input .= ' <input type="button" value="' . esc_attr( $upload_button ) . '" class="upload_button button ctmb-upload-file" data-ctmb-upload-type="' . esc_attr( $upload_type ) . '" data-ctmb-upload-title="' . esc_attr( $upload_title ) . '" /> ';

						break;

					// Date
					// (formatted like WordPress post date so that it works *easily* with any language - unlike jQuery UI Datepicker)
					case 'date':

						// Extract month, day, year from saved date (YYYY-MM-DD)
						$month = '';
						$day = '';
						$year = '';
						if ( ! empty( $data['value'] ) ) {
							list( $year, $month, $day ) = explode( '-', $data['value'] );
							$day = ltrim( $day, '0' ); // remove 0 padding from left
						}

						// Container start
						$input .= '<div class="ctmb-date">';

						// Month
						$input .= '<select name="' . esc_attr( $data['key'] ) . '-month" id="' . $data['esc_element_id'] . '-month">';
						$input .= '<option value=""></option>';
						for ( $i = 1; $i <= 12; $i++ ) {
							$month_num = str_pad( $i, 2, '0', STR_PAD_LEFT );
							$month_name = $wp_locale->get_month( $i );
							$input .= '<option value="' . esc_attr( $month_num ) . '" ' . selected( $month, $month_num, false ) . '>' . esc_html( $month_name ) . '</option>';
						}
						$input .= '</select>';

						// Day
						$input .= ' <input type="number" name="' . esc_attr( $data['key'] ) . '-day" id="' . $data['esc_element_id'] . '-day" min="1" max="31" value="' . esc_attr( $day ) . '" class="ctmb-date-day">';

						// Year
						$input .= ', <input type="number" name="' . esc_attr( $data['key'] ) . '-year" id="' . $data['esc_element_id'] . '-year" min="2000" max="2100" value="' . esc_attr( $year ) . '" class="ctmb-date-year">';

						// Container end
						$input .= '</div>';

						break;

				}

			}

			/**
			 * Field Container
			 */

			// Output field
			if ( ! empty( $input ) ) { // don't render if type invalid

				?>
				<div id="ctmb-field-<?php echo esc_attr( $data['key'] ); ?>" class="<?php echo esc_attr( $data['field_class'] ); ?>"<?php echo $data['field_attributes']; ?>>

					<div class="ctmb-name">

						<?php if ( ! empty( $data['field']['name'] ) ) : ?>

							<?php echo esc_html( $data['field']['name'] ); ?>

							<?php if ( ! empty( $data['field']['after_name'] ) ) : ?>
								<span><?php echo esc_html( $data['field']['after_name'] ); ?></span>
							<?php endif; ?>

						<?php endif; ?>

					</div>

					<div class="ctmb-value">

						<?php echo $input; ?>

						<?php if ( ! empty( $data['field']['desc'] ) ) : ?>
						<p class="description">
							<?php echo $data['field']['desc']; ?>
						</p>
						<?php endif; ?>

					</div>

				</div>
				<?php

			}

		}

		/**
		 * Get field value
		 *
		 * Gets saved value or a default value if is first add or if value not allowed to be empty.
		 * This assists with showing and saving/validating fields.
		 *
		 * @since 0.8.5
		 * @access public
		 * @param string $key Field key
		 * @param bool $default_only True if only want to check if default should be used
		 * @return string Saved or default value
		 */
		public function field_value( $key, $default_only = false ) {

			global $post;

			$screen = get_current_screen();

			$value = '';

			// Get saved value, if any
			if ( empty( $default_only ) ) { // sometimes only want to check if default should be used
				$value = get_post_meta( $post->ID, $key, true );
			}

			// No saved value
			if ( empty( $value ) ) {

				// Get field data
				$field = $this->meta_box['fields'][$key];

				// Default is not empty
				if ( ! empty( $field['default'] ) ) {

					// Field cannot be empty, use default
					if ( ! empty( $field['no_empty'] ) ) {
						$value = $field['default'];
					}

					// Field can be empty but this is first add, use default
					else if ( 'post' == $screen->base && 'add' == $screen->action ) {
						$value = $field['default'];
					}

				}

			}

			return $value;

		}

		/**
		 * Save meta boxes
		 *
		 * @since 0.8.5
		 * @access public
		 * @param int $post_id Post ID
		 * @param object $post Data for post being saved
		 */
		public function save( $post_id, $post ) {

			// Is a POST occurring?
			if ( empty( $_POST ) ) {
				return false;
			}

			// Not an auto-save (meta values not submitted)
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return false;
			}

			// Verify the nonce
			$nonce_key = $this->meta_box['id'] . '_nonce';
			$nonce_action = $this->meta_box['id'] . '_save';
			if ( empty( $_POST[$nonce_key] ) || ! wp_verify_nonce( $_POST[$nonce_key], $nonce_action ) ) {
				return false;
			}

			// Make sure user has permission to edit
			$post_type = get_post_type_object( $post->post_type );
			if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
				return false;
			}

			// Get fields
			$fields = $this->meta_box['fields'];

			// Sanitize fields
			$sanitized_values = array();
			foreach ( $fields as $key => $field ) {

				// Sanitize value
				// General sanitization and sanitization based on field type, options, no_empty, etc.
				$sanitized_values[$key] = $this->sanitize_field_value( $key );

			}

			// Run additional custom sanitization function if config requires it
			if ( ! empty( $this->meta_box['custom_sanitize'] ) ) {
				$sanitized_values = call_user_func( $this->meta_box['custom_sanitize'], $sanitized_values );
			}

			// Save fields
			foreach ( $sanitized_values as $key => $value ) {

				// Add value if it key does not exist
				// Or upate value it key does exist
				// Note: see old code below which deleted key if empty value
				// This is no longer done because it causes posts with empty values to disappear when sorted on that key!
				update_post_meta( $post_id, $key, $value );

				/* Old way causes sorting problems (see note above)

				// Update value or add if key does not exist
				if ( ! empty( $value ) ) {
					update_post_meta( $post_id, $key, $value );
				}

				// Delete key if value is empty and key exists in database
				else if ( get_post_meta( $post_id, $key, true ) ) {
					delete_post_meta( $post_id, $key );
				}

				*/

			}

		}

		/**
		 * Sanitize field value
		 *
		 * Sanitize field's POST value before saving.
		 * Provides General sanitization and sanitization based on field type, options, no_empty, etc.
		 *
		 * @since 0.8.5
		 * @access public
		 * @param string $key Field key
		 * @return mixed Sanitized value
		 */
		public function sanitize_field_value( $key ) {

			global $post_id, $post, $allowedposttags;

			// Get posted value
			$input = isset( $_POST[$key] ) ? $_POST[$key] : '';

			// General sanitization
			$output = trim( stripslashes( $input ) );

			// Empty value if specific page templates required but not used
			if ( ! empty( $output ) && ! empty( $this->meta_box['fields'][$key]['page_templates'] ) && ( ! isset( $_POST['page_template'] ) || ! in_array( $_POST['page_template'], $this->meta_box['fields'][$key]['page_templates'] ) ) ) {
				$output = '';
			}

			// Sanitize based on type
			switch ( $this->meta_box['fields'][$key]['type'] ) {

				// Text
				// Textarea
				case 'text':
				case 'textarea':

					// Strip tags if config does not allow HTML
					if ( empty( $this->meta_box['fields'][$key]['allow_html'] ) ) {
						$output = trim( strip_tags( $output ) );
					}

					// Sanitize HTML in case used (remove evil tags like script, iframe) - same as post content
					if ( ! current_user_can( 'unfiltered_html' ) ) { // Admin only
						$output = stripslashes( wp_filter_post_kses( addslashes( $output ), $allowedposttags ) );
					}

					break;

				// Checkbox
				case 'checkbox':

					$output = ! empty( $output ) ? '1' : '';

					break;

				// Radio
				// Select
				case 'radio':
				case 'select':

					// If option invalid, blank it so default will be used
					if ( ! isset( $this->meta_box['fields'][$key]['options'][$output] ) ) {
						$output = '';
					}

					break;

				// Number
				case 'number':

					$output = (int) $output; // force number

					break;

				// URL
				// Upload (value is URL)
				case 'url':
				case 'upload': // Regular (URL)

					$output = esc_url_raw( $output ); // force valid URL or use nothing

					break;

				// Upload Textarea (URL or Embed Code)
				case 'upload_textarea':

					// URL?
					if ( preg_match( '/^(http(s*)):\/\//i', $output ) ) { // if begins with http:// or https://, must be URL
						$output = esc_url_raw( $output ); // force valid URL or use nothing
					}

					// Otherwise it must be embed code, so HTML is always allowed
					// <script>, <iframe>, etc. will be allowed for all users

					break;

				// Date (form sanitized date from three inputs)
				case 'date':

					$output = '';

					// Get month, day and year from $_POST
					$m = isset( $_POST[ $key . '-month' ] ) ? trim( $_POST[ $key . '-month' ] ) : '';
					$d = isset( $_POST[ $key . '-day' ] ) ? trim( $_POST[ $key . '-day' ] ) : '';
					$y = isset( $_POST[ $key . '-year' ] ) ? trim( $_POST[ $key . '-year' ] ) : '';

					// Valid date
					if ( strlen( $y ) == 4 && ! empty( $m ) && ! empty( $d ) && checkdate( $m, $d, $y ) ) { // valid year, date given and peoper (no February 31, for example)

						// Pad month and day with 0 (force 2012-6-1 into 2012-06-01)
						$m = str_pad( $m, 2, '0', STR_PAD_LEFT );
						$d = str_pad( $d, 2, '0', STR_PAD_LEFT );

						// Form the date for saving in to database
						$output = "$y-$m-$d";

					}

					break;

			}

			// Run additional custom sanitization function if config requires it
			if ( ! empty( $this->meta_box['fields'][$key]['custom_sanitize'] ) ) {
				$output = call_user_func( $this->meta_box['fields'][$key]['custom_sanitize'], $output );
			}

			// Sanitization left value empty but empty is not allowed, use default
			$output = trim( $output );
			if ( empty( $output ) ) {
				$output = $this->field_value( $key, 'default_only' ); // won't try to get saved value
			}

			// Return sanitized value
			return $output;

		}

		/**
		 * Enqueue stylesheets
		 *
		 * @since 0.8.5
		 * @access public
		 */
		public function enqueue_styles() {

			$screen = get_current_screen();

			// Add/edit any post type
			if ( 'post' == $screen->base ) {

				// Always enable thickbox on add/edit post for custom meta upload fields
				wp_enqueue_style( 'thickbox' );

				// Meta boxes stylesheet
				wp_enqueue_style( 'ctmb-meta-boxes', trailingslashit( Matt2015_dir ) . '/meta-boxes/ct-metabox.css', false, $this->version ); // bust cache on update

			}

		}

		/**
		 * Enqueue scripts
		 *
		 * @since 0.8.5
		 * @access public
		 */
		public function enqueue_scripts() {

			$screen = get_current_screen();

			// Add/edit any post type
			if ( 'post' == $screen->base ) {

				// Always enable media-upload and thickbox on add/edit post for custom meta upload fields
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );

				// Meta boxes JavaScript
				wp_enqueue_script( 'ctmb-meta-boxes', trailingslashit( Matt2015_dir ) . '/meta-boxes/ct-metabox.js', false, $this->version ); // bust cache on update

			}

		}

	}

}