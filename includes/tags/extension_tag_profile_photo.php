<?php

class Elementor_Ext_Profile_Photo_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

	private $fieldName = 'pkit_profile_photo_url';
	public function get_name() {
		return 'ph__' . $this->fieldName;
	}

	public function get_title() {
		return esc_html__( 'Profile Photo', 'textdomain' );
	}

	public function get_group() {
		return [ 'press-kit' ];
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
		$meta_key = 'pkit_profile_photo_url';
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
	    // $current_user = get_post_meta($current_post, 'associated_user', true);
	    $current_user = 19;

		$loggedIn = wp_get_current_user();
		if(current_user_can('administrator') && $post->post_type == 'pkit-template') {
			return $loggedIn->ID;

		}
		
		if (!$current_user) {
			echo $this->get_title();
			return;
		}

		return $current_user;
	}
    
}
