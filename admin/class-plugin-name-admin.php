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

		wp_enqueue_style(
			'main',
			plugin_dir_url( __FILE__ ) . 'css/main.css',
			array(),
			$this->version,
			'all'
		);
 
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
		

	}

	public function role_manager( $hook_suffix ) {

		// Adding 'lite-version' user role with custom capabilities
		add_role(
			'lite-version',
			'Lite Version',
			array(
				'read' => true,
				Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE => true,
				Plugin_Name_Capabilities::EDIT_PROJECT_NAME => true,
				Plugin_Name_Capabilities::EDIT_BIO => true,
				Plugin_Name_Capabilities::EDIT_LINKS => true,
			)
		);
	
		// Adding 'full-version' user role with custom capabilities
		add_role(
			'full-version',
			'Full Version',
			array(
				'read' => true,
				Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE => true,
				Plugin_Name_Capabilities::EDIT_PROJECT_NAME => true,
				Plugin_Name_Capabilities::EDIT_BIO => true,
				Plugin_Name_Capabilities::EDIT_LINKS => true,
				Plugin_Name_Capabilities::EDIT_COVER => true,
				Plugin_Name_Capabilities::HIGHLIGHT_LINK => true,
				Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO => true
			)
		);
		
		// Update 'Admin' role
		$role = get_role('administrator');
		
		// Add a new capability
		$role->add_cap(Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, true);
		$role->add_cap(Plugin_Name_Capabilities::EDIT_PROJECT_NAME, true);
		$role->add_cap(Plugin_Name_Capabilities::EDIT_BIO, true);
		$role->add_cap(Plugin_Name_Capabilities::EDIT_COVER, true);
		$role->add_cap(Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO, true);
		$role->add_cap(Plugin_Name_Capabilities::EDIT_LINKS, true);
		
		

	}

	function admin_menu( $hook_suffix ) {
		add_menu_page(
			'My Custom Page',             
			'Custom Page',                
			'read',             
			'my_custom_page_slug',        
			array($this, 'my_custom_admin_page'),       
			'dashicons-admin-generic',    
			100                           
		);
	}

	function disable_notices() {
		
		global $pagenow;
	
		// Check if we're on our custom page
		if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] == 'my_custom_page_slug' ) {
			// Remove all other actions hooked into admin_notices and all_admin_notices
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
		
	}
	
	function my_custom_admin_page( $hook_suffix ) {

		$user_id = get_current_user_id(); // default to current logged-in user

		if (current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
			$user_id = intval($_GET['user_id']); // use user_id from URL if admin
		}


		?>
		<div class="h-full p-6 mx-auto ">
    <!-- Two Column Grid -->
    <div class="grid h-full grid-cols-1 gap-3 bg-gray-200 md:grid-cols-6">

        <!-- Main Content (3/4 width on desktop) -->
		<div class="p-6 rounded md:col-span-3">
			<div class="relative m-5 bg-gray-300 bg-center bg-no-repeat bg-cover rounded-sm h-80" 
				style="background-image: url('http://luca.local/wp-content/uploads/2023/08/kelly-sikkema-YXWoEn5uOvg-unsplash.jpg')"
				x-data="{ mainOverlay: false, showOverlay: false }"
				@mouseover="mainOverlay = true"
				@mouseout="mainOverlay = false"
			>
				<!-- Main Container Overlay and Pencil Icon -->
				<div x-show="mainOverlay" class="absolute inset-0 flex items-start justify-start bg-black bg-opacity-40">
					<!-- Pencil Icon -->
				
					<button class="p-2 mt-2 ml-2 bg-white border-0 rounded-full outline-none focus:outline-none">
						<svg width="16" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M15.197 3.35462C16.8703 1.67483 19.4476 1.53865 20.9536 3.05046C22.4596 4.56228 22.3239 7.14956 20.6506 8.82935L18.2268 11.2626M10.0464 14C8.54044 12.4882 8.67609 9.90087 10.3494 8.22108L12.5 6.06212" stroke="black" stroke-width="2" stroke-linecap="round"></path>
							<path d="M13.9536 10C15.4596 11.5118 15.3239 14.0991 13.6506 15.7789L11.2268 18.2121L8.80299 20.6454C7.12969 22.3252 4.55237 22.4613 3.0464 20.9495C1.54043 19.4377 1.67609 16.8504 3.34939 15.1706L5.77323 12.7373" stroke="black" stroke-width="2" stroke-linecap="round"></path>
						</svg>
					</button>

					<!-- Main Container File Input -->
					<input 
						type="file" 
						class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer" 
					>
				</div>
				
				<div 
					class="absolute w-20 h-20 bottom-[-10%] left-[45%]"
					@mouseover="showOverlay = true; $event.stopPropagation()"
					@mouseout="showOverlay = false; $event.stopPropagation()"
				>
					<img class="w-20 h-20 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="Rounded avatar">
					
					<div x-show="showOverlay" class="absolute top-0 left-0 flex items-center justify-center w-20 h-20 bg-black rounded-full cursor-pointer bg-opacity-20">
						<!-- Profile Picture File Input -->
						<button class="p-2 bg-white border-0 rounded-full outline-none focus:outline-none">
							<svg width="16" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M15.197 3.35462C16.8703 1.67483 19.4476 1.53865 20.9536 3.05046C22.4596 4.56228 22.3239 7.14956 20.6506 8.82935L18.2268 11.2626M10.0464 14C8.54044 12.4882 8.67609 9.90087 10.3494 8.22108L12.5 6.06212" stroke="black" stroke-width="2" stroke-linecap="round"></path>
								<path d="M13.9536 10C15.4596 11.5118 15.3239 14.0991 13.6506 15.7789L11.2268 18.2121L8.80299 20.6454C7.12969 22.3252 4.55237 22.4613 3.0464 20.9495C1.54043 19.4377 1.67609 16.8504 3.34939 15.1706L5.77323 12.7373" stroke="black" stroke-width="2" stroke-linecap="round"></path>
							</svg>
						</button>
						<input 
							type="file" 
							class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer" 
						>
					</div>
				</div>
			</div>

        </div>

        <!-- Sidebar (1/4 width on desktop) -->
        <div class="p-6 rounded">
            <h2 class="mb-2 text-xl">Preview</h2>
            <!-- Your sidebar content goes here -->
        </div>

    </div>
</div>
		<?php
	}
	

	public static function render_playground() {
		?>

		<?php Plugin_Name_Builder::upload_field('profile_photo', 'Profile Photo', Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
			<?php Plugin_Name_Builder::upload_field('cover_photo', 'Cover Photo', Plugin_Name_Capabilities::EDIT_COVER, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
			
			<form method="post">
				<?php Plugin_Name_Builder::text_field('project', 'Project / Artist', 'Project / Artist:', Plugin_Name_Capabilities::EDIT_PROJECT_NAME, $user_id); ?>
				<?php Plugin_Name_Builder::textarea_field('bio', 'Bio', 'Bio:', Plugin_Name_Capabilities::EDIT_BIO, $user_id); ?>
				<!-- EDIT_LINKS -->
				<?php Plugin_Name_Builder::checkbox_field('branding' . '_chck', 'Remove Branding:', Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO, $user_id); ?>
				<!-- HIGHLIGHT_LINK -->
				<?php Plugin_Name_Builder::link_list_field( Plugin_Name_Capabilities::EDIT_LINKS, $user_id); ?>
				
				<input type="submit" name="submit_form" value="Submit">
				
			</form>

		<?php
	}

	public static function render_preview() {
		?>

		<?php Plugin_Name_Builder::upload_field('profile_photo', 'Profile Photo', Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
			<?php Plugin_Name_Builder::upload_field('cover_photo', 'Cover Photo', Plugin_Name_Capabilities::EDIT_COVER, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
			
			<form method="post">
				<?php Plugin_Name_Builder::text_field('project', 'Project / Artist', 'Project / Artist:', Plugin_Name_Capabilities::EDIT_PROJECT_NAME, $user_id); ?>
				<?php Plugin_Name_Builder::textarea_field('bio', 'Bio', 'Bio:', Plugin_Name_Capabilities::EDIT_BIO, $user_id); ?>
				<!-- EDIT_LINKS -->
				<?php Plugin_Name_Builder::checkbox_field('branding' . '_chck', 'Remove Branding:', Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO, $user_id); ?>
				<!-- HIGHLIGHT_LINK -->
				<?php Plugin_Name_Builder::link_list_field( Plugin_Name_Capabilities::EDIT_LINKS, $user_id); ?>
				
				<input type="submit" name="submit_form" value="Submit">
				
			</form>

		<?php
	}

	public static function user_column_button($columns) {
		$columns['edit_btn'] = 'Edit Profile';
		return $columns;
	}
	
	public static function user_column_button_cb($val, $column_name, $user_id) {
		$url = 'admin.php?page=my_custom_page_slug&user_id=';
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
	


	

}
