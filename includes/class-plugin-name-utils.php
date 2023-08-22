<?php



class Plugin_Name_Utilities {

    public static function check_user_capability($capability) {
        if (!current_user_can($capability)) {
            return false; // Capability not met
        }
        return true; // Capability met
    }

    public static function is_lite_version($user_id) {
        $user = get_userdata($user_id);
        return in_array('lite-version', (array) $user->roles);
    }

    public static function is_full_version($user_id) {
        $user = get_userdata($user_id);

        return in_array('full-version', (array) $user->roles);
    }

    public static function get_user_maxLinks($user_id) {
      
        // Check if user is an admin
        if (user_can($user_id, 'manage_options')) {
            return 999; // Virtually no limit for an admin
        }
    
        // Default maxLinks
        $maxLinks = 5;
        
        // Check for full version and retrieve associated maxLinks if available
        if (self::is_full_version($user_id)) {
            $full_version_limit = get_user_meta(1, 'limit_links_full', true);
           
            if ($full_version_limit) {
                return (int)$full_version_limit;
            }
        }
        
        // Check for lite version and retrieve associated maxLinks if available
        elseif (self::is_lite_version($user_id)) {
            $lite_version_limit = get_user_meta(1, 'limit_links_lite', true);
            if ($lite_version_limit) {
                return (int)$lite_version_limit;
            }
        }
    
        // Return default maxLinks if no other values found
        return $maxLinks;
    }
    
    

    public static function handle_user_meta($name, $capability, $target_user_id = null) {
        // If a target user ID isn't provided, use the current user's ID
        $user_id = $target_user_id ? $target_user_id : get_current_user_id();
    
       
        
        // Handle file upload fields
        if (substr($name, -4) === '_url' && isset($_FILES[$name]) && self::check_user_capability($capability)) {
            return get_user_meta($user_id, $name, true);
        }

        
        if (substr($name, -5) === '_list' && isset($_POST[$name]) && self::check_user_capability($capability)) {
            $posted_array = $_POST[$name];  // Assume that this is a JSON string

            update_user_meta($user_id, $name, $posted_array);
        
            // Return the updated value
            $data = get_user_meta($user_id, $name, true);
            
            
            return $data;
        }
        

            
        // If the field has been posted and the capability is met, save it
        if (isset($_POST[$name]) && self::check_user_capability($capability)) {
            if ($name === "username") {
                $posted_value = sanitize_title($_POST[$name]);  // Use sanitize_title for URL safe strings
            } else {
                $posted_value = sanitize_text_field($_POST[$name]);  // Always sanitize input!
            }
            update_user_meta($user_id, $name, $posted_value);


            // Sync with hb-user-profile cpt
    if ($name === "username") {
        // Search for a hb-user-profile post associated with this user
        $args = array(
            'post_type' => 'hb-user-profile',
            'meta_query' => array(
                array(
                    'key' => 'associated_user',
                    'value' => $user_id,
                    'compare' => '='
                )
            )
        );
        $query = new WP_Query($args);

        // If there's an existing post, update it
        if ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            wp_update_post(array(
                'ID' => $post_id,
                'post_name' => $posted_value, // Update the slug (URL) of the post
                'post_title' => $posted_value // Update the title of the post
            ));
        } else {
            // If not, create a new one
            $post_id = wp_insert_post(array(
                'post_type' => 'hb-user-profile',
                'post_status' => 'publish',
                'post_name' => $posted_value, // Set the slug (URL) of the post
                'post_title' => $posted_value, // Set the title of the post
                'meta_input' => array(
                    'associated_user' => $user_id
                )
            ));
        }
        // Reset the WP_Query
        wp_reset_postdata();
    }
        }
    
        // Retrieve the updated value for the field
         return get_user_meta($user_id, $name, true);
    }
    

    public static function delete_old_files_function($filename) {
        $uploads_dir = wp_upload_dir();
        $ph_bio_dir = $uploads_dir['basedir'] . '/ph-bio';
        
        if (file_exists($ph_bio_dir)) {
            $files = glob($ph_bio_dir . '/' . $filename); // Get all files with the specified name
            usort($files, function($a, $b) {
                return filemtime($a) < filemtime($b); // Sort files by modified time
            });
    
            // Remove the latest file (the first one in the sorted list)
            array_shift($files);
    
            // Delete the rest of the files
            foreach($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
    
    

    
}
    
new Plugin_Name_Utilities();