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



// remove_role( 'um_pro-member' );
// remove_role( 'um_free-member' );

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
	Plugin_Name_Activator::create_link_manager_table();
	Plugin_Name_Activator::create_social_link_manager_table();
	Plugin_Name_Activator::create_link_clicks_table();
	Plugin_Name_Activator::create_page_views_table();
	Plugin_Name_Activator::create_social_link_clicks_table();
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



function remove_menu_items_for_role() {
    if ( current_user_can('um_free-member') || current_user_can('um_pro-member') ) {
        global $menu;
        $menu = array(); // This will remove all menu items
    }
}
add_action('admin_menu', 'remove_menu_items_for_role', 100);


// add_action( 'load-profile.php', function() {
//     if( current_user_can('um_free-member') || current_user_can('um_pro-member') )
//         exit( wp_safe_redirect( admin_url('?page=profile-editor') ) );
// } );


// function block_subscriber_access_to_dashboard() {
//     $current_screen = get_current_screen();
//     if (($current_screen->base === 'dashboard' && current_user_can('um_free-member')) ||( $current_screen->base === 'dashboard' && current_user_can('um_pro-member'))) {
//         wp_redirect(admin_url('?page=profile-editor'));
//         exit;
//     }
// }
// add_action('current_screen', 'block_subscriber_access_to_dashboard');


// function redirect_subscribers_on_login($redirect_to, $request, $user) {
//     // Check if the user is a subscriber
//     if (isset($user->roles) && is_array($user->roles) && in_array('um_free-member', $user->roles)) {
//         // Redirect to the user's profile page
//         return admin_url('?page=profile-editor');
//     }
//     if (isset($user->roles) && is_array($user->roles) && in_array('um_pro-member', $user->roles)) {
//         // Redirect to the user's profile page
//         return admin_url('?page=profile-editor');
//     }
//     return $redirect_to;
// }
// add_filter('login_redirect', 'redirect_subscribers_on_login', 10, 3);

function remove_entire_admin_sidebar_for_role() {
    if (  current_user_can('um_pro-member') || current_user_can('um_free-member') ) {
        echo '<style>
            #adminmenumain, #adminmenu { display: none !important; }
            #wpcontent, #wpfooter { margin-left: 0 !important; }
            #wpwrap { background-color: rgb(229 231 235) !important; }
            #wpadminbar { display: none; }
        </style>';
    }
}
add_action('admin_head', 'remove_entire_admin_sidebar_for_role');


function remove_all_admin_footer_actions_for_role() {
    if ( current_user_can('um_pro-member') || current_user_can('um_free-member') ) {
        remove_all_actions('in_admin_footer');
    }
}
add_action('admin_footer', 'remove_all_admin_footer_actions_for_role', 1);


function remove_admin_footer_version_for_role($default_version) {
    if ( current_user_can('um_pro-member') || current_user_can('um_free-member') ) {
        return '';
    }
    return $default_version;
}
add_filter('update_footer', 'remove_admin_footer_version_for_role', 11);

function remove_admin_footer_text_for_role($default_text) {
    if ( current_user_can('um_pro-member') || current_user_can('um_free-member') ) {
        return '';
    }
    return $default_text;
}
add_filter('admin_footer_text', 'remove_admin_footer_text_for_role');




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

function display_user_links_shortcode_listing($atts) {
    return Plugin_Name_Utilities::get_user_links();
    
    
}
add_shortcode('display_user_links_listing', 'display_user_links_shortcode_listing');


add_action('updated_user_meta', 'sync_links_list_to_phbio_links', 10, 4);
add_action('added_user_meta', 'sync_links_list_to_phbio_links', 10, 4);

function sync_links_list_to_phbio_links($meta_id, $user_id, $meta_key, $_meta_value) {
    if ($meta_key === 'links_list') {
        $value = get_user_meta($user_id, 'links_list', true);
        $decodedString = urldecode($value);
        $linksArray = json_decode($decodedString, true);

        /** Re-index to fix any potential issues */
        $arr = array_values(is_array($linksArray) ? $linksArray : []);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'phbio_links';
        

        // Step 1: Collect all IDs from the Application
        $app_ids = array_column($arr, 'id');

        // Step 2: Fetch all IDs from the Database
        $db_ids_result = $wpdb->get_results($wpdb->prepare("SELECT id FROM $table_name WHERE user_id = %d", $user_id), ARRAY_A);
        $db_ids = array_column($db_ids_result, 'id');

        // Step 3: Find the Difference
        $ids_to_delete = array_diff($db_ids, $app_ids);

        // Step 4: Delete Rows
        foreach($ids_to_delete as $id) {
            $wpdb->delete($table_name, array('id' => $id, 'user_id' => $user_id));
        }

        foreach ($arr as $link) {
            $entry = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d AND user_id = %d", $link['id'], $user_id));
            
            if ($entry) {
                // Update the entry
                $wpdb->update(
                    $table_name,
                    array(
                        'title' => $link['title'],
                        'text' => $link['text'],
                        'isHidden' => $link['isHidden'],
                        'highlight' => $link['highlight'],
                        'start_time' => $link['start_time'],
                        'end_time' => $link['end_time'],
                        'isScheduled' => $link['isScheduled'],
                        'imageFile' => $link['imageFile']
                    ),
                    array('id' => $link['id'], 'user_id' => $user_id)  // WHERE clause
                );
            } else {
                // Insert a new entry
                $wpdb->insert(
                    $table_name,
                    array(
                        'user_id' => $user_id,
                        'id' => $link['id'],
                        'title' => $link['title'],
                        'text' => $link['text'],
                        'isHidden' => $link['isHidden'],
                        'highlight' => $link['highlight'],
                        'start_time' => $link['start_time'],
                        'end_time' => $link['end_time'],
                        'isScheduled' => $link['isScheduled'],
                        'imageFile' => $link['imageFile']
                    )
                );
            }
        }
    }
    if ($meta_key === 'social_links_list') {
        $value = get_user_meta($user_id, 'social_links_list', true);
        $decodedString = urldecode($value);
        $linksArray = json_decode($decodedString, true);

        /** Re-index to fix any potential issues */
        $arr = array_values(is_array($linksArray) ? $linksArray : []);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'phbio_social_links';
        
         // Step 1: Collect all IDs from the Application
         $app_ids = array_column($arr, 'id');

         // Step 2: Fetch all IDs from the Database
         $db_ids_result = $wpdb->get_results($wpdb->prepare("SELECT id FROM $table_name WHERE user_id = %d", $user_id), ARRAY_A);
         $db_ids = array_column($db_ids_result, 'id');
 
         // Step 3: Find the Difference
         $ids_to_delete = array_diff($db_ids, $app_ids);
 
         // Step 4: Delete Rows
         foreach($ids_to_delete as $id) {
             $wpdb->delete($table_name, array('id' => $id, 'user_id' => $user_id));
         }
         
        foreach ($arr as $link) {
            $entry = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d AND user_id = %d", $link['id'], $user_id));
            
            if ($entry) {
                // Update the entry
                $wpdb->update(
                    $table_name,
                    array(
                        'title' => $link['title'],
                        'text' => $link['text'],
                        
                    ),
                    array('id' => $link['id'], 'user_id' => $user_id)  // WHERE clause
                );
            } else {
                // Insert a new entry
                $wpdb->insert(
                    $table_name,
                    array(
                        'user_id' => $user_id,
                        'id' => $link['id'],
                        'title' => $link['title'],
                        'text' => $link['text'],
                        
                    )
                );
            }
        }
    }
}
