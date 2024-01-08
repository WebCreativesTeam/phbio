<?php

class Elementor_Ext_Cover_Photo_Tag extends \Elementor\Core\DynamicTags\Data_Tag {

	private $fieldName = 'pkit_cover_photo_url';
	public function get_name() {
		return 'ph__' . $this->fieldName;
	}


	public function get_title() {
		return esc_html__( 'Cover Photo', 'textdomain' );
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
		$meta_key = 'pkit_cover_photo_url';
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
    
    
		$parent_id = $post->post_parent;
		
	    $current_user = get_post_meta($parent_id, 'associated_pkit_user', true);


		$loggedIn = wp_get_current_user();
		if(current_user_can('administrator') && $post->post_type == 'pkit-template') {
			return $loggedIn->ID;
		}

		return $current_user;
	}
    
}
