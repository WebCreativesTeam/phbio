<?php

class Elementor_Is_User_Not_Verified extends \Elementor\Core\DynamicTags\Tag {

	private $fieldName = 'user-not-verified';
	public function get_name() {
		return 'ph__' . $this->fieldName;
	}


	public function get_title() {
		return esc_html__( 'Class = User Not Verified', 'textdomain' );
	}

	public function get_group() {
		return [ 'link-in-bio' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
	public function render() {
		echo "testinggg";
		$user = $this->current_user();
		if( !in_array( 'um_free-verified', (array) $user->roles ) && !in_array( 'um_pro-verified', (array) $user->roles )) {
			if ( in_array( 'um_free-member', (array) $user->roles ) || in_array( 'um_pro-member', (array) $user->roles )  ) {
				echo "hidden";
			}
		}
		
	}

	public function current_user() {
	    global $post;

		// Get the current user
		$current_post = $post->ID;
	    $current_user = get_post_meta($current_post, 'associated_user', true);

		
		if (!$current_user) {
			echo $this->get_title();
			return;
		}

		return $current_user;
	}

}