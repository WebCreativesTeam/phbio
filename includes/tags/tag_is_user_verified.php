<?php

class Elementor_Is_User_Verified extends \Elementor\Core\DynamicTags\Tag {

	private $fieldName = 'user-verified';
	public function get_name() {
		return 'ph__' . $this->fieldName;
	}


	public function get_title() {
		return esc_html__( 'Class = User Verified', 'textdomain' );
	}

	public function get_group() {
		return [ 'link-in-bio' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
	public function render() {

		$user_id = $this->current_user();

		if ( Plugin_Name_Utilities::is_user_verified($user_id)) {
		   echo "if-user-verified user-verified";
		} else {
		   echo "if-user-verified user-not-verified";
		}
		
	}

	public function current_user() {
	    global $post;

		// Get the current user
		$current_post = $post->ID;
	    $current_user = get_post_meta($current_post, 'associated_user', true);

		
		if (!$current_user) {
			return;
		}

		return $current_user;
	}

}