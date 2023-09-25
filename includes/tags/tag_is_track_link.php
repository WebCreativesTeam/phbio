<?php

class Elementor_Is_Track_Link extends \Elementor\Core\DynamicTags\Tag {

	private $fieldName = 'tracked-link';
	public function get_name() {
		return 'ph__' . $this->fieldName;
	}


	public function get_title() {
		return esc_html__( 'Class = Track = Link', 'textdomain' );
	}

	public function get_group() {
		return [ 'link-in-bio' ];
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
    public function render() {
		return $this->fieldName;
	}

}