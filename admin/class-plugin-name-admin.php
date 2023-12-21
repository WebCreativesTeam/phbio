<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks to
 * enqueue the admin-facing stylesheet and JavaScript.
 * As you add hooks and methods, update this description.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Plugin_Name_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The unique prefix of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_prefix    The string used to uniquely prefix technical functions of this plugin.
	 */
	private $plugin_prefix;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $plugin_prefix    The unique prefix of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_prefix, $version ) {

		$this->plugin_name   = $plugin_name;
		$this->plugin_prefix = $plugin_prefix;
		$this->version       = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 * You can use $hook_suffix to conditionally load scripts on certain admin pages only.
	 * Remove it, if you do not use it.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
	 * @since 1.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_styles( $hook_suffix ) {

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css',
			array(),
			$this->version,
			'all'
		);

		global $pagenow;
	
		// Check if we're on our custom page
		if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'edit-bio') || ( isset( $_GET['page'] ) && $_GET['page'] == 'linkin-bio-settings') || ( isset( $_GET['page'] ) && $_GET['page'] == 'linkin-bio-analytics') || ( isset( $_GET['page'] ) && $_GET['page'] == 'presskit-analytics') || ( isset( $_GET['page'] ) && $_GET['page'] == 'edit-presskit') || ( isset( $_GET['page'] ) && $_GET['page'] == 'presskit-settings') ) {
			wp_enqueue_style(
				'main',
				plugin_dir_url( __FILE__ ) . 'css/main.css',
				array(),
				$this->version,
				'all'
			);
			
		}

		wp_enqueue_style( 'font-awesome-icons-webfont-woff', plugin_dir_url( __FILE__ ) . 'fonts/fontawesome-webfont.woff', array(), $this->version, 'all' );
		wp_enqueue_style( 'font-awesome-icons-list', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'font-awesome-icons-list-select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
    
    
    
    
 
	}

	/**
	 * Register the JavaScript for the admin area.
	 * You can use $hook_suffix to conditionally load scripts on certain admin pages only.
	 * Remove it, if you do not use it.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
	 * @since    1.0.0
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_scripts( $hook_suffix ) {

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js',
			array( 'jquery' ),
			$this->version,
			false
		);

		wp_enqueue_script(
			'index',
			plugin_dir_url( __FILE__ ) . 'js/index.js',
			NULL,
			$this->version,
			false
		);
		wp_enqueue_script( 'username', plugin_dir_url( __FILE__ ) . 'js/username.js', NULL, $this->version, false );
		
		wp_localize_script( 'username', 'plugin', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'plugin' )
			) );
			
		wp_enqueue_script(
			'font-awesome-icons-list-select2',
			plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
			NULL,
			$this->version,
			false
		);
		wp_enqueue_script(
			'font-awesome-picker',
			plugin_dir_url( __FILE__ ) . 'js/script-main.js',
			array('jquery'),
			$this->version,
			false
		);
			
	}

	public function role_manager( $hook_suffix ) {


		// Update 'Lite' role
		$role_lite_version = get_role('um_free-member');
		
		// Add a new capability
		$role_lite_version->add_cap(Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, true);
		$role_lite_version->add_cap(Plugin_Name_Capabilities::EDIT_PROJECT_NAME, true);
		$role_lite_version->add_cap(Plugin_Name_Capabilities::EDIT_BIO, true);
		$role_lite_version->add_cap(Plugin_Name_Capabilities::EDIT_LINKS, true);
		$role_lite_version->add_cap(Plugin_Name_Capabilities::PRESSKIT_LANG, true);


	
		// Update 'Full Version' role
		$role_full_version = get_role('um_pro-member');
		
		// Add a new capability
		$role_full_version->add_cap(Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, true);
		$role_full_version->add_cap(Plugin_Name_Capabilities::EDIT_PROJECT_NAME, true);
		$role_full_version->add_cap(Plugin_Name_Capabilities::EDIT_BIO, true);
		$role_full_version->add_cap(Plugin_Name_Capabilities::EDIT_COVER, true);
		$role_full_version->add_cap(Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO, true);
		$role_full_version->add_cap(Plugin_Name_Capabilities::EDIT_LINKS, true);
		$role_full_version->add_cap(Plugin_Name_Capabilities::CAN_SCHEDULE_LINK, true);
		$role_full_version->add_cap(Plugin_Name_Capabilities::HIGHLIGHT_LINK, true);
		$role_full_version->add_cap(Plugin_Name_Capabilities::PRESSKIT_LANG, true);

		
		
		// Update 'Admin' role
		$role_admin = get_role('administrator');
		
		// Add a new capability
		$role_admin->add_cap(Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, true);
		$role_admin->add_cap(Plugin_Name_Capabilities::EDIT_PROJECT_NAME, true);
		$role_admin->add_cap(Plugin_Name_Capabilities::EDIT_BIO, true);
		$role_admin->add_cap(Plugin_Name_Capabilities::EDIT_COVER, true);
		$role_admin->add_cap(Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO, true);
		$role_admin->add_cap(Plugin_Name_Capabilities::EDIT_LINKS, true);
		$role_admin->add_cap(Plugin_Name_Capabilities::CAN_SCHEDULE_LINK, true);

		

	}

	
	function disable_notices() {
		
		global $pagenow;
	
		// Check if we're on our custom page
		if ( ( isset( $_GET['page'] ) && $_GET['page'] == 'edit-bio') || ( isset( $_GET['page'] ) && $_GET['page'] == 'linkin-bio-settings') || ( isset( $_GET['page'] ) && $_GET['page'] == 'edit-presskit') || ( isset( $_GET['page'] ) && $_GET['page'] == 'presskit-settings') ) {
			// Remove all other actions hooked into admin_notices and all_admin_notices
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
		
	}
	
	public static function role_change_cb($user_id, $role, $old_roles) {

		

		if (in_array('um_pro-member', $old_roles) && $role === 'um_free-member') {

			// $default = get_user_meta(1, 'default_template', true);	
			// $default_pkit = get_user_meta(1, 'default_pkit_template', true);
			// update_usermeta( $user_id, 'selected_template', 9522 );
			// update_user_meta( $user_id, 'selected_pkit_template', $default_pkit );
			
			
			$pkit_langs = get_user_meta($user_id, 'pkit_lang', true);
			$parts = explode(",", $pkit_langs);
			$firstLang = $pkit_langs[0];
			update_user_meta( $user_id, 'pkit_lang', $firstLang );
			// Backup links list
			$meta_value = get_user_meta($user_id, 'links_list', true);
			update_user_meta($user_id, '_backup_meta_field', $meta_value);
			update_user_meta($user_id, '_backup_date', current_time('mysql'));
		}

		// Roles if user role is upgraded back
		if (in_array('um_free-member', $old_roles) && $role === 'um_pro-member') {

			$backup_value = get_user_meta($user_id, '_backup_meta_field', true);
			if ($backup_value) {
				update_user_meta($user_id, 'links_list', $backup_value);
				delete_user_meta($user_id, '_backup_meta_field');
				delete_user_meta($user_id, '_backup_date');
			}
		}

		
	}

	public static function user_column_button($columns) {
		$columns['edit_btn'] = 'Edit Profile';
		return $columns;
	}
	
	public static function user_column_button_cb($val, $column_name, $user_id) {
		$url = 'admin.php?page=edit-bio&user_id=';
		if ($column_name == 'edit_btn') {
			if (Plugin_Name_Utilities::is_lite_version($user_id)) {
				return '<a href="' . admin_url($url . $user_id) . '" class="button action">Edit Lite Version</a>';
			}
			
			if (Plugin_Name_Utilities::is_full_version($user_id)) {
				return '<a href="' . admin_url($url . $user_id) . '" class="button action">Edit Full Version</a>';
			}
		}
		
		return $val;
	}
	
	public static function template_manager( $hook_suffix ) {
		$labels = array(
			'name'               => _x( 'Templates', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'Template', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'Template Manager', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'Template', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'Add New', 'template', 'text-domain' ),
			'add_new_item'       => __( 'Add New Template', 'text-domain' ),
			'new_item'           => __( 'New Template', 'text-domain' ),
			'edit_item'          => __( 'Edit Template', 'text-domain' ),
			'view_item'          => __( 'View Template', 'text-domain' ),
			'all_items'          => __( 'All Templates', 'text-domain' ),
			'search_items'       => __( 'Search Templates', 'text-domain' ),
			'not_found'          => __( 'No templates found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No templates found in Trash.', 'text-domain' )
		);
	
		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'text-domain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'template-manager' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);
	
		register_post_type( 'template-manager', $args );
	}
	public static function pkit_template_manager( $hook_suffix ) {
		$labels = array(
			'name'               => _x( 'Templates', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'Template', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'Template Manager', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'Template', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'Add New', 'template', 'text-domain' ),
			'add_new_item'       => __( 'Add New Template', 'text-domain' ),
			'new_item'           => __( 'New Template', 'text-domain' ),
			'edit_item'          => __( 'Edit Template', 'text-domain' ),
			'view_item'          => __( 'View Template', 'text-domain' ),
			'all_items'          => __( 'All Templates', 'text-domain' ),
			'search_items'       => __( 'Search Templates', 'text-domain' ),
			'not_found'          => __( 'No templates found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No templates found in Trash.', 'text-domain' )
		);
	
		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'text-domain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'pkit-template' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);
	
		register_post_type( 'pkit-template', $args );
	}

	public static function user_profile_manager( $hook_suffix ) {
		$labels = array(
			'name'               => _x( 'User Profiles', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'User Profile', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'User Profiles', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'User Profile', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'Add New', 'user profile', 'text-domain' ),
			'add_new_item'       => __( 'Add New User Profile', 'text-domain' ),
			'new_item'           => __( 'New User Profile', 'text-domain' ),
			'edit_item'          => __( 'Edit User Profile', 'text-domain' ),
			'view_item'          => __( 'View User Profile', 'text-domain' ),
			'all_items'          => __( 'All User Profiles', 'text-domain' ),
			'search_items'       => __( 'Search User Profiles', 'text-domain' ),
			'not_found'          => __( 'No user profiles found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No user profiles found in Trash.', 'text-domain' )
		);
	
		$args = array(
			'labels'             => $labels,
			'description'        => __( 'User Profiles.', 'text-domain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'bio' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);
	
		register_post_type( 'hb-user-profile', $args );
	}
	public static function user_pkit_manager( $hook_suffix ) {
		$labels = array(
			'name'               => _x( 'User Press Kits', 'post type general name', 'text-domain' ),
			'singular_name'      => _x( 'User Press Kit', 'post type singular name', 'text-domain' ),
			'menu_name'          => _x( 'User Press Kits', 'admin menu', 'text-domain' ),
			'name_admin_bar'     => _x( 'User Press Kit', 'add new on admin bar', 'text-domain' ),
			'add_new'            => _x( 'Add New', 'User Press Kits', 'text-domain' ),
			'add_new_item'       => __( 'Add New User Press Kit', 'text-domain' ),
			'new_item'           => __( 'New User Press Kit', 'text-domain' ),
			'edit_item'          => __( 'Edit User Press Kit', 'text-domain' ),
			'view_item'          => __( 'View User Press Kit', 'text-domain' ),
			'all_items'          => __( 'All User Press Kits', 'text-domain' ),
			'search_items'       => __( 'Search User Press Kits', 'text-domain' ),
			'not_found'          => __( 'No User Press Kits found.', 'text-domain' ),
			'not_found_in_trash' => __( 'No User Press Kits found in Trash.', 'text-domain' )
		);
	
		$args = array(
			'labels'             => $labels,
			'description'        => __( 'User Press Kits.', 'text-domain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'presskit' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);
	
		register_post_type( 'hb-user-pkit', $args );
	}
	

     function template_version_mb( $hook_suffix ) {
		add_meta_box(
			'version_id',
			__( 'Version', 'text-domain' ),
			array($this, 'template_version_field'),    
			'template-manager'
		);
	}
     function pkit_template_version_mb( $hook_suffix ) {
		add_meta_box(
			'version_id',
			__( 'Version', 'text-domain' ),
			array($this, 'template_version_field'),    
			'pkit-template'
		);
	}

    function template_version_field( $post ) {
		$value = get_post_meta( $post->ID, '_version_key', true );
		?>
		<label for="version_field">Version:</label>
		<select id="version_field" name="version_field">
			<option value="lite" <?php selected( $value, 'lite' ); ?>>Free Template</option>
			<option value="full" <?php selected( $value, 'full' ); ?>>Pro Template</option>
		</select>
		<?php
	}

	public static function template_version_field_save( $post_id ) {
		if ( array_key_exists( 'version_field', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_version_key',
				$_POST['version_field']
			);
		}
	}
	
	function redirect_parent_post_access() {
		if (is_singular('hb-user-pkit')) {
			global $post;
			if ($post->post_parent == 0) { // Check if it's a parent post
				wp_redirect(home_url()); // Redirect to homepage or any other page
				exit;
			}
		}
	}
	
	

function render_user_profile_elementor_content($content) {
    // Check if we're on a 'user-profile' post type
    if(get_post_type() !== 'hb-user-profile') return $content;
    
    // Get the current post's associated user (assuming you've saved the user ID in the post meta with key 'associated_user')
    $user_id = get_post_meta(get_the_ID(), 'associated_user', true);
    
    if (!$user_id) {
        // Error: No associated user found.
        return $content . '<p class="error">Error: No associated user found for this profile.</p>';
    }
    
    // Get the template manager ID from the user meta
    $template_manager_id = get_user_meta($user_id, 'selected_template', true);
    
    // If no template manager ID is found, return an error message
    if(!$template_manager_id) {
        return $content . '<p class="error">Error: No template found for this user.</p>';
    }
    
    // Fetch the Elementor content and append or replace the original content
    $elementor_content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_manager_id);
    
    if (!$elementor_content) {
        // Error: Failed to fetch Elementor content
        return $content . '<p class="error">Error: Failed to load template content.</p>';
    }
    
    return $content . $elementor_content;
}

function render_user_presskit_elementor_content($content) {
    // Check if we're on a 'hb-user-pkit' post type
    if (get_post_type() !== 'hb-user-pkit') return $content;
    
    global $post;
    $parent_id = $post->post_parent;

    // Check if the current post is a child post
    if ($parent_id == 0) {
        // It's a parent post, so we return the content unchanged
        return $content;
    }
    
    // Fetch the user ID from the parent post's meta
    $user_id = get_post_meta($parent_id, 'associated_pkit_user', true);
    
    if (!$user_id) {
        // Error: No associated user found on the parent post.
        return $content . '<p class="error">Error: No associated user found for this parent profile.</p>';
    }
    
    // Get the template manager ID from the user meta
    $template_manager_id = get_user_meta($user_id, 'selected_pkit_template', true);
    
    // If no template manager ID is found, return an error message
    if (!$template_manager_id) {
        return $content . '<p class="error">Error: No template found for this user.</p>';
    }
    
    // Fetch the Elementor content and append or replace the original content
    $elementor_content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_manager_id);
    
    if (!$elementor_content) {
        // Error: Failed to fetch Elementor content
        return $content . '<p class="error">Error: Failed to load template content.</p>';
    }
    
    // Append the Elementor content to the original content
    return $content . $elementor_content;
}


function user_profile_private_redirection() {
    global $post;

    // Check if we're on a 'user-profile' post type
    if (is_singular('hb-user-profile')) {
        
        // Get the associated user for this CPT post (assuming you've saved the user ID in the post meta with key 'associated_user')
        $user_id = get_post_meta($post->ID, 'associated_user', true);
        
        // Exclude the associated user and administrators from the redirection
        if (get_current_user_id() == $user_id || current_user_can('manage_options')) {
            return;
        }
        
        // Get the 'public' meta for this user
        $public = get_user_meta($user_id, 'public', true);
        
        // If 'public' is set to 'no', perform the redirection
        if ($public === 'no') {
            
            // Get the private redirection URL set for user ID '1'
            $redirection_url = get_user_meta(1, 'private_redirection', true);

            // If the redirection URL is set, redirect to it. Otherwise, redirect to the 404 page.
            if (!empty($redirection_url)) {
                wp_redirect($redirection_url, 503);
                exit;
            } else {
                wp_redirect(get_site_url('/'), 503);
                exit;
            }
			
        }
    }
    if (is_singular('hb-user-pkit')) {
        
		
	
		if($post->post_parent !== 0) {
				// Get the associated user for this CPT post (assuming you've saved the user ID in the post meta with key 'associated_user')
				$user_id = get_post_meta($post->post_parent, 'associated_pkit_user', true);
			
			
				// Exclude the associated user and administrators from the redirection
				if (get_current_user_id() == $user_id || current_user_can('manage_options')) {
					return;
				}
				
				// Get the 'public' meta for this user and this child post
				$public = get_user_meta($user_id, 'public_' . $post->ID, true);
				
				// If 'public' is NOT set to 'yes', perform the redirection
				if ($public !== 'yes') {
            
					// Get the private redirection URL set for user ID '1'
					$redirection_url = get_user_meta(1, 'private_pkit_redirection', true);
		
					// If the redirection URL is set, redirect to it. Otherwise, redirect to the 404 page.
					if (!empty($redirection_url)) {
						wp_redirect($redirection_url, 503);
						exit;
					} else {
						wp_redirect(get_site_url('/'), 503);
						exit;
					}
				}
			
		}
	}
	
}

    

	

}
