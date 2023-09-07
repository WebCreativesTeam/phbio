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
}

function register_tags( $dynamic_tags_manager) {
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_project.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_bio.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_username.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_profile_photo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_cover_photo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_is_logo.php';
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/tags/tag_link_property.php';
    $dynamic_tags_manager->register( new \Elementor_Project_Name_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Bio_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Username_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Profile_Photo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Cover_Photo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Is_Logo_Tag() );
    $dynamic_tags_manager->register( new \Elementor_Link_Property() );
//
}
    


function register_widgets( $widgets_manager ) {

	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widget_copy_input.php';

	$widgets_manager->register( new \Elementor_Custom_Input_Widget() );

}

}