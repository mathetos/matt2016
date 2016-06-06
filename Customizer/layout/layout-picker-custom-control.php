<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;

/**
 * Class to create a custom layout control
 */
class Matt2015_Layout_Picker_Custom_Control extends WP_Customize_Control
{
		public $type = 'radio';
		
      /**
       * Render the content on the theme customizer page
       */
      public function render_content()
       {
            $imageDirectory = '/Customizer/layout/img/';
            $imageDirectoryInc = '/Customizer/layout/img/';

            $finalImageDirectory = '';

            if(is_dir(get_stylesheet_directory().$imageDirectory))
            {
                $finalImageDirectory = get_stylesheet_directory_uri().$imageDirectory;
            }

            if(is_dir(get_stylesheet_directory().$imageDirectoryInc))
            {
                $finalImageDirectory = get_stylesheet_directory_uri().$imageDirectoryInc;
            }
            ?>
             
                  <ul>
                    <li class="full_content">
						<label for="<?php echo $this->id; ?>[full_content]">
							<input type="radio" value="<?php echo $this->id; ?>[full_content]" name="<?php echo $this->id; ?>" data-customize-setting-link="archive_layout" id="<?php echo $this->id; ?>[full_content]"  />
							<img src="<?php echo $finalImageDirectory; ?>full-content.jpg" alt="Full Content" />
						</label>
					</li>
					
                    <li class="excerpt">
						<label for="<?php echo $this->id; ?>[excerpt]">
							<input type="radio" value="<?php echo $this->id; ?>[excerpt]" name="<?php echo $this->id; ?>" data-customize-setting-link="archive_layout"  id="<?php echo $this->id; ?>[excerpt]" />
							<img src="<?php echo $finalImageDirectory; ?>excerpt.jpg" alt="Excerpt" />
						</label>
					</li>
					
                    <li class="fancy_rollover">
						<label for="<?php echo $this->id; ?>[fancy_rollover]">
							<input type="radio" value="<?php echo $this->id; ?>[fancy_rollover]" name="<?php echo $this->id; ?>" data-customize-setting-link="archive_layout" id="<?php echo $this->id; ?>[fancy_rollover]"  />
							<img src="<?php echo $finalImageDirectory; ?>fancy-rollover.jpg" alt="Fancy Rollover" />
						</label>
					</li>	
                </ul>
            <?php
       }
}
?>