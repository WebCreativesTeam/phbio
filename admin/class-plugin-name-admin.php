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
		<div class="dashboard-layout">

<div x-data="{ editMode: false }" 
	 x-init="() => { 
		 if (localStorage.getItem('editMode') !== null) { 
			 editMode = (localStorage.getItem('editMode') === 'true'); 
		 } 
	 }" 
	 class="main-area">
	<!-- Actions Area -->
	<div class="actions-area">
		<h1 x-text="!editMode ? 'Edit Profile' : 'Profile Preview' " class="page-title"></h1>
		<label class="toggle-label">
			<input type="checkbox" x-model="editMode" @change="localStorage.setItem('editMode', editMode)" style="display: none !important">
			<div class="toggle">
				<div class="toggle__line"></div>
				<div class="toggle__dot"></div>
			</div>
		</label>
	</div>

	<!-- Content Area -->
	<div x-show="editMode" class="content-preview">
		<!-- Profile Preview Goes Here -->

	</div>

	<div x-show="!editMode" class="content-edit">
		<!-- Edit Profile Form Goes Here -->
		<?php Plugin_Name_Builder::upload_field('profile_photo', 'Profile Photo', Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
		<?php Plugin_Name_Builder::upload_field('cover_photo', 'Cover Photo', Plugin_Name_Capabilities::EDIT_COVER, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
		
		<form method="post">
			<?php 
			Plugin_Name_Builder::text_field('project', 
			'Project / Artist', 
			'Project / Artist', 
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M14.58,11.3a3.24,3.24,0,0,0,.71-2,3.29,3.29,0,0,0-6.58,0,3.24,3.24,0,0,0,.71,2,5,5,0,0,0-2,2.31,1,1,0,1,0,1.84.78A3,3,0,0,1,12,12.57h0a3,3,0,0,1,2.75,1.82,1,1,0,0,0,.92.61,1.09,1.09,0,0,0,.39-.08,1,1,0,0,0,.53-1.31A5,5,0,0,0,14.58,11.3ZM12,10.57h0a1.29,1.29,0,1,1,1.29-1.28A1.29,1.29,0,0,1,12,10.57ZM18,2H6A3,3,0,0,0,3,5V16a3,3,0,0,0,3,3H8.59l2.7,2.71A1,1,0,0,0,12,22a1,1,0,0,0,.65-.24L15.87,19H18a3,3,0,0,0,3-3V5A3,3,0,0,0,18,2Zm1,14a1,1,0,0,1-1,1H15.5a1,1,0,0,0-.65.24l-2.8,2.4L9.71,17.29A1,1,0,0,0,9,17H6a1,1,0,0,1-1-1V5A1,1,0,0,1,6,4H18a1,1,0,0,1,1,1Z"></path></svg>', 
			Plugin_Name_Capabilities::EDIT_PROJECT_NAME, $user_id); 
			?>

			<?php Plugin_Name_Builder::textarea_field('bio', 'Bio', 'Bio:', Plugin_Name_Capabilities::EDIT_BIO, $user_id); ?>
			<?php Plugin_Name_Builder::link_list_field('Manage Links', Plugin_Name_Capabilities::EDIT_LINKS, $user_id); ?>
			<input type="submit" name="submit_form" value="Submit" class="upload-btn">
		</form>
	</div>
</div>

</div>



		<?php
	}
	

	public static function render_playground( $user_id) {
		?>

		<?php Plugin_Name_Builder::upload_field('profile_photo', 'Profile Photo', Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
			<?php Plugin_Name_Builder::upload_field('cover_photo', 'Cover Photo', Plugin_Name_Capabilities::EDIT_COVER, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
			
			<form method="post">
				<?php Plugin_Name_Builder::text_field('project', 'Project / Artist', 'Project / Artist:', Plugin_Name_Capabilities::EDIT_PROJECT_NAME, $user_id); ?>
				<?php Plugin_Name_Builder::textarea_field('bio', 'Bio', 'Bio:', Plugin_Name_Capabilities::EDIT_BIO, $user_id); ?>
				<!-- EDIT_LINKS -->
				<?php Plugin_Name_Builder::checkbox_field('branding' . '_chck', 'Remove Branding:', Plugin_Name_Capabilities::MANAGE_WEBSITE_LOGO, $user_id); ?>
				<!-- HIGHLIGHT_LINK -->
				<?php Plugin_Name_Builder::link_list_field('Manage Links', Plugin_Name_Capabilities::EDIT_LINKS, $user_id); ?>
				
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
