<?php

class Elementor_Ext_Project_Name_Tag extends \Elementor\Core\DynamicTags\Tag {

	private $fieldName = 'pkit_project';
	public function get_name() {
		return 'ph__' . $this->fieldName;
	}


	public function get_title() {
		return esc_html__( 'Project Name', 'textdomain' );
	}

	public function get_group() {
		return [ 'press-kit' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
    public function render() {
		$current_user = $this->current_user();
	
		if(!$current_user) {
			return;
		}
		// Get the user meta for 'tag-name'
		$meta_key = 'pkit_project';
		
		$meta_value = get_user_meta($current_user, $meta_key, true);
	
		if (!$meta_value) {
			echo 'No meta value found for ' . $meta_key;
			return;
		}
	
		// Display the value
		echo esc_html($meta_value);
	}

	public function current_user() {
	    global $post;

		// Get the current user
		$parent_id = $post->post_parent;
		
	    $current_user = get_post_meta($parent_id, 'associated_pkit_user', true);

		
		if (!$current_user) {
			echo $this->get_title();
			return;
		}

		return $current_user;
	}
	

}