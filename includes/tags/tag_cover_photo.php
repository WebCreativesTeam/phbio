<?php

class Elementor_Cover_Photo_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

	private $fieldName = 'cover_photo_url';
	public function get_name() {
		return 'ph__' . $this->fieldName;
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

		$current_user = $this->current_user();
	
		if(!$current_user) {
			return [];
		}
	
		// Get the user meta for 'profile_photo'
		$meta_key = 'cover_photo_url';
		$meta_value = get_user_meta($current_user, $meta_key, true);
	
		if (!$meta_value) {
			return []; // No meta value found
		}

        return [
                'id' => 1,
                'url' => $meta_value,
            ]; 
    }

	public function current_user() {
	    global $post;

		// Get the current user
		$current_post = $post->ID;
	    $current_user = get_post_meta($current_post, 'associated_user', true);

		$loggedIn = wp_get_current_user();
		if(current_user_can('administrator') && $post->post_type == 'template-manager') {
			return $loggedIn->ID;
		}
		
		if (!$current_user) {
			echo $this->get_title();
			return;
		}

		return $current_user;
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
