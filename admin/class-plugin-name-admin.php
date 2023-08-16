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
		add_menu_page(
			'Link in Bio Settings',             
			'Link in Bio Settings',                
			'manage_options',             
			'linkin-bio-settings',        
			array($this, 'settings_page'),       
			'dashicons-admin-generic',    
			100                           
		);
	}

	function settings_page() {

		$user_id = get_current_user_id();

		
		?>
			<div class="dashboard-layout">
				<div class="main-area">
					<div class="actions-area">
					    <div class="title-area">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z"></path></svg>
							<h1 class="page-title"> Link In Bio - Settings </h1>		
						</div>
					</div>
					<div class="content-edit">
						<form method="post">
							<?php 
							Plugin_Name_Builder::text_field('limit_project', 
							'20', 
							true,
							'Character Limit : Project / Artist', 
							'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17,6H7A1,1,0,0,0,7,8h4v9a1,1,0,0,0,2,0V8h4a1,1,0,0,0,0-2Z"></path></svg>', 
							Plugin_Name_Capabilities::EDIT_PROJECT_NAME, false, $user_id); 
							?>

							<?php 
							Plugin_Name_Builder::text_field('limit_bio', 
							'150', 
							true,
							'Character Limit : Bio', 
							'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17,6H7A1,1,0,0,0,7,8h4v9a1,1,0,0,0,2,0V8h4a1,1,0,0,0,0-2Z"></path></svg>', 
							Plugin_Name_Capabilities::EDIT_PROJECT_NAME, false, $user_id); 
							?>

							<?php 
							Plugin_Name_Builder::text_field('limit_links_lite', 
							'5', 
							true,
							'Links Limit : Lite Version', 
							'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.11,15.39,8.23,19.27a2.47,2.47,0,0,1-3.5,0,2.46,2.46,0,0,1,0-3.5l3.88-3.88a1,1,0,1,0-1.42-1.42L3.31,14.36a4.48,4.48,0,0,0,6.33,6.33l3.89-3.88a1,1,0,0,0-1.42-1.42Zm-3.28-.22a1,1,0,0,0,.71.29,1,1,0,0,0,.71-.29l4.92-4.92a1,1,0,1,0-1.42-1.42L8.83,13.75A1,1,0,0,0,8.83,15.17ZM21,18H20V17a1,1,0,0,0-2,0v1H17a1,1,0,0,0,0,2h1v1a1,1,0,0,0,2,0V20h1a1,1,0,0,0,0-2Zm-4.19-4.47,3.88-3.89a4.48,4.48,0,0,0-6.33-6.33L10.47,7.19a1,1,0,1,0,1.42,1.42l3.88-3.88a2.47,2.47,0,0,1,3.5,0,2.46,2.46,0,0,1,0,3.5l-3.88,3.88a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0Z"></path></svg>', 
							Plugin_Name_Capabilities::EDIT_PROJECT_NAME, false, $user_id); 
							?>

							<?php 
							Plugin_Name_Builder::text_field('limit_links_full', 
							'10', 
							true,
							'Links Limit : Full Version', 
							'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.11,15.39,8.23,19.27a2.47,2.47,0,0,1-3.5,0,2.46,2.46,0,0,1,0-3.5l3.88-3.88a1,1,0,1,0-1.42-1.42L3.31,14.36a4.48,4.48,0,0,0,6.33,6.33l3.89-3.88a1,1,0,0,0-1.42-1.42Zm-3.28-.22a1,1,0,0,0,.71.29,1,1,0,0,0,.71-.29l4.92-4.92a1,1,0,1,0-1.42-1.42L8.83,13.75A1,1,0,0,0,8.83,15.17ZM21,18H20V17a1,1,0,0,0-2,0v1H17a1,1,0,0,0,0,2h1v1a1,1,0,0,0,2,0V20h1a1,1,0,0,0,0-2Zm-4.19-4.47,3.88-3.89a4.48,4.48,0,0,0-6.33-6.33L10.47,7.19a1,1,0,1,0,1.42,1.42l3.88-3.88a2.47,2.47,0,0,1,3.5,0,2.46,2.46,0,0,1,0,3.5l-3.88,3.88a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0Z"></path></svg>', 
							Plugin_Name_Capabilities::EDIT_PROJECT_NAME, false, $user_id); 
							?>
							<input type="submit" name="submit_form" value="Submit" class="upload-btn">
						</form>
					</div>
				</div>
			</div>
		<?php
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

<div x-data="{ editMode: false, showSettings: false, showTemplates: false }" 
     x-init="() => { 
         if (localStorage.getItem('editMode') !== null) { 
             editMode = (localStorage.getItem('editMode') === 'true'); 
         } 
     }" 
     class="relative main-area"> <!-- Added relative positioning here -->

    <!-- Actions Area -->
    <div class="actions-area">
        <h1 x-text="!editMode ? 'Edit Mode' : 'Preview Mode' " class="page-title"></h1>

        <!-- New Flex Container for Buttons and Toggle -->
        <div class="action-buttons">

            <!-- Button: Select Template -->
            <button @click="showTemplates = !showTemplates; showSettings = false;" class="template-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19,2H9A3,3,0,0,0,6,5V6H5A3,3,0,0,0,2,9V19a3,3,0,0,0,3,3H15a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V5A3,3,0,0,0,19,2ZM16,19a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H16Zm0-9H4V9A1,1,0,0,1,5,8H15a1,1,0,0,1,1,1Zm4,5a1,1,0,0,1-1,1H18V9a3,3,0,0,0-.18-1H20Zm0-9H8V5A1,1,0,0,1,9,4H19a1,1,0,0,1,1,1Z"></path></svg>
                Select Template
            </button>

            <!-- Button: Settings (SVG only) -->
            <button @click="showSettings = !showSettings; showTemplates = false;" class="settings-btn">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z"></path></svg>
            </button>

            <!-- Toggle -->
            <label class="toggle-label">
                <input type="checkbox" x-model="editMode" @change="localStorage.setItem('editMode', editMode)" style="display: none !important">
                <div class="toggle">
                    <div class="toggle__line"></div>
                    <div class="toggle__dot"></div>
                </div>
            </label>

        </div> <!-- End of Flex Container -->
    </div>

    <!-- Settings Content Area -->
    <div x-show="showSettings" class=" content-settings">
		 <!-- Back Button for Settings -->
		 <button @click="showSettings = false" class="mt-6 ml-4 template-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path></svg>
			Back
		</button>
        Settings Content Goes Here
    </div>

    <!-- Templates Content Area -->
    <div x-show="showTemplates" class=" content-templates">
		<!-- Back Button for Templates -->
		<button @click="showTemplates = false" class="mt-6 ml-4 template-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path></svg>
			Back
		</button>
        Templates Content Goes Here
    </div>

    <!-- Content Area for Edit Mode -->
    <div x-show="editMode" class="content-preview">
        <!-- Edit Profile Form Goes Here -->
		

    </div>

    <!-- Content Area for Preview Mode -->
    <div x-show="!editMode" class="content-edit">
        <?php Plugin_Name_Builder::upload_field('profile_photo', 'Profile Photo', Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
		<?php Plugin_Name_Builder::upload_field('cover_photo', 'Cover Photo', Plugin_Name_Capabilities::EDIT_COVER, array('image/jpeg', 'image/png', 'image/tiff'), 2 * 1024 * 1024, $user_id); ?>
		
		<form method="post">
			<?php 
			Plugin_Name_Builder::text_field('project', 
			'Project / Artist', 
			false,
			'Project / Artist', 
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M14.58,11.3a3.24,3.24,0,0,0,.71-2,3.29,3.29,0,0,0-6.58,0,3.24,3.24,0,0,0,.71,2,5,5,0,0,0-2,2.31,1,1,0,1,0,1.84.78A3,3,0,0,1,12,12.57h0a3,3,0,0,1,2.75,1.82,1,1,0,0,0,.92.61,1.09,1.09,0,0,0,.39-.08,1,1,0,0,0,.53-1.31A5,5,0,0,0,14.58,11.3ZM12,10.57h0a1.29,1.29,0,1,1,1.29-1.28A1.29,1.29,0,0,1,12,10.57ZM18,2H6A3,3,0,0,0,3,5V16a3,3,0,0,0,3,3H8.59l2.7,2.71A1,1,0,0,0,12,22a1,1,0,0,0,.65-.24L15.87,19H18a3,3,0,0,0,3-3V5A3,3,0,0,0,18,2Zm1,14a1,1,0,0,1-1,1H15.5a1,1,0,0,0-.65.24l-2.8,2.4L9.71,17.29A1,1,0,0,0,9,17H6a1,1,0,0,1-1-1V5A1,1,0,0,1,6,4H18a1,1,0,0,1,1,1Z"></path></svg>', 
			Plugin_Name_Capabilities::EDIT_PROJECT_NAME, true, $user_id); 
			?>

			<?php Plugin_Name_Builder::textarea_field('bio', 'Bio', 'Bio:', Plugin_Name_Capabilities::EDIT_BIO, true, $user_id); ?>
			<input type="submit" name="submit_form" value="Submit" class="upload-btn">
		</form>
    </div>
</div>

</div>


		<?php
	}
	function old2( $hook_suffix ) {

		$user_id = get_current_user_id(); // default to current logged-in user

		if (current_user_can('administrator') && isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
			$user_id = intval($_GET['user_id']); // use user_id from URL if admin
		}


		?>
		<div class="dashboard-layout">

<div x-data="{ editMode: false, showSettings: false, showTemplates: false }" 
	 x-init="() => { 
		 if (localStorage.getItem('editMode') !== null) { 
			 editMode = (localStorage.getItem('editMode') === 'true'); 
		 } 
	 }" 
	 class="main-area">
	<!-- Actions Area -->
	<div class="actions-area">
		<h1 x-text="!editMode ? 'Edit Mode' : 'Preview Mode' " class="page-title"></h1>
		 <!-- New Flex Container for Buttons and Toggle -->
		 <div class="action-buttons">

		<!-- Button: Select Template -->
		<button @click="showTemplates = !showTemplates; showSettings = false;" class="template-btn">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19,2H9A3,3,0,0,0,6,5V6H5A3,3,0,0,0,2,9V19a3,3,0,0,0,3,3H15a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V5A3,3,0,0,0,19,2ZM16,19a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H16Zm0-9H4V9A1,1,0,0,1,5,8H15a1,1,0,0,1,1,1Zm4,5a1,1,0,0,1-1,1H18V9a3,3,0,0,0-.18-1H20Zm0-9H8V5A1,1,0,0,1,9,4H19a1,1,0,0,1,1,1Z"></path></svg>
			Select Template
		</button>

		<!-- Button: Settings (SVG only) -->
		<button @click="showSettings = !showSettings; showTemplates = false;" class="settings-btn">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M19.9,12.66a1,1,0,0,1,0-1.32L21.18,9.9a1,1,0,0,0,.12-1.17l-2-3.46a1,1,0,0,0-1.07-.48l-1.88.38a1,1,0,0,1-1.15-.66l-.61-1.83A1,1,0,0,0,13.64,2h-4a1,1,0,0,0-1,.68L8.08,4.51a1,1,0,0,1-1.15.66L5,4.79A1,1,0,0,0,4,5.27L2,8.73A1,1,0,0,0,2.1,9.9l1.27,1.44a1,1,0,0,1,0,1.32L2.1,14.1A1,1,0,0,0,2,15.27l2,3.46a1,1,0,0,0,1.07.48l1.88-.38a1,1,0,0,1,1.15.66l.61,1.83a1,1,0,0,0,1,.68h4a1,1,0,0,0,.95-.68l.61-1.83a1,1,0,0,1,1.15-.66l1.88.38a1,1,0,0,0,1.07-.48l2-3.46a1,1,0,0,0-.12-1.17ZM18.41,14l.8.9-1.28,2.22-1.18-.24a3,3,0,0,0-3.45,2L12.92,20H10.36L10,18.86a3,3,0,0,0-3.45-2l-1.18.24L4.07,14.89l.8-.9a3,3,0,0,0,0-4l-.8-.9L5.35,6.89l1.18.24a3,3,0,0,0,3.45-2L10.36,4h2.56l.38,1.14a3,3,0,0,0,3.45,2l1.18-.24,1.28,2.22-.8.9A3,3,0,0,0,18.41,14ZM11.64,8a4,4,0,1,0,4,4A4,4,0,0,0,11.64,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,11.64,14Z"></path></svg>
		</button>

		<!-- Toggle -->
		<label class="toggle-label">
			<input type="checkbox" x-model="editMode" @change="localStorage.setItem('editMode', editMode)" style="display: none !important">
			<div class="toggle">
				<div class="toggle__line"></div>
				<div class="toggle__dot"></div>
			</div>
		</label>

		</div> <!-- End of Flex Container -->
	</div>

	<!-- Settings Content Area -->
    <div x-show="showSettings" class="content-settings">
        <!-- Settings Content Goes Here -->
		ok
    </div>

    <!-- Templates Content Area -->
    <div x-show="showTemplates" class="content-templates">
        <!-- Templates Content Goes Here -->
		ok2
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
			false,
			'Project / Artist', 
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M14.58,11.3a3.24,3.24,0,0,0,.71-2,3.29,3.29,0,0,0-6.58,0,3.24,3.24,0,0,0,.71,2,5,5,0,0,0-2,2.31,1,1,0,1,0,1.84.78A3,3,0,0,1,12,12.57h0a3,3,0,0,1,2.75,1.82,1,1,0,0,0,.92.61,1.09,1.09,0,0,0,.39-.08,1,1,0,0,0,.53-1.31A5,5,0,0,0,14.58,11.3ZM12,10.57h0a1.29,1.29,0,1,1,1.29-1.28A1.29,1.29,0,0,1,12,10.57ZM18,2H6A3,3,0,0,0,3,5V16a3,3,0,0,0,3,3H8.59l2.7,2.71A1,1,0,0,0,12,22a1,1,0,0,0,.65-.24L15.87,19H18a3,3,0,0,0,3-3V5A3,3,0,0,0,18,2Zm1,14a1,1,0,0,1-1,1H15.5a1,1,0,0,0-.65.24l-2.8,2.4L9.71,17.29A1,1,0,0,0,9,17H6a1,1,0,0,1-1-1V5A1,1,0,0,1,6,4H18a1,1,0,0,1,1,1Z"></path></svg>', 
			Plugin_Name_Capabilities::EDIT_PROJECT_NAME, true, $user_id); 
			?>

			<?php Plugin_Name_Builder::textarea_field('bio', 'Bio', 'Bio:', Plugin_Name_Capabilities::EDIT_BIO, true, $user_id); ?>
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
