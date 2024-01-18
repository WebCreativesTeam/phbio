<?php

class Elementor_Ext_Is_User_Not_Verified extends \Elementor\Core\DynamicTags\Tag {

	private $fieldName = 'pkit-user-not-verified';
	public function get_name() {
		return 'ph__' . $this->fieldName;
	}


	public function get_title() {
		return esc_html__( 'Class = User Not Verified', 'textdomain' );
	}

	public function get_group() {
		return [ 'press-kit' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
	public function render() {

		$user_id = $this->current_user();
		if ( !Plugin_Name_Utilities::is_user_verified($user_id)) {
		    echo "if-user-not-verified user-not-verified";
		} else {
		   echo "if-user-not-verified user-verified";
		}
		
	}

	public function current_user() {
	    global $post;

		// Get the current user
		$current_post = $post->post_parent;
	    $current_user = get_post_meta($current_post, 'associated_pkit_user', true);

		
		if (!$current_user) {
			echo $this->get_title();
			return;
		}

		return $current_user;
	}

}