<?php



class Plugin_Name_Utilities {

    // Data to delete after, days
    // IMPORTANT KEEP THIS SAME WITH THE SCHEDULED CLASS'S VALUE 
    const KEEP_FOR = 1;

    public static function is_not_included_field($label) {
        return '<div class="mt-10 warning-message">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 warning-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20Zm0-8.5a1,1,0,0,0-1,1v3a1,1,0,0,0,2,0v-3A1,1,0,0,0,12,11.5Zm0-4a1.25,1.25,0,1,0,1.25,1.25A1.25,1.25,0,0,0,12,7.5Z"></path></svg>
                               <span> Your current template does not include <b>' . $label .
                            '</b></span></div>';
    }

    public static function current_user_has_backup_links() {
        $user_id = get_current_user_id();

        if(self::user_has_backup($user_id) && self::is_lite_version($user_id)) {
            return '<div class="warning-message">
            <svg xmlns="http://www.w3.org/2000/svg" class="warning-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20Zm0-8.5a1,1,0,0,0-1,1v3a1,1,0,0,0,2,0v-3A1,1,0,0,0,12,11.5Zm0-4a1.25,1.25,0,1,0,1.25,1.25A1.25,1.25,0,0,0,12,7.5Z"></path></svg>
            <span>Your
            subscription has expired, but don\'t worry! We\'ll keep all your links live for <b>' . self::get_remaining_days_for_user_backup($user_id) . '</b>. <a href="/my-account/subscription" class="font-semibold no-underline" target="__blank">Renew now</a> to keep
            them active. </span></div>';
        } else {
            return '';
        }
    }


    public static function user_has_backup($user_id) {

        
        
        // Check if the _backup_meta_field exists for the user
        $backup_meta = get_user_meta($user_id, '_backup_meta_field', true);
        
        // Return true if it exists, otherwise return false
        return !empty($backup_meta);
    }
    
    public static function get_remaining_days_for_user_backup($user_id) {
        $backup_date = get_user_meta($user_id, '_backup_date', true);
    
        if (!$backup_date) {
            return false; // or return null, or any other indication that there's no backup date
        }
    
        $elapsed_time = current_time('timestamp') - strtotime($backup_date);
        $days_elapsed = $elapsed_time / DAY_IN_SECONDS;
        $remaining_days = self::KEEP_FOR - $days_elapsed;
    
        // If no days left, calculate hours
        if (floor($remaining_days) == 0) {
            $hours_elapsed = $elapsed_time / HOUR_IN_SECONDS;
            $remaining_hours = 24 - $hours_elapsed;
            return max(0, floor($remaining_hours)) . " hours";
        }
    
        return max(0, floor($remaining_days)) . " days"; // ensure we don't get negative numbers
    }
    
    
    
    public static function check_user_capability($capability) {
        if (!current_user_can($capability)) {
            return false; // Capability not met
        }
        return true; // Capability met
    }

    public static function get_user_links() {
        $user_id = get_current_user_id();

        // Get Links
        $value = get_user_meta($user_id, 'links_list', true);

        $decodedString = urldecode($value);
        $linksArray = json_decode($decodedString, true);
    
        /** Re-index to fix any potential issues */
        $arr = array_values(is_array($linksArray) ? $linksArray : []);

        return $arr;
    }

    public static function is_lite_version($user_id) {
        $user = get_userdata($user_id);

        return in_array('lite-version', (array) $user->roles);
    }

    public static function is_full_version($user_id) {
        $user = get_userdata($user_id);
        
        return in_array('full-version', $user->roles);
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
            // Sync with hb-user-profile cpt
            if ($name === "pkit_username") {
                // Search for a hb-user-profile post associated with this user
                $args = array(
                    'post_type' => 'hb-user-pkit',
                    'meta_query' => array(
                        array(
                            'key' => 'associated_pkit_user',
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
                        'post_type' => 'hb-user-pkit',
                        'post_status' => 'publish',
                        'post_name' => $posted_value, // Set the slug (URL) of the post
                        'post_title' => $posted_value, // Set the title of the post
                        'meta_input' => array(
                            'associated_pkit_user' => $user_id
                        )
                    ));
                }
                // Reset the WP_Query
                wp_reset_postdata();


            
                // Create or update subpages
                $xyz = get_user_meta($user_id, 'pkit_lang', true);
                $langs = explode(',', $xyz);
            
                foreach ($langs as $lang) {
                    $args = array(
                        'post_type' => 'hb-user-pkit',
                        'post_parent' => $post_id,
                        'name' => $lang,
                        'post_status' => array('publish', 'draft')  // Include drafts in the search
                    );
                    $subpage_query = new WP_Query($args);
                    
                    if ($subpage_query->have_posts()) {
                        $subpage_query->the_post();
                        wp_update_post(array(
                            'ID' => get_the_ID(),
                            'post_title' => ucfirst($lang),
                            'post_status' => 'publish' // Ensure it's published
                        ));
                    } else {
                        wp_insert_post(array(
                            'post_type' => 'hb-user-pkit',
                            'post_status' => 'publish',
                            'post_parent' => $post_id,
                            'post_name' => $lang, 
                            'post_title' => ucfirst($lang)
                        ));
                    }
                    // Reset the WP_Query
                    wp_reset_postdata();
                }
            
                // Unpublish other subpages
                $args = array(
                    'post_type' => 'hb-user-pkit',
                    'post_parent' => $post_id,
                    'numberposts' => -1,
                    'post_status' => 'publish'  // Only consider published subpages
                );
                $all_subpages_query = get_posts($args);
            
                foreach ($all_subpages_query as $subpage) {
                    if (!in_array($subpage->post_name, $langs)) {
                        wp_update_post(array(
                            'ID' => $subpage->ID,
                            'post_status' => 'draft'
                        ));
                    }
                }
            }
        }
    
        // Retrieve the updated value for the field
         return get_user_meta($user_id, $name, true);
    }
    

    public static function get_unique_dynamic_tag_names_from_template($template_id) {
        // Retrieve the template data based on the template_id
        $template_data = \Elementor\Plugin::$instance->db->get_builder($template_id);
        
        $dynamic_tags = [];
    
        // Recursive function to traverse the nested array
        function traverse($item, &$tags) {
            if (is_array($item)) {
                // Check for the __dynamic__ key
                if (isset($item['__dynamic__']) && is_array($item['__dynamic__'])) {
                    foreach ($item['__dynamic__'] as $tag) {
                        // Use regex to extract the name attribute
                        if (preg_match('/name="([^"]+)"/', $tag, $matches)) {
                            $tagName = $matches[1];
                            if ($tagName && !in_array($tagName, $tags)) {
                                $tags[] = $tagName;
                            }
                        }
                    }
                }
    
                // Traverse deeper
                foreach ($item as $subitem) {
                    traverse($subitem, $tags);
                }
            }
        }
    
        traverse($template_data, $dynamic_tags);
    
        return $dynamic_tags;
    }
    

  
    
    

    
}
    
new Plugin_Name_Utilities();