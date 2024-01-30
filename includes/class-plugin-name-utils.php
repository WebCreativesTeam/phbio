<?php



class Plugin_Name_Utilities {

    // Data to delete after, days
    // IMPORTANT KEEP THIS SAME WITH THE SCHEDULED CLASS'S VALUE 
    const KEEP_FOR = 2;

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
            subscription has expired, but don\'t worry! We\'ll keep all your links live for <b>' . self::get_remaining_hours_for_user_backup($user_id) . '</b>. <a href="/my-account/subscription" class="font-semibold no-underline text-[#F1441E]" target="_blank">Renew now</a> to keep
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
    
    public static function get_remaining_hours_for_user_backup($user_id) {
        $backup_date = get_user_meta($user_id, '_backup_date', true);
    
        if (!$backup_date) {
            return false; // or return null, or any other indication that there's no backup date
        }
    
        $elapsed_time = current_time('timestamp') - strtotime($backup_date);
        $hours_elapsed = $elapsed_time / HOUR_IN_SECONDS;
        $remaining_hours = (self::KEEP_FOR * 24) - $hours_elapsed;
    
        return max(0, ceil($remaining_hours)) . " hours"; // ensure we don't get negative numbers
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
            $remaining_hours = 48 - $hours_elapsed;
            return max(0, floor($remaining_hours)) . " hours";
        }
    
        return max(0, floor($remaining_days)) . " days"; // ensure we don't get negative numbers
    }
    

    public static function get_user_langs() {
        // Get the current user ID
        $current_user_id = get_current_user_id();

        // Get the user meta 'pkit_lang' for the current user
        $pkit_lang_meta = get_user_meta($current_user_id, 'pkit_lang', true);

        // Check if the meta contains a comma and split by comma if it does
        if (strpos($pkit_lang_meta, ',') !== false) {
            $pkit_lang_array = explode(',', $pkit_lang_meta);
        } else {
            // If there is no comma, just create an array with the single value
            $pkit_lang_array = array($pkit_lang_meta);
        }


    //     // Query for 'hb-user-pkit' posts associated with the current user via 'associated_pkit_user' meta key
    //     $args = array(
    //         'post_type' => 'hb-user-pkit',
    //         'meta_query' => array(
    //             array(
    //                 'key' => 'associated_pkit_user',
    //                 'value' => $current_user_id,
    //                 'compare' => '='
    //             )
    //         ),
    //         'posts_per_page' => -1 // Get all matching posts
    //     );

    //     $parent_posts_query = new WP_Query($args);
    //     $parent_posts = $parent_posts_query->posts;

    //     // Step 2: For each parent post, find child posts with slugs in $pkit_lang_array
    //     $child_posts_array = array();

    //     foreach ($parent_posts as $parent_post) {
    //         foreach ($pkit_lang_array as $lang_slug) {
    //             $child_args = array(
    //                 'post_type' => 'hb-user-pkit',
    //                 'post_parent' => $parent_post->ID,
    //                 'name' => $lang_slug,
    //                 'post_status' => 'any', // Include all statuses
    //                 'posts_per_page' => -1
    //             );
                
    //             $child_posts_query = new WP_Query($child_args);
    //             $child_posts = $child_posts_query->posts;
                
    //             // Merge the found child posts into the $child_posts_array
    //             $child_posts_array = array_merge($child_posts_array, $child_posts);
    //         }
    //     }


    //    // Initialize an array to store the form objects
    //     $forms_by_language = array();

    //     // Iterate over each language in the $pkit_lang_array
    //     foreach ($pkit_lang_array as $lang) {
    //         // Retrieve the forms from the ACF repeater field on the options page
    //         $forms = get_field('pkit_fmanager', 'option');

    //         // Check if forms are retrieved successfully
    //         if ($forms) {
    //             // Iterate over each form
    //             foreach ($forms as $form) {
    //                 // Check if the current form's language matches the current language in the loop
    //                 if ($form['pkit_fmanager_language'] === $lang) {
    //                     // Check if the form is Pro or Free based on the 'Version' field
    //                     $is_pro_version = $form['pkit_fmanager_role'];

    //                     // Add the form to the $forms_by_language array with additional info if needed
    //                     $forms_by_language[$lang][] = array(
    //                         'form' => $form['pkit_fmanager_form'], // This contains the form object
    //                         'is_pro' => $is_pro_version // This is a boolean indicating Pro (true) or Free (false)
    //                     );
    //                 }
    //             }
    //         }
    //     }

        return $pkit_lang_array;
    }
    
    // public static function get_user_forms($pkit_lang_array) {
    //     $user_id = get_current_user_id();
    //     // Check if the user is Pro or Free
    //     $is_pro_version = Plugin_Name_Utilities::is_full_version($user_id);
        
    //     // Get the repeater field 'pkit_fmanager' (assuming it's an option field)
    //     $forms = get_field('pkit_fmanager', 'option');
    //     $user_forms = array();
    
    //     if ($forms) {
    //         foreach ($forms as $form) {
    //             // Check if the current row matches the user's version
    //             $is_pro_form = $form['pkit_fmanager_role'];
    
    //             // If the user is Pro, include both Pro and Free forms
    //             // If the user is Free, only include Free forms
    //             if (($is_pro_version && $is_pro_form) || (!$is_pro_version && !$is_pro_form)) {
    //                 // Check if the form language is in the user's language array
    //                 if (in_array($form['pkit_fmanager_language'], $pkit_lang_array)) {
    //                     // Add the form object to the user forms array
    //                     $user_forms[] = $form['pkit_fmanager_form'];
    //                 }
    //             }
    //         }
    //     }
        
    //     return $user_forms;
    // }

    public static function get_user_forms($pkit_lang_array) {
        $user_id = get_current_user_id();
        // Check if the user is Pro or Free
        $is_pro_version = Plugin_Name_Utilities::is_full_version($user_id);
        
        // Get the repeater field 'pkit_fmanager' (assuming it's an option field)
        $forms = get_field('pkit_fmanager', 'option');
        $sorted_user_forms = array();
    
        if ($forms) {
            // Initialize an array for each language
            foreach ($pkit_lang_array as $lang) {
                $sorted_user_forms[$lang] = array();
            }
    
            foreach ($forms as $form) {
                // Check if the current row matches the user's version
                $is_pro_form = $form['pkit_fmanager_role'];
    
                // If the user is Pro, include both Pro and Free forms
                // If the user is Free, only include Free forms
                if (($is_pro_version && $is_pro_form) || (!$is_pro_version && !$is_pro_form)) {
                    $form_lang = $form['pkit_fmanager_language'];
                    // Check if the form language is in the user's language array
                    if (in_array($form_lang, $pkit_lang_array)) {
                        // Add the form object to the corresponding language array
                        $sorted_user_forms[$form_lang][] = $form['pkit_fmanager_form'];
                    }
                }
            }
        }
    
        // Flatten the sorted array while preserving the order of languages
        $user_forms = array();
        foreach ($pkit_lang_array as $lang) {
            if (!empty($sorted_user_forms[$lang])) {
                $user_forms = array_merge($user_forms, $sorted_user_forms[$lang]);
            }
        }
        
        return $user_forms;
    }
    
    

    public static function get_language_full_name($language_code) {
        // Define the array of language codes and their full names
        $languages = array(
            'en' => 'English',
            'it' => 'Italian',
            'es' => 'Spanish',
            'de' => 'German',
            'fr' => 'French',
            'pt' => 'Portuguese',
        );
    
        // Return the full language name or the original code if not found
        return isset($languages[$language_code]) ? $languages[$language_code] : $language_code;
    }
    
    public static function get_user_langs_full_names() {
        // Get the language codes
        $lang_codes = self::get_user_langs();

        // Map each code to its full name
        $full_names = array_map('self::get_language_full_name', $lang_codes);

        print_r($full_names);
        return $full_names;
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

        return in_array('um_free-member', (array) $user->roles);
    }

    public static function is_full_version($user_id) {
        $user = get_userdata($user_id);
        
        return in_array('um_pro-member', (array) $user->roles);
    }
    public static function is_lite_verified_version($user_id) {
        $user = get_userdata($user_id);

        return in_array('um_free-verified', (array) $user->roles);
    }

    public static function convertTimeToServer($user_id, $dateTime) {
        global $wpdb;
        $serverTimeZone = $wpdb->get_var("SELECT @@system_time_zone");
        $userTimezone = get_user_meta( $user_id, '_wp_utz_opts', true );
        if (!is_array($userTimezone) || empty($userTimezone['timezone'])) $userTimezone['timezone'] = wp_timezone_string();
        try {
            $dateTime = new DateTime ($dateTime, new DateTimeZone($userTimezone['timezone']));
            $dateTime->setTimezone(new DateTimeZone($serverTimeZone));
            return $dateTime->format("Y-m-d H:i:s");
        } catch (Exception $e) {
            return false;
        }
    }
    public static function convertTimeToUser($user_id, $dateTime) {
        global $wpdb;
        $serverTimeZone = $wpdb->get_var("SELECT @@system_time_zone");
        $userTimezone = get_user_meta( $user_id, '_wp_utz_opts', true );
        if (!is_array($userTimezone) || empty($userTimezone['timezone'])) $userTimezone['timezone'] = wp_timezone_string();
        try {
            $dateTime = new DateTime ($dateTime, new DateTimeZone($serverTimeZone));
            $dateTime->setTimezone(new DateTimeZone($userTimezone['timezone']));
            return $dateTime->format("Y-m-d H:i:s");
        } catch (Exception $e) {
            return false;
        }
    }

    public static function is_user_verified($user_id) {
        $isVerified = get_field('borah__user_verified', 'user_' . $user_id);
        return $isVerified;
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
    
    public static function get_pkit_data($user_id, $lang) {
        // Initialize the version as 'free'
        $version = 'free';

        // Get the user by user ID
        $user = get_user_by('id', $user_id);

        // Check if the user exists and has the 'um_pro-member' role
        if ($user && in_array('um_pro-member', $user->roles)) {
            $version = 'pro';
        }


        // Fetch the user meta based on language argument
        $array = get_user_meta($user_id)[$lang . '_' . $version . '_fields_order'];
    
        // Check if the array is set and is not empty
        if (!isset($array[0]) || empty($array[0])) {
            return []; // Return an empty array if no data found
        }
    
        // Decode the JSON string
        $jsonDecoded = json_decode($array[0], true);
    
        $finalArray = [];
        foreach ($jsonDecoded as $blockName => $values) {
            $blockMeta = get_user_meta($user_id, '_' . $blockName, true);
            $blockPlaceholder = $blockMeta ?: $blockName;
            $blockDefinitionsACF = acf_get_field($blockPlaceholder);
            $blockLabel = $blockDefinitionsACF['label'] ?? $blockPlaceholder;
            $blockType = $blockDefinitionsACF['type'] ?? "text";
            
            
            $blockArray = [
                'block_name' => $blockName,
                'block_label' => $blockLabel,
                'type'       => $blockType,
                'fields' => []
            ];
    
            foreach ($values as $value) {
                $concatenatedKey = $blockName . '_' . $value;
                $metaKey = '_' . $concatenatedKey;
                $userMeta = get_user_meta($user_id, $metaKey, true);
                $placeholder = $userMeta ?: $metaKey;
                $fieldDefinitionsACF = acf_get_field($placeholder);
                
                $fieldLabel = $fieldDefinitionsACF['label'] ?? $placeholder;
                $fieldType = $fieldDefinitionsACF['type'] ?? "text";
                
                $userElementMeta = get_user_meta($user_id, $concatenatedKey, true) ?: '';

                if($fieldType == 'oembed') {
                    $userElementMeta = get_field($concatenatedKey, 'user_' . $user_id);
                }
                $blockArray['fields'][] = [$fieldLabel, $fieldType, $userElementMeta];
            }
    
            if (!empty($blockArray['fields'])) {
                $finalArray[] = $blockArray;
            }
        }
    
        return $finalArray;
    }
    public static function get_pkit_blocks() {
        // Retrieve the repeater field data from the options page
        $blockConfigurations = get_field('block_configuration', 'option');
        
        // Initialize an array to store the instances
        $instances = [];
    
        // Check if the repeater field has rows of data
        if ($blockConfigurations) {
            // Loop through each row of the repeater field
            foreach ($blockConfigurations as $configuration) {
                // Add the 'name' and 'key' of each row to the instances array
                $instances[] = [
                    'name' => $configuration['name'],
                    'key' => $configuration['key']
                ];
            }
        }
    
        return $instances;
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



            if ($name === "links_list") {
                $lists = $_POST['links_list'];
                $decodedString = urldecode($lists);
                $linksArray = json_decode($decodedString, true);
            
                /** Re-index to fix any potential issues */
                $arr = array_values(is_array($linksArray) ? $linksArray : []);
            
                // Convert the array to a string and log it

                error_log("Hey");
                error_log(print_r($arr, true));
            }
            
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
            // Sync with hb-user-pkit cpt
            if ($name === "pkit_username") {
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
                        'post_title' => $posted_value, // Update the title of the post
                        'post_status' => 'publish'
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
            }

            if ($name === "pkit_lang") { 
            
               
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

                // Use get_posts and directly retrieve the first post ID
                $post_ids = get_posts($args);
                
                if (!empty($post_ids)) {
                    $post_id = $post_ids[0]->ID;  // Get the first post ID
                } else {
                    return;
                }

                $langs = explode(',', $posted_value);

                
                foreach ($langs as $lang) {
                    $args = array(
                        'post_type' => 'hb-user-pkit',
                        'post_parent' => $post_id,
                        'name' => $lang,
                        'post_status' => array('publish')  
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
                            'post_status' => 'publish'
                        ));
                    }
                }

                wp_reset_postdata();
            }
        }
    
        // Retrieve the updated value for the field
         return get_user_meta($user_id, $name, true);
    }
    
    public static function is_empty_table($id) {
        ?>
        <div class='table-wrapper table-is-empty' data-wptable='<?php echo $id; ?>'>        
        <div class='empty-analytic'>
        <svg xmlns='http://www.w3.org/2000/svg' fill='currentColor' height='1em' viewBox='0 0 512 512'><path d='M24 32c13.3 0 24 10.7 24 24V408c0 13.3 10.7 24 24 24H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H72c-39.8 0-72-32.2-72-72V56C0 42.7 10.7 32 24 32zM128 136c0-13.3 10.7-24 24-24l208 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-208 0c-13.3 0-24-10.7-24-24zm24 72H296c13.3 0 24 10.7 24 24s-10.7 24-24 24H152c-13.3 0-24-10.7-24-24s10.7-24 24-24zm0 96H424c13.3 0 24 10.7 24 24s-10.7 24-24 24H152c-13.3 0-24-10.7-24-24s10.7-24 24-24z'/></svg>
        <div class='analytic-empty-title'>No data available</div>
        <div class='analytic-empty-text'>There's no activity in the selected time range.</div>
        </div>
        </div>
        <?php
    }
    public static function is_empty_chart($type, $tableID, $chartID) {
        // Define SVG paths for different types
        $svg_paths = [
            'pie' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="1em" viewBox="0 0 576 512"><path d="M304 240V16.6c0-9 7-16.6 16-16.6C443.7 0 544 100.3 544 224c0 9-7.6 16-16.6 16H304zM32 272C32 150.7 122.1 50.3 239 34.3c9.2-1.3 17 6.1 17 15.4V288L412.5 444.5c6.7 6.7 6.2 17.7-1.5 23.1C371.8 495.6 323.8 512 272 512C139.5 512 32 404.6 32 272zm526.4 16c9.3 0 16.6 7.8 15.4 17c-7.7 55.9-34.6 105.6-73.9 142.3c-6 5.6-15.4 5.2-21.2-.7L320 288H558.4z"/></svg>',
            'area' => '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" height="1em" viewBox="0 0 512 512"><path d="M64 64c0-17.7-14.3-32-32-32S0 46.3 0 64V400c0 44.2 35.8 80 80 80H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H80c-8.8 0-16-7.2-16-16V64zm96 288H448c17.7 0 32-14.3 32-32V251.8c0-7.6-2.7-15-7.7-20.8l-65.8-76.8c-12.1-14.2-33.7-15-46.9-1.8l-21 21c-10 10-26.4 9.2-35.4-1.6l-39.2-47c-12.6-15.1-35.7-15.4-48.7-.6L135.9 215c-5.1 5.8-7.9 13.3-7.9 21.1v84c0 17.7 14.3 32 32 32z"/></svg>'
        ];
    
        // Choose the SVG path based on the type
        $svg_path = isset($svg_paths[$type]) ? $svg_paths[$type] : $svg_paths['type1']; // default to 'type1' if the type is not defined
    
        ?>
        <div class='table-wrapper table-is-empty' data-wptable='<?php echo $tableID; ?>' data-wpchart='<?php echo $chartID; ?>'>        
        <div class='empty-analytic'>
        <?php echo $svg_path; ?>
        <div class='analytic-empty-title'>No data available</div>
        <div class='analytic-empty-text'>There's no activity in the selected time range.</div>
        </div>
        </div>
        <?php
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