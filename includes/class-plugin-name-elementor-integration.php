<?php

class Plugin_Name_Elementor_Integration {

private $fields;

public function __construct($fields) {
    $this->fields = $fields;
}

public function add_group($dynamic_tags_manager) {
    $dynamic_tags_manager->register_group(
        'link-in-bio',
        [
            'title' => esc_html__('Link In Bio', '')
        ]
    );
}

public function register_tags($dynamic_tags_manager) {
    foreach ($this->fields as $field) {
        $dynamic_tags_manager->register($this->generate_dynamic_tag_class($field));
    }
}

private function generate_dynamic_tag_class($field) {
    return new class($field) extends \Elementor\Core\DynamicTags\Tag {

        private $field;

        public function __construct($field) {
            $this->field = $field;
            parent::__construct();
        }

        public function get_name() {
            return $this->field['name'];
        }

        public function get_title() {
            return esc_html__($this->field['title'], 'textdomain');
        }

        public function get_group() {
            return $this->field['group'];
        }

        public function get_categories() {
            return $this->field['categories'];
        }

        public function render() {
            echo get_user_meta(get_current_user_id(), $this->get_name(), true);
        }

    };
}
}
