<?php

class Elementor_Ext_Username_Tag extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'ph_pkit_username';
	}

	public function get_title() {
		return esc_html__( 'Username', 'textdomain' );
	}

	public function get_group() {
		return [ 'press-kit' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
    public function render() {
		// Get the current user
		$current_user = wp_get_current_user();
	
		if (!$current_user->ID) {
			echo 'No user logged in';
			return;
		}
	
		// Get the user meta for 'tag-name'
		$meta_key = 'username';
		$meta_value = get_user_meta($current_user->ID, $meta_key, true);
	
		if (!$meta_value) {
			echo 'No meta value found for ' . $meta_key;
			return;
		}
	
		// Display the value
		echo esc_html($meta_value);
	}
	

}