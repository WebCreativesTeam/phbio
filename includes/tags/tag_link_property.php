<?php

class Elementor_Link_Property extends \Elementor\Core\DynamicTags\Tag {

    public function get_name() {
        return 'link-property';
    }

    public function get_title() {
        return __('Link Property', 'text-domain');
    }

    public function get_group() {
        return 'link-in-bio'; // You can create a custom group or use an existing one
    }

    public function get_categories() {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function _register_controls() {
        $this->add_control(
            'property_name',
            [
                'label' => __('Property Name', 'text-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'property_name_here',
            ]
        );
    }

    public function render() {
        global $post;

        $property_name = $this->get_settings('property_name');

        if ($property_name && isset($post->$property_name)) {
            echo $post->$property_name;
        }
    }
}
