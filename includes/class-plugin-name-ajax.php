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

	}

}