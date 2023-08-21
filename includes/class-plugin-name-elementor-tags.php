<?php

class Elementor_Test_Tag extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'tag-name';
	}

	public function get_title() {
		return esc_html__( 'Dynamic Tag Name', 'textdomain' );
	}

	public function get_group() {
		return [ 'link-in-bio' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
    public function render() {
        echo get_user_meta( get_current_user_id(), $this->get_name(), true );
    }

}