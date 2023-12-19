<?php

class Plugin_Name_Elementor_Integration {

/**
 * Register new dynamic tag group
 *
 * @since 1.0.0
 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
 * @return void
 */
function add_group( $dynamic_tags_manager ) {

	$dynamic_tags_manager->register_group(
		'link-in-bio',
		[
			'title' => esc_html__( 'Link In Bio', '' )
		]
	);
	$dynamic_tags_manager->register_group(
		'press-kit',
		[
			'title' => esc_html__( 'Presskit', '' )
		]
	);
}

function register_tags( $dynamic_tags_manager) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_project.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/extension_tag_project.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_bio.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_username.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_profile_photo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_cover_photo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/extension_tag_is_logo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/extension_tag_username.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/extension_tag_profile_photo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/extension_tag_cover_photo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_is_logo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_link_property.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_is_track_link.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_is_track_social_link.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_is_user_verified.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_is_user_not_verified.php';
    $dynamic_tags_manager->register( new \Elementor_Project_Name_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Ext_Project_Name_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Bio_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Username_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Profile_Photo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Cover_Photo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Ext_Username_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Ext_Profile_Photo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Ext_Cover_Photo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Ext_Is_Logo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Is_Logo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Link_Property() );
    $dynamic_tags_manager->register( new \Elementor_Is_Track_Link() );
    $dynamic_tags_manager->register( new \Elementor_Is_Track_Social_Link() );
    $dynamic_tags_manager->register( new \Elementor_Is_User_Not_Verified() );
    $dynamic_tags_manager->register( new \Elementor_Is_User_Verified() );
//
}
    


// function register_widgets( $widgets_manager ) {

// 	// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget_copy_input.php';

// 	// $widgets_manager->register( new \Elementor_Custom_Icon_Widget() );

// }



function extend_icon( $element, $args ) {
	// Add a new control
	$element->add_control(
		'phbio_icon_value',
		[
			'label' => __( 'Dynamic Icon', 'elementor' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'placeholder' => __( 'fa-facebook', 'elementor' ),
			'section' => 'section_icon',
			'dynamic' => [
				'active' => true,
			],
			'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
		]
	);
}

function extend_icon_render( $content, $widget ) {
    if( 'icon' === $widget->get_name() ) {
        // Get the settings
        $settings = $widget->get_settings_for_display();

        // Check if the custom_icon_text is set
        if( ! empty( $settings['phbio_icon_value'] ) ) {
            $content = str_replace('fab fa-facebook', ' fa fab ' . esc_attr( $settings['phbio_icon_value'] ), $content);
        }
    }
    return $content;
}




}