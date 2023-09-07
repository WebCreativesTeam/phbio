<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Plugin_Name {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The unique prefix of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_prefix    The string used to uniquely prefix technical functions of this plugin.
	 */
	protected $plugin_prefix;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {

			$this->version = PLUGIN_NAME_VERSION;

		} else {

			$this->version = '1.0.0';

		}

		$this->plugin_name   = 'plugin-name';
		$this->plugin_prefix = 'pfx_';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-utils.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-capabilities.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-builder.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-ajax.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-analytics.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-dashboard.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-name-elementor-integration.php';
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/queries/query_links_list.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-plugin-name-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-plugin-name-public.php';

		$this->loader = new Plugin_Name_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Plugin_Name_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Plugin_Name_Admin( $this->get_plugin_name(), $this->get_plugin_prefix(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_admin, 'role_manager' );
		$this->loader->add_action('set_user_role', $plugin_admin, 'role_change', 10, 3);



		$this->loader->add_action( 'init', $plugin_admin, 'template_manager' );
		$this->loader->add_action( 'init', $plugin_admin, 'user_profile_manager' );
		$this->loader->add_action( 'the_content', $plugin_admin, 'render_user_profile_elementor_content' );
		$this->loader->add_action( 'template_redirect', $plugin_admin, 'user_profile_private_redirection' );
		
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'template_version_mb' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'template_version_field_save' );
		// $this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );

		$plugin_settings = new Plugin_Name_Settings();
		$plugin_dashboard = new Plugin_Name_Dashboard();

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'disable_notices', 0 );
		$this->loader->add_action( 'all_admin_notices', $plugin_admin, 'disable_notices', 0 );
		$this->loader->add_filter( 'manage_users_columns', $plugin_admin, 'user_column_button');
		$this->loader->add_filter( 'manage_users_custom_column', $plugin_admin, 'user_column_button_cb', 10, 3);
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$plugin_ajax = new Plugin_Ajax( $this->get_plugin_name() );
		$plugin_analytics = new Plugin_Name_Analytics();

		$this->loader->add_action( 'wp_ajax_nopriv_callback', $plugin_ajax, 'callback' );
		$this->loader->add_action( 'wp_ajax_callback', $plugin_ajax, 'callback' );

		$this->loader->add_action( 'wp_ajax_handle_link_click', $plugin_ajax, 'handle_link_click' );
		$this->loader->add_action( 'wp_ajax_nopriv_handle_link_click', $plugin_ajax, 'handle_link_click' );
	 
		$el_integrate = new Plugin_Name_Elementor_Integration();
		$this->loader->add_action( 'elementor/dynamic_tags/register', $el_integrate, 'add_group' );
		$this->loader->add_action( 'elementor/dynamic_tags/register', $el_integrate, 'register_tags' );
		$this->loader->add_action( 'elementor/widgets/register', $el_integrate, 'register_widgets' );
		
		$el_links_list_query = new Plugin_Query_Links_List();
		$this->loader->add_action( 'elementor/query/links_list', $el_links_list_query, 'query' );
		// add_action('elementor/query/links_list', 'links_list');


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Plugin_Name_Public( $this->get_plugin_name(), $this->get_plugin_prefix(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Shortcode name must be the same as in shortcode_atts() third parameter.
		$this->loader->add_shortcode( $this->get_plugin_prefix() . 'shortcode', $plugin_public, 'pfx_shortcode_func' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The unique prefix of the plugin used to uniquely prefix technical functions.
	 *
	 * @since     1.0.0
	 * @return    string    The prefix of the plugin.
	 */
	public function get_plugin_prefix() {
		return $this->plugin_prefix;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
