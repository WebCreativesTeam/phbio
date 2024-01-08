<?php

class Elementor_Ext_Is_Logo_Tag extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'pkit_is_logo';
	}

	public function get_title() {
		return esc_html__( 'Is Website Logo', 'textdomain' );
	}

	public function get_group() {
		return [ 'press-kit' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
    public function render() {
		// Get the current user
		$current_user = $this->current_user();

		// Get the user meta for 'tag-name'
		$meta_key = 'pkit_logo';
		$meta_value = get_user_meta($current_user, $meta_key, true);
	
		
		$isLogo = '';
		if(!$meta_value || $meta_value === 'yes' ) {
			$isLogo = 'ph_logo_hidden';
		}

		echo $isLogo;
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