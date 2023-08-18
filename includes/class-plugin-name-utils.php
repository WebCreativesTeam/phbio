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
        }
    
        // Retrieve the updated value for the field
         return get_user_meta($user_id, $name, true);
    }
    

    

    
}
    
new Plugin_Name_Utilities();