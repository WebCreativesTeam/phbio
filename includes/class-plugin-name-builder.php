<?php

class Plugin_Name_Builder {
    
    const ERROR_MSG= "This functionality is only available for the Full Version";

    public static function text_field($name, $value, $label, $capability, $target_user_id) {
        $value = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);

        // Display the field
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>';
            echo '<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($value) . '" disabled />';
            echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
        } else {
            // Render the enabled input field if the capability is met
            echo '<label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>';
            echo '<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($value) . '" />';
        }
    }

    
    public static function textarea_field($name, $value, $label, $capability, $target_user_id) {
        $value = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
    
        // Display the field
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>';
            echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" disabled>' . esc_textarea($value) . '</textarea>';
            echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
        } else {
            echo '<label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>';
            echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($name) . '">' . esc_textarea($value) . '</textarea>';
        }
    }
    
    
    public static function checkbox_field($name, $label, $capability, $target_user_id) {
        $value = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
    
        // Display the checkbox
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<label for="' . esc_attr($name) . '">';
            echo '<input type="checkbox" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" ' . checked($value, '1', false) . ' disabled />';
            echo esc_html($label) . '</label>';
            echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
        } else {
            echo '<label for="' . esc_attr($name) . '">';
            echo '<input type="checkbox" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" ' . checked($value, '1', false) . ' />';
            echo esc_html($label) . '</label>';
        }
    }
    
    
    
    public static function select_field($name, $options, $selected, $label, $capability, $target_user_id) {
        $value = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
        // Display the select field
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>';
            echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" disabled>';
            foreach ($options as $value => $option_label) {
                echo '<option value="' . esc_attr($value) . '" ' . selected($value, $selected, false) . '>' . esc_html($option_label) . '</option>';
            }
            echo '</select>';
            echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
        } else {
            echo '<label for="' . esc_attr($name) . '">' . esc_html($label) . '</label>';
            echo '<select name="' . esc_attr($name) . '" id="' . esc_attr($name) . '">';
            foreach ($options as $value => $option_label) {
                echo '<option value="' . esc_attr($value) . '" ' . selected($value, $selected, false) . '>' . esc_html($option_label) . '</option>';
            }
            echo '</select>';
        }
    }


    public static function link_list_field($capability, $target_user_id) {
        $value = Plugin_Name_Utilities::handle_user_meta('links_list', $capability, $target_user_id);
        
        $decodedString = urldecode($value);
        $linksArray = json_decode($decodedString, true);
    
        /**  This following array would not work as it starts from 1 (Decoded string gives this)*/

        // $linksArray = array(
        //     1 => array(
        //         'id' => 1691399168396,
        //         'text' => 'http://io.oO'
        //     ),
        //     2 => array(
        //         'id' => 1691399170553,
        //         'text' => 'http://io.oOp'
        //     )
        // );

        /** !important:   This following array would  work as it starts from 0 */

        // $linksArray = array(
        //     0 => array(
        //         'id' => 1691399168396,
        //         'text' => 'http://io.oO'
        //     ),
        //     1 => array(
        //         'id' => 1691399170553,
        //         'text' => 'http://io.oOp'
        //     )
        // );
        

        /** Re-index to fix the above issue */
        $reIndexedArray = array_values($linksArray);
        
        $links_json = htmlspecialchars(json_encode($reIndexedArray), ENT_QUOTES, 'UTF-8');
        
        // Start the output buffering
        ob_start();
    
        // Check capability
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
        } else {
            ?>

            <main x-data="dataList(<?php echo $links_json; ?>)">
                <input 
                    placeholder="Type link here..." 
                    type="text" 
                    name="links" 
                    x-model="inputAddLinkValue"
                    @keyup.enter="addLink()"
                    @keydown.enter.prevent
                />
                <span x-text="linkError" class="text-danger"></span>
                <ul>
                    <template x-for="link in links">
                        <li 
                            x-bind:draggable="!isInputFocused" 
                            @dragstart="handleDragStart($event, link.id)" 
                            @drop="handleDrop($event, link.id)" 
                            @dragover="handleDragOver($event)"
                        >
                            <div x-show="!link.isEditing">
                                <span x-text="link.text">Item</span>
                                &nbsp;&nbsp;&nbsp;
                                <button type="button" @click="removeLink(link.id)">x</button>
                                <button type="button" @click="showEditLinkForm(link.id)">
                                    <img class="width:12px" src="./assets/icons/pen.svg" />
                                </button>
                            </div>
                            <div 
                                id="editionForm" 
                                x-show="link.isEditing"
                            >
                                <input x-model="inputEditLinkValue" type="text" placeholder="Edit your link..." @keyup.enter="editLink(link.id)" @keydown.enter.prevent
                                @focus="isInputFocused = true"
                                @blur="isInputFocused = false"
                                />
                                <button type="button" @click="cancelEditLink()">Cancel</button>
                            </div>
                        </li>
                    </template>
                </ul>
                <input type="hidden" name="links_list" x-model="linksJson" />
            </main>

            

            <?php
        }
    
        // Get the content from the output buffer and end buffering
        $content = ob_get_clean();
    
        echo $content;
    }
    
    

    public static function upload_field($field_name, $label, $capability, $allowed_types = array('image/jpeg', 'image/png', 'image/tiff'), $max_size = 2 * 1024 * 102,  $target_user_id) {
        
        $name = $field_name . '_url';
        $image_url = Plugin_Name_Utilities::handle_user_meta($name, Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE,  $target_user_id);
        ?>

        <div class="upload-container <?php if(!Plugin_Name_Utilities::check_user_capability($capability)) { echo 'no-hover' ; } ?>">
    
        <?php
            if (isset($image_url) && strlen($image_url) > 2 && Plugin_Name_Utilities::check_user_capability($capability)) {
                echo '<img src="' . esc_attr($image_url) . '" alt="Uploaded File" class="file-preview">';
            }
    
            echo '<div class="upload-content" >'; // Added this container
    
                if(!Plugin_Name_Utilities::check_user_capability($capability)) {
                    echo '<label for="' . esc_attr($name) . '" class="block upload-label">' . esc_html($label) . '</label>';
                    echo '<input type="file" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" class="absolute inset-0 w-full h-full opacity-0" disabled />';
                    echo '<p class="mt-2 text-center text-red-600">' . esc_html(self::ERROR_MSG) . '</p>';
                } else {
                    echo '<form method="post" enctype="multipart/form-data">';
                    echo '<label for="' . esc_attr($name) . '" class="block upload-label">' . esc_html($label) . '</label>';
                    echo '<input type="file" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" class="absolute inset-0 w-full h-full opacity-0" accept="'. implode(',', $allowed_types) .'" onchange="this.form.submit()" />';
                    echo '<input type="submit" value="Upload" class="mt-2 upload-btn" />';
                    echo '</form>';
    
                    if (substr($name, -4) === '_url' && isset($_FILES[$name]) && Plugin_Name_Utilities::check_user_capability($capability)) {
                        self::handle_avatar_upload($name, $allowed_types, $max_size, $target_user_id);
                    }
                }
            
            echo '</div>'; // Closing the upload-content container
    
        echo '</div>';
    }
    

    private static function handle_avatar_upload($field_name, $allowed_types, $max_size, $target_user_id = null) {
        // If a target user ID isn't provided, use the current user's ID
        $user_id = $target_user_id ? $target_user_id : get_current_user_id();

       
        // If the file field has been posted and the capability is met, handle the upload
        if (isset($_FILES[$field_name])) {
            $file = $_FILES[$field_name];
    
            // Check the file type
            if (!in_array($file['type'], $allowed_types)) {
                echo '<p class="error">Invalid file type. Only JPG, JPEG, PNG, and TIFF are allowed.</p>';
                return;
            }
    
            // Check the file size
            if ($file['size'] > $max_size) {
                echo '<p class="error">File size exceeded. Maximum file size is ' . ($max_size / 1024) . 'KB.</p>';
                return;
            }
    
            // Rename the file
            $timestamp = time();
            $new_filename = $field_name . '_' . $timestamp . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    
            // Move to the desired location
            $upload = wp_upload_bits($new_filename, null, file_get_contents($file['tmp_name']));
    
            if ($upload['error']) {
                echo '<p class="error">Failed to upload avatar.</p>';
                return;
            }
    
            // Register the file in the media library
            $wp_filetype = wp_check_filetype($new_filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($new_filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
    
            $attach_id = wp_insert_attachment($attachment, $upload['file']);
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
            wp_update_attachment_metadata($attach_id, $attach_data);
    
            // Save the URL into the user's meta data
            update_user_meta($user_id, $field_name, $upload['url']);
    
            ?>

            <div class="toast active">
            
            <div class="toast-content">
                
                <svg class="check" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M14.72,8.79l-4.29,4.3L8.78,11.44a1,1,0,1,0-1.41,1.41l2.35,2.36a1,1,0,0,0,.71.29,1,1,0,0,0,.7-.29l5-5a1,1,0,0,0,0-1.42A1,1,0,0,0,14.72,8.79ZM12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"></path></svg>
                <div class="message">
                <span class="text text-1">Success</span>
                <span class="text text-2">Your changes has been saved</span>
                </div>
            </div>
            <!-- Remove 'active' class, this is just to show in Codepen thumbnail -->
            <div class="progress active"></div>
            </div>
            <script>
                setTimeout(() => {
                    window.location.assign(window.location.href);
                }, 1500);

            </script>
            <?php
            

        } else {
            echo '<p class="error">Something went wrong.</p>';
        }
    }
    
    
}
    

new Plugin_Name_Builder();