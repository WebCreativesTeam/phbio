<?php

class Elementor_Ext_Parent_Tag extends \Elementor\Core\DynamicTags\Tag {

	private $fieldName = 'pkit_parent';
	public function get_name() {
		return 'ph__' . $this->fieldName;
	}


	public function get_title() {
		return esc_html__( 'Parent ID', 'textdomain' );
	}

	public function get_group() {
		return [ 'press-kit' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
    public function render() {
		$current_user = $this->current_user();
	
		
		// Display the value
		echo esc_html($current_user);
	}

	public function current_user() {
	    global $post;

		// Get the current user
		$parent_id = $post->post_parent;
		
	    $current_user = get_post_meta($parent_id, 'associated_user', true);

		
		if (!$current_user) {
			echo $this->get_title();
			return;
		}

		return $parent_id;
	}
	

}