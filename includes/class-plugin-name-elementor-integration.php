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
    $dynamic_tags_manager->register( new \Elementor_Test_Tag() );

}
    
}

