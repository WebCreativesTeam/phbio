<?php


class FormManager {

    public function __construct() {
        add_action('acf/include_fields', array($this, 'add_acf_fields'));
        add_action('acf/init', array($this, 'add_acf_options_page'));
       

    }
    
   
    public function add_acf_fields() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }
        
        acf_add_local_field_group( array(
            'key' => 'group_6528cd9a87f68',
            'title' => 'Settings - Form Manager',
            'fields' => array(
                array(
                    'key' => 'field_6528cd9b6f33a',
                    'label' => 'Forms',
                    'name' => 'pkit_fmanager',
                    'aria-label' => '',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'hide_admin' => 0,
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 12,
                    'max' => 12,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_6528cdf5a2ef1',
                            'label' => 'Form',
                            'name' => 'pkit_fmanager_form',
                            'aria-label' => '',
                            'type' => 'post_object',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'hide_admin' => 0,
                            'post_type' => array(
                                0 => 'af_form',
                            ),
                            'post_status' => array(
                                0 => 'publish',
                            ),
                            'taxonomy' => '',
                            'return_format' => 'object',
                            'multiple' => 0,
                            'allow_null' => 0,
                            'bidirectional' => 0,
                            'ui' => 1,
                            'bidirectional_target' => array(
                            ),
                            'parent_repeater' => 'field_6528cd9b6f33a',
                        ),
                        array(
                            'key' => 'field_6528ce35a2ef2',
                            'label' => 'Version',
                            'name' => 'pkit_fmanager_role',
                            'aria-label' => '',
                            'type' => 'true_false',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'hide_admin' => 0,
                            'message' => '',
                            'default_value' => 0,
                            'ui_on_text' => 'Pro',
                            'ui_off_text' => 'Free',
                            'ui' => 1,
                            'parent_repeater' => 'field_6528cd9b6f33a',
                        ),
                        array(
                            'key' => 'field_6528ce6da2ef3',
                            'label' => 'Language',
                            'name' => 'pkit_fmanager_language',
                            'aria-label' => '',
                            'type' => 'select',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'hide_admin' => 0,
                            'choices' => array(
                                'en' => 'English',
                                'it' => 'Italian',
                                'es' => 'Spanish',
                                'de' => 'German',
                                'fr' => 'French',
                                'pt' => 'Portuguese',
                            ),
                            'default_value' => false,
                            'return_format' => 'value',
                            'multiple' => 0,
                            'allow_null' => 0,
                            'ui' => 0,
                            'ajax' => 0,
                            'placeholder' => '',
                            'parent_repeater' => 'field_6528cd9b6f33a',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'presskit-form-manager',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );
    }
    
    public function add_acf_options_page() {
        acf_add_options_page( array(
            'page_title' => 'Form Manager',
            'menu_slug' => 'presskit-form-manager',
            'parent_slug' => 'presskit-settings',
            'position' => '',
            'redirect' => false,
            'autoload' => true,
        ) );
    }
}

// Instantiate the class
new FormManager();