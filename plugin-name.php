<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress or ClassicPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       My Plugin Name
 * Plugin URI:        https://plugin.com/plugin-name-uri/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Your Name or Your Company Name
 * Requires at least: X.X
 * Requires PHP:      X.X
 * Tested up to:      X.X
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}





/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * Define the Plugin basename
 */
define( 'PLUGIN_NAME_BASE_NAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 *
 * This action is documented in includes/class-plugin-name-activator.php
 * Full security checks are performed inside the class.
 */
function pfx_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
	Plugin_Name_Activator::create_link_clicks_table();
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 *
 * This action is documented in includes/class-plugin-name-deactivator.php
 * Full security checks are performed inside the class.
 */
function pfx_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'pfx_activate' );
register_deactivation_hook( __FILE__, 'pfx_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * Generally you will want to hook this function, instead of calling it globally.
 * However since the purpose of your plugin is not known until you write it, we include the function globally.
 *
 * @since    1.0.0
 */
function pfx_run() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
pfx_run();


function display_user_links_shortcode($atts) {
    // Extract the 'username' from the current request URI
    $request_uri = $_SERVER['REQUEST_URI'];
    $path_parts = explode('/', trim($request_uri, '/'));
    if (count($path_parts) < 2 || $path_parts[0] !== 'bio') {
        return 'Data will be shown in the user bio page';
    }
    $username_meta_value = $path_parts[1];

    // Try to get a user by the custom meta field
    $args = array(
        'meta_key'   => 'username',  // Adjust this if the meta field key is different
        'meta_value' => $username_meta_value,
        'number'     => 1
    );
    $users = get_users($args);
    
    // If no user is found, return a message
    if (empty($users)) {
        return 'No user found for this page.';
    }

    $user = $users[0];

    // Fetch the links from the user meta
    $value = get_user_meta($user->ID, 'links_list', true);
    $decodedString = urldecode($value);
    $linksArray = json_decode($decodedString, true);

	print_r(Plugin_Name_Analytics::get_top_performing_link($user->ID));
    /** Re-index to fix any potential indexing issues */
    $links_list = array_values(is_array($linksArray) ? $linksArray : []);

    // Initialize output
    $output = '<ul class="user-links-list">';

    // Loop through each link and add to output
    foreach ($links_list as $link_data) {
        $url = isset($link_data['text']) ? $link_data['text'] : '';
        $title = isset($link_data['title']) && $link_data['title'] ? $link_data['title'] : $url;
        
        $output .= '<li><a href="' . esc_url($url) . '" class="tracked-link" data-user-id="' . esc_attr($user->ID) . '">' . esc_html($title) . '</a></li>';
    }

    $output .= '</ul>';

    return $output;
}
add_shortcode('display_user_links', 'display_user_links_shortcode');

