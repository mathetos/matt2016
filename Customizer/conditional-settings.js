 jQuery(document).ready( function($) {
	//set initial state of checkboxes based on current select value
	if($('li#customize-control-enable_rrssb select').val() == 'yes') {
		$('li#customize-control-social_sharing_position').show()
		$('li#customize-control-enable_email').show()
		$('li#customize-control-enable_fb').show()
		$('li#customize-control-enable_twitter').show()
		$('li#customize-control-enable_google').show()
		$('li#customize-control-enable_linkedin').show()
		$('li#customize-control-enable_reddit').show()
		$('li#customize-control-enable_pinterest').show();
	} else {
		// set initial hide checkboxes if select set to 'no'
		$('li#customize-control-social_sharing_position').hide()
		$('li#customize-control-enable_email').hide()
		$('li#customize-control-enable_fb').hide();
		$('li#customize-control-enable_twitter').hide();
		$('li#customize-control-enable_google').hide();
		$('li#customize-control-enable_linkedin').hide();
		$('li#customize-control-enable_reddit').hide();
		$('li#customize-control-enable_pinterest').hide();
	}
	//detect change of select and hide/show depending on value
	$('li#customize-control-enable_rrssb select').change(function() {
		if($('li#customize-control-enable_rrssb select').val() == 'yes') {
			$('li#customize-control-social_sharing_position').show()
			$('li#customize-control-enable_email').show()
			$('li#customize-control-enable_fb').show();
			$('li#customize-control-enable_twitter').show();
			$('li#customize-control-enable_google').show();
			$('li#customize-control-enable_linkedin').show();
			$('li#customize-control-enable_reddit').show();
			$('li#customize-control-enable_pinterest').show();
		} else {
			// hide checkboxes if changed to 'no'
			$('li#customize-control-social_sharing_position').hide()
			$('li#customize-control-enable_email').hide()
			$('li#customize-control-enable_fb').hide();
			$('li#customize-control-enable_twitter').hide();
			$('li#customize-control-enable_google').hide();
			$('li#customize-control-enable_linkedin').hide();
			$('li#customize-control-enable_reddit').hide();
			$('li#customize-control-enable_pinterest').hide();
		}
	});
});    