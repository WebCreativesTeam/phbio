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
            $type = $_POST['type'];

            // Check if the CPT with this permalink exists
            $args = array(
                'name'        => $username,
                'post_type'   => $type,
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
		
		public function handle_remove_gallery_image() {

			$index = isset($_POST['index']) ? intval($_POST['index']) : -1;
			$name = 'img_gallery_urls'; // Replace with your actual field name
			
			$user_id = get_current_user_id();
			$image_urls = get_user_meta($user_id, $name, true);
			$image_urls = $image_urls ? json_decode($image_urls, true) : array();
			if($index >= 0 && $index < count($image_urls)) {
				unset($image_urls[$index]);
				$image_urls = array_values($image_urls); // Re-index the array
				update_user_meta($user_id, $name, json_encode($image_urls));
				wp_send_json_success();
			} else {
				wp_send_json_error();
			}
		}

		public function handle_image_upload() {
			$response = ['success' => false, 'data' => 'Unknown Error'];
			$field_name = 'file'; // Adjust as needed
			$allowed_types = ['image/jpeg', 'image/png', 'image/tiff'];
			$max_size = 2 * 1024 * 1024; // 2 MB
			
			$target_user_id = get_current_user_id(); // Adjust as needed
			
			if (!isset($_FILES[$field_name])) {
				$response['data'] = 'No file uploaded.';
				wp_send_json($response);
			}
			
			$file = $_FILES[$field_name];
			
			if (!in_array($file['type'], $allowed_types)) {
				$response['data'] = 'Invalid file type. Only JPG, JPEG, PNG, and TIFF are allowed.';
				wp_send_json($response);
			}
			
			if ($file['size'] > $max_size) {
				$response['data'] = 'File size exceeded. Maximum file size is ' . ($max_size / 1024) . 'KB.';
				wp_send_json($response);
			}
			
			$uploads_dir = wp_upload_dir();
			$custom_dir = $uploads_dir['basedir'] . '/ph-bio';
			
			if (!file_exists($custom_dir)) {
				wp_mkdir_p($custom_dir);
			}
			
			$timestamp = time();
			$new_filename = $field_name . '_user_' . $target_user_id . '_' . $timestamp . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
			$target_file_path = $custom_dir . '/' . $new_filename;
			
			if (move_uploaded_file($file['tmp_name'], $target_file_path)) {
				$file_url = $uploads_dir['baseurl'] . '/ph-bio/' . $new_filename;
				update_user_meta($target_user_id, $field_name, $file_url);
				$response['success'] = true;
				$response['data'] = ['fileUrl' => $file_url, 'message' => 'Your changes have been saved'];
			} else {
				$response['data'] = 'Failed to upload image.';
			}
			
			wp_send_json($response);
		}

		
		public function handle_record_page_view() {
			global $wpdb;
			
			// Ensure post_id is provided and is a number
			if (!isset($_POST['post_id']) || !is_numeric($_POST['post_id'])) {
				wp_send_json(['success' => false, 'error' => 'Invalid post ID']);
				return;
			}
			
			$post_id = intval($_POST['post_id']);  // Sanitize as integer
			$country = $_POST['country'];  
			
			// Record the view in your database
			$insert_result = $wpdb->insert(
				"{$wpdb->prefix}page_views",
				[
					'post_id' => $post_id,
					'viewed_at' => current_time('mysql'),
					'viewed_country' => $country  
				],
				['%d', '%s', '%s']  
			);
			
			
			// Check whether the insert was successful
			if($insert_result) {
				$response['success'] = true;
			} else {
				$response['success'] = false;
				$response['error'] = $wpdb->last_error;
			}
		
			$response['post_id'] = $post_id;
		
			wp_send_json($response);
		}
		
	
		
		
		
		

	}

}