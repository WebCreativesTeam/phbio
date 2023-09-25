<?php

/**
 * The public-facing AJAX functionality.
 *
 * Creates the various functions used for AJAX on the front-end.
 *
 * @package    Plugin
 * @subpackage Plugin/public
 * @author     Plugin_Author <email@example.com>
 */

if( ! class_exists( 'Plugin_Ajax' ) ){

	class Plugin_Ajax {

		/**
		 * An example AJAX callback.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function callback() {

			// Check the nonce for permission.
			if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'plugin' ) ) {
				die( 'Permission Denied' );
			}

            $username = $_POST['username'];

            // Check if the CPT with this permalink exists
            $args = array(
                'name'        => $username,
                'post_type'   => 'hb-user-profile',
                'post_status' => 'publish',
                'numberposts' => 1
            );
            $existing_posts = get_posts($args);

            if (count($existing_posts)) {
                echo json_encode(array('available' => false));
            } else {
                echo json_encode(array('available' => true));
            }


			
			// Terminate the callback and return a proper response.
			wp_die();
            exit();

		}

		public function handle_link_click() {
			global $wpdb;
		
			if (!isset($_POST['link'])) {
				wp_send_json_error('Invalid request');
				return;
			}
		
			$link = $_POST['link'];
			$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
		
			$table_name = $wpdb->prefix . 'link_clicks';
		
			// Insert a new row for the click
			$wpdb->insert(
				$table_name,
				array(
					'user_id' => $user_id,
					'link' => $link,
					'clicked_at' => current_time('mysql')  // Using WordPress function to get current server time
				)
			);
		
			wp_send_json_success('Click counted');
		}
		public function handle_social_link_click() {
			global $wpdb;
		
			if (!isset($_POST['link'])) {
				wp_send_json_error('Invalid request');
				return;
			}
		
			$link = $_POST['link'];
			$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
		
			$table_name = $wpdb->prefix . 'social_link_clicks';
		
			// Insert a new row for the click
			$wpdb->insert(
				$table_name,
				array(
					'user_id' => $user_id,
					'link' => $link,
					'clicked_at' => current_time('mysql')  // Using WordPress function to get current server time
				)
			);
		
			wp_send_json_success('Click counted');
		}
		
		
		

	}

}