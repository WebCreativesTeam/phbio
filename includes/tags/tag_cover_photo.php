<?php

class Elementor_Cover_Photo_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

	public function get_name() {
		return 'ph_cover_photo';
	}

	public function get_title() {
		return esc_html__( 'Cover Photo', 'textdomain' );
	}

	public function get_group() {
		return [ 'link-in-bio' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
	}
	

    public function get_value( array $options = array() )
    {

		$current_user = wp_get_current_user();
	
		if (!$current_user->ID) {
			return []; // No user logged in
		}
	
		// Get the user meta for 'profile_photo'
		$meta_key = 'cover_photo_url';
		$meta_value = get_user_meta($current_user->ID, $meta_key, true);
	
		if (!$meta_value) {
			return []; // No meta value found
		}

        return [
                'id' => 1,
                'url' => $meta_value,
            ]; 
    }

    // public function get_value( array $options = [] ) {
	// 	// Get the current user
	// 	$current_user = wp_get_current_user();
	
	// 	if (!$current_user->ID) {
	// 		return []; // No user logged in
	// 	}
	
	// 	// Get the user meta for 'profile_photo'
	// 	$meta_key = 'profile_photo';
	// 	$meta_value = get_user_meta($current_user->ID, $meta_key, true);
	
	// 	if (!$meta_value) {
	// 		return []; // No meta value found
	// 	}
	
	// 	// Return the value as 'url'
	// 	return [
	// 		'id' => 1,
	// 		'url' => $meta_value
	// 	];
	// }
}
