<?php

class Plugin_Name_Builder {
    
    const ERROR_MSG= "This functionality is only available for the Full Version";

    public static function text_field($name, $value, $isValue, $label, $icon, $capability, $target_user_id, $hasLimit = true) {
        $data = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
        if (!$data && $isValue) $data = $value;
        
        $char_limit = 0;
        if ($hasLimit) {
            // Retrieve the character limit from WordPress for admin user with ID 0
            $char_limit_key = 'limit_' . $name;

           
            $char_limit = get_metadata('user', 1, $char_limit_key, true);
            if (!$char_limit) {
                $hasLimit = false;  // Disable the limit if char_limit is unset
            }
        }
        ?>
        
        <label for="<?php echo $name; ?>" class="input-label"><?php echo $label; ?></label>
        
        <?php
        if($hasLimit) {
            echo '<div class="input-container" x-data="{ charCount: ' .  strlen($data) . ', charLimit: '.  $char_limit . '}">';
        } else {
            echo '<div class="input-container">';
        }
        ?>
            <?php
            // SVG icon
            echo $icon;
            
            // Display the field
            if (!Plugin_Name_Utilities::check_user_capability($capability)) {
                echo '<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($data) . '" class="input-field" placeholder="' . esc_attr($data) . '"' . ($hasLimit ? ' maxlength="' . esc_attr($char_limit) . '"' : '') . ' disabled />';
                echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
            } else {
                echo '<input type="text" name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" value="' . esc_attr($data) . '" class="input-field" placeholder="' . esc_attr($data) . '"' . ($hasLimit ? ' maxlength="' . esc_attr($char_limit) . '"' : '') . ' x-on:input="charCount = $event.target.value.length" />';
            }
            if ($hasLimit) {
                echo '<span class="char-counter" x-text="`${charCount} / ${charLimit}`"></span>';
            }
            ?>
        </div>
        <?php
    }
    
    
    public static function url_field($name, $value, $isValue, $label, $icon, $capability, $target_user_id, $hasLimit = true) {
        $data = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
        if (!$data && $isValue) $data = $value;
    
        $char_limit = 0;
        if ($hasLimit) {
            $char_limit_key = 'limit_' . $name;
            $char_limit = get_metadata('user', 1, $char_limit_key, true);
            if (!$char_limit) {
                $hasLimit = false;  // Disable the limit if char_limit is unset
            }
        }
        ?>
    
        <div 
            x-data="{ copied: false, charCount: <?= strlen($data) ?>, charLimit: <?= $char_limit ?>, username: '<?= esc_attr($data) ?>', secureUsername: '<?= esc_attr($data) ?>', isAvailable: false, isLoading: false, message: '', hasChecked: false }" 
            x-init="() => {
                isValidUsername = () => {
                    return /^[a-zA-Z0-9-_]+$/.test(username);
                };
                copyToClipboard = () => {
        const el = document.createElement('textarea');
        el.value = '<?php echo esc_js(site_url()); ?>/' + secureUsername;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        copied = true;
        setTimeout(() => { copied = false; }, 2000); // Reset after 2 seconds
    };
    
                checkAvailability = () => {
                    charCount = username.length;
                    if (!isValidUsername()) {
                        message = 'Invalid username.';
                        hasChecked = true;
                        return;  // Exit the function without making the AJAX request
                    }
    
                    isLoading = true;
                    hasChecked = true;
                    let formData = new FormData();
                    formData.append('action', 'callback');
                    formData.append('username', username);
                    formData.append('nonce', plugin.nonce);
    
                    fetch(plugin.ajax_url, {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        isLoading = false;
                        isAvailable = data.available;
                        message = data.available ? 'Username is available.' : 'Username is already taken.';
                        if(data.available) {
                            secureUsername = username; 
                        } else {
                            secureUsername = '<?= esc_js($data) ?>'; 
                        }
                    })
                    .catch(error => {
                        isLoading = false;
                        console.error('Error:', error);
                    });
                };
            }">
    
            <label for="<?php echo $name; ?>" class="input-label"><?php echo $label; ?></label>
            <div x-text="message" x-bind:style="'visibility: ' + (hasChecked ? 'visible' : 'hidden')" :class="{'text-blue-400': isAvailable, 'text-red-500': !isAvailable && message !== ''}" ></div>
    
            <div class="input-container">
                <?php
                // SVG icon
                echo $icon;
    
                // Display the field
                if (!Plugin_Name_Utilities::check_user_capability($capability)) {
                    echo '<input type="text" name="' . esc_attr($name . '_visible') . '" id="' . esc_attr($name) . '" x-model="username" x-on:input="checkAvailability" value="' . esc_attr($data) . '" class="input-field" placeholder="' . esc_attr($data) . '"' . ($hasLimit ? ' maxlength="' . esc_attr($char_limit) . '"' : '') . ' :disabled="isLoading" disabled />';
                    echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
                } else {
                    echo '<input type="text" name="' . esc_attr($name . '_visible') . '" id="' . esc_attr($name) . '" x-model="username" x-on:input="checkAvailability" value="' . esc_attr($data) . '" class="input-field" placeholder="' . esc_attr($data) . '"' . ($hasLimit ? ' maxlength="' . esc_attr($char_limit) . '"' : '') . ' :disabled="isLoading" />';
                }
                
    
                // The hidden input which holds the real value to be saved
                echo '<input type="hidden" name="' . esc_attr($name) . '" x-model="secureUsername" />';
                if ($hasLimit) {
                    echo '<span class="char-counter" x-text="`${charCount} / ${charLimit}`"></span>';
                }
                ?>
            </div>
            
<div class="flex items-center gap-3 mb-6">
<span class="block text-sm text-gray-500 hover:text-gray-700" x-text="`<?php echo esc_js(site_url('/bio')); ?>/` + secureUsername"></span>
<svg @click="copyToClipboard" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-5 h-5 cursor-pointer hover:text-gray-700" viewBox="0 0 24 24" fill="currentColor"><path d="M21,8.94a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19.32.32,0,0,0-.09,0A.88.88,0,0,0,14.05,2H10A3,3,0,0,0,7,5V6H6A3,3,0,0,0,3,9V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V9S21,9,21,8.94ZM15,5.41,17.59,8H16a1,1,0,0,1-1-1ZM15,19a1,1,0,0,1-1,1H6a1,1,0,0,1-1-1V9A1,1,0,0,1,6,8H7v7a3,3,0,0,0,3,3h5Zm4-4a1,1,0,0,1-1,1H10a1,1,0,0,1-1-1V5a1,1,0,0,1,1-1h3V7a3,3,0,0,0,3,3h3Z"></path></svg>
<span x-show="copied" class="ml-2 text-sm text-gray-700">Copied!</span>
</div>
        </div>
        <?php
    }
    
    
    
    
    public static function textarea_field($name, $value, $label, $capability, $target_user_id, $hasLimit = true) {
        $data = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
        
        $char_limit = null;
        if ($hasLimit) {
            // Retrieve the character limit from WordPress for admin user with ID 1
            $char_limit_key = 'limit_' . $name;
            $char_limit = get_user_meta(1, $char_limit_key, true);
            if (!$char_limit) {
                $hasLimit = false;  // Disable the limit if char_limit is unset
            }
        }
    
        echo '<label for="' . esc_attr($name) . '" class="textarea-label">' . esc_html($label) . '</label>';
    
        if ($hasLimit && $char_limit) {
            echo '<div class="textarea-container" x-data="{ charCount: ' . strlen($data) . ', charLimit: ' . $char_limit . ' }">';
        } else {
            echo '<div class="textarea-container">';
        }
    
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" class="textarea-field" rows="4" disabled style="resize: none !important;">' . esc_textarea($data) . '</textarea>';
            echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
        } else {
            echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" class="textarea-field" rows="4" ' . ($char_limit ? 'x-bind:maxlength="' . esc_attr($char_limit) . '" x-on:input="charCount = $event.target.value.length"' : '') . ' style="resize: none !important;">' . esc_textarea($data) . '</textarea>';
            if ($hasLimit && $char_limit) {
                echo '<span class="textarea-char-counter" x-text="`${charCount} / ${charLimit}`">' . strlen($data) . ' / ' . $char_limit . '</span>';
            }
        }
    
        echo '</div>'; // Closing div for textarea-container
    }
    
    
    
    
    
    public static function checkbox_field($name, $label, $capability, $target_user_id) {
        $value = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
        
        // Check user capability
        $disabledAttribute = !Plugin_Name_Utilities::check_user_capability($capability) ? 'disabled' : '';
        $initialState = ($value === 'yes') ? 'true' : 'false';

        
        echo '<div x-data="{ switchState: ' . $initialState . ' }" style="margin-bottom: 2rem;">';
        // Hidden Field
        echo '<input type="hidden" name="' . esc_attr($name) . '" x-bind:value="switchState ? \'yes\' : \'no\'">';
        
        // Toggle Switcher with Label
        echo '<label class="toggle-label">';
        echo '<input type="checkbox" x-model="switchState" ' . $disabledAttribute . ' style="display: none !important">';
        echo '<div class="mr-4 toggle">';
        echo '<div class="toggle__line"></div>';
        echo '<div class="toggle__dot"></div>';
        echo '</div>';
        echo esc_html($label);
        echo '</label>';
        
        // Error Message if disabled
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
        }
        
        echo '</div>'; // Closing div for x-data
    }
    
    
    
    
    


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
        
        public static function link_list_field($label, $capability, $target_user_id) {
            $value = Plugin_Name_Utilities::handle_user_meta('links_list', $capability, $target_user_id);
            
            $decodedString = urldecode($value);
            $linksArray = json_decode($decodedString, true);
            
            /** Re-index to fix the above issue */
            $reIndexedArray = array_values(is_array($linksArray) ? $linksArray : []);
            
            $links_json = htmlspecialchars(json_encode($reIndexedArray), ENT_QUOTES, 'UTF-8');
            $links_limit = Plugin_Name_Utilities::get_user_maxLinks($target_user_id);
            
            // Start the output buffering
            ob_start();
            
            // Check capability
            if (!Plugin_Name_Utilities::check_user_capability($capability)) {
                echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
            } else {
                ?>
                <main x-data="dataList({initLinks: <?php echo $links_json; ?>, initMax: <?php echo $links_limit; ?>})" x-init="applyScheduling()">
            
                    <!-- New Add New Link button -->
                    <button type="button" x-show="links.length < maxLinks" @click="showAddNewLink()" class="add-link-btn">Add New Link</button>
        
                    <!-- New form that appears when the Add New Link button is clicked -->
                    <div x-show="showAddNewLinkForm">
                        <div class="relative p-5 mt-5">
                            <button @click.prevent="showAddNewLinkForm = false" class="absolute top-0 border-0 cursor-pointer right-2 bg-inherit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18ZM20,6H16V5a3,3,0,0,0-3-3H11A3,3,0,0,0,8,5V6H4A1,1,0,0,0,4,8H5V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V8h1a1,1,0,0,0,0-2ZM10,5a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1V6H10Zm7,14a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V8H17Zm-3-1a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z"></path></svg>
                            </button>
                            <label class="input-label">URL</label>
                            <input class="input-field-enhanced" x-model="inputAddLinkValue" x-bind:required="showAddNewLinkForm">
                            <label class="input-label">Title</label>
                            <input class="input-field-enhanced" x-model="newLink.title">


                            <?php 
                            if (!Plugin_Name_Utilities::check_user_capability(Plugin_Name_Capabilities::CAN_SCHEDULE_LINK)) {
                                echo '<div class="my-3">Only Full version users can schedule link</div>';
                            } else { ?>
                                <div class="my-3">
                                <label>
                                    <input type="checkbox" x-model="newLink.isScheduled">
                                    Enable Scheduling
                                </label>
                            </div>
                            
                            <div class="flex flex-col gap-5 my-5 md:flex-row" x-show="newLink.isScheduled">
                                <div class="flex items-center gap-3">
                                    <label class="input-label"> Start Time</label>
                                    <input type="datetime-local" x-model="newLink.start_time">             
                                </div>
        
                                <div class="flex items-center gap-3">
                                    <label class="input-label"> End Time</label>
                                    <input type="datetime-local" x-model="newLink.end_time">
                                </div>
                            </div>
                            
                            <?php } ?>

                            
                            

                                
                            
                            <button type="button" @click="addLink()" class="upload-btn">Add Link</button>
                        </div>
                    </div>
                    
                    <span x-text="linkError" class="text-danger"></span>
                    <span x-text="maxLinksError" x-show="links.length >= maxLinks" class="text-danger"></span>
        
                    <!-- Existing links display -->
                    <ul>
                        <template x-for="link in links">
                        <li 
                            class="p-5 m-5 bg-gray-200 border-2 border-dashed rounded-md"
                            x-bind:draggable="!link.isEditing && !isInputFocused" 
                            @dragstart="handleDragStart($event, link.id)" 
                            @dragend="handleDragEnd($event)" 
                            @drop="handleDrop($event, link.id)" 
                            @dragover="handleDragOver($event)"
                            @dragenter="draggedOverLinkId = link.id" 
                            @dragleave="draggedOverLinkId = null"
                            :class="{
                                'drag-over': draggedOverLinkId === link.id,
                                'hidden-link-class': link.isHidden,
                                'highlight-link-class': link.highlight,
                                'dragging-class': link.isDragging
                            }"
                        >
                            <div x-show="!link.isEditing" class="flex items-center justify-between">
                             <div class="flex flex-col">
                             <div x-data="{ switchState: !link.isHidden }" style="margin-bottom: 1rem;">
                                        <label class="toggle-label">
                                            <input 
                                                type="checkbox" 
                                                x-model="switchState" 
                                                @change="toggleHideLink(link.id)"
                                                style="display: none !important"
                                            >
                                            <div class="mr-4 toggle toggle--small">
                                                <div class="toggle__line"></div>
                                                <div class="toggle__dot toggle__dot--small"></div>
                                            </div>
                                        </label>
                                    </div>
                                <span x-text="link.title" class="text-sm font-semibold"></span>
                                <span x-text="link.text" class="text-gray-600"></span>
                             </div>
                             <div class="flex items-center">
                                 <button type="button" class="border-0 cursor-pointer bg-inherit" @click="showEditLinkForm(link.id)">

                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M5,18H9.24a1,1,0,0,0,.71-.29l6.92-6.93h0L19.71,8a1,1,0,0,0,0-1.42L15.47,2.29a1,1,0,0,0-1.42,0L11.23,5.12h0L4.29,12.05a1,1,0,0,0-.29.71V17A1,1,0,0,0,5,18ZM14.76,4.41l2.83,2.83L16.17,8.66,13.34,5.83ZM6,13.17l5.93-5.93,2.83,2.83L8.83,16H6ZM21,20H3a1,1,0,0,0,0,2H21a1,1,0,0,0,0-2Z"></path></svg>
                                 </button>
                                    <button type="button" class="border-0 cursor-pointer bg-inherit" @click="removeLink(link.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18ZM20,6H16V5a3,3,0,0,0-3-3H11A3,3,0,0,0,8,5V6H4A1,1,0,0,0,4,8H5V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V8h1a1,1,0,0,0,0-2ZM10,5a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1V6H10Zm7,14a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V8H17Zm-3-1a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z"></path></svg>
                                    </button>
                                    

                                    <?php 
                                    if (!Plugin_Name_Utilities::check_user_capability(Plugin_Name_Capabilities::HIGHLIGHT_LINK)) {
                                        // DO NOTHING
                                    } else { ?>
                                        <button class="upload-btn" type="button" x-show="!link.isHidden" @click="toggleHighlightLink(link.id)">
                                            <span x-text="link.highlight ? 'Unhighlight' : 'Highlight'"></span>
                                        </button>
                                    
                                    <?php } ?>

                                    
                                   

                                    

                                    </div>
                                </div>
                                <div 
                                    id="editionForm" 
                                    x-show="link.isEditing"
                                >
                                    <div class="p-5 mt-5 ">
                                        <label class="input-label">Title</label>
                                        <input class="input-field-enhanced" x-model="inputEditTitleValue" >

                                        <label class="input-label">URL</label>
                                        <input class="input-field-enhanced" x-model="inputEditLinkValue" >

                                       

                                        <?php 
                                        if (!Plugin_Name_Utilities::check_user_capability(Plugin_Name_Capabilities::CAN_SCHEDULE_LINK)) {
                                            echo '<div class="my-2">Only Full version users can schedule link</div>';
                                        } else { ?>
                                         <div class="my-3">
                                            <input type="checkbox" x-model="link.isScheduled"> Enable Scheduling
                                        </div>
                                        <div class="flex flex-col gap-5 my-5 md:flex-row" x-show="link.isScheduled">
                                            <div class="flex items-center gap-3">
                                                <label class="input-label"> Start Time</label>
                                                <input type="datetime-local" x-model="link.start_time">             
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <label class="input-label"> End Time</label>
                                                <input type="datetime-local" x-model="link.end_time">
                                            </div>
                                        </div>
                                        
                                        <?php } ?>

                                        
                                        
                                       

                                        <!-- Image Upload -->
                                        <label class="input-label">Link Image</label>
                                        <div class="upload-container">
                                            <img x-show="link.imageFile" :src="link.imageFile" alt="Uploaded File" class="file-preview">
                                            <div x-show="!link.imageFile" class="flex items-center justify-center p-2 align-middle file-preview">No Image Uploaded</div>
                                            <div class="upload-content">
                                                <form method="post" enctype="multipart/form-data" x-ref="linkImageUploadForm">
                                                    <label for="link_image" class="block upload-label">Upload Image</label>
                                                    <input type="file" name="link_image" id="link_image" class="absolute inset-0 w-full h-full opacity-0" accept="image/jpeg,image/png,image/tiff" @change="uploadImage(link.id)" />
                                                </form>
                                            </div>
                                        </div>

                                        <hr>
                                        
                                        <button type="button" @click="editLink(link.id)" class="upload-btn">Save</button>
                                        <button type="button" @click="cancelEditLink()" class="upload-btn">Cancel</button>
                                    </div>

                                </div>
                            </li>
                        </template>
                    </ul>
            
                    <div x-show="links.length === 0" class="p-5 m-5 text-center bg-gray-200">
                        No links found.
                    </div>
                    <input type="hidden" name="links_list" x-model="linksJson" />
                </main>
        
               
                <?php
            }
            
            // Get the content from the output buffer and end buffering
            $content = ob_get_clean();
            
            echo $content;
        }
        
    
        public static function social_links_list_field($label, $capability, $target_user_id) {
            $value = Plugin_Name_Utilities::handle_user_meta('social_links_list', $capability, $target_user_id);
            
            $decodedString = urldecode($value);
            $linksArray = json_decode($decodedString, true);
            
            /** Re-index to fix any potential issues */
            $reIndexedArray = array_values(is_array($linksArray) ? $linksArray : []);
            
            $links_json = htmlspecialchars(json_encode($reIndexedArray), ENT_QUOTES, 'UTF-8');
            
            // Start the output buffering
            ob_start();
            
            // Check capability
            if (!Plugin_Name_Utilities::check_user_capability($capability)) {
                echo '<p class="description">' . esc_html(self::ERROR_MSG) . '</p>';
            } else {
                ?>
             <main x-data="socialLinks({initLinks: <?php echo $links_json; ?>})">
    <!-- Add New Link Button -->
    <button type="button" x-show="links.length < maxLinks" @click="showAddNewLinkForm = !showAddNewLinkForm" class="add-link-btn">Add New Social Link</button>

    <!-- New form that appears when the Add New Social Link button is clicked -->
    <div x-show="showAddNewLinkForm && !isAnyLinkBeingEdited()">
        <div class="relative p-5 mt-5">
            <button @click.prevent="showAddNewLinkForm = false" class="absolute top-0 border-0 cursor-pointer right-2 bg-inherit">
                <span>&times;</span>
            </button>
            
            <label class="input-label">Icon</label>
            <input class="input-field-enhanced" x-model="newLink.icon" placeholder="e.g., fa-facebook">

            <label class="input-label">URL</label>
            <input class="input-field-enhanced" x-model="inputAddLinkValue" placeholder="https://www.example.com">

            <button type="button" @click="addLink()" class="upload-btn">Add Link</button>
        </div>
    </div>

    <!-- Displaying Error Messages -->
    <span x-text="linkError" class="text-danger"></span>
    <span x-text="maxLinksError" x-show="links.length >= maxLinks" class="text-danger"></span>

    <!-- Existing Social Links Display -->
    <ul>
        <template x-for="link in links">
            <li 
                class="p-5 m-5 bg-gray-200 border-2 border-dashed rounded-md"
                x-bind:draggable="!link.isEditing" 
                @dragstart="handleDragStart($event, link.id)" 
                @dragend="handleDragEnd($event)" 
                @drop="handleDrop($event, link.id)" 
                @dragover="handleDragOver($event)"
                @dragenter="draggedOverLinkId = link.id" 
                @dragleave="draggedOverLinkId = null"
            >
                <div x-show="!link.isEditing" class="flex items-center justify-between">
                    <div>
                        <i :class="link.icon"></i>
                        <span x-text="link.url" class="text-gray-600"></span>
                    </div>

                    <div class="flex items-center">
                       
                        <button type="button" class="border-0 cursor-pointer bg-inherit" @click="showEditLinkForm(link.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M5,18H9.24a1,1,0,0,0,.71-.29l6.92-6.93h0L19.71,8a1,1,0,0,0,0-1.42L15.47,2.29a1,1,0,0,0-1.42,0L11.23,5.12h0L4.29,12.05a1,1,0,0,0-.29.71V17A1,1,0,0,0,5,18ZM14.76,4.41l2.83,2.83L16.17,8.66,13.34,5.83ZM6,13.17l5.93-5.93,2.83,2.83L8.83,16H6ZM21,20H3a1,1,0,0,0,0,2H21a1,1,0,0,0,0-2Z"></path></svg>
                        </button>
                        <button type="button" class="border-0 cursor-pointer bg-inherit" @click="removeLink(link.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18ZM20,6H16V5a3,3,0,0,0-3-3H11A3,3,0,0,0,8,5V6H4A1,1,0,0,0,4,8H5V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V8h1a1,1,0,0,0,0-2ZM10,5a1,1,0,0,1,1-1h2a1,1,0,0,1,1,1V6H10Zm7,14a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V8H17Zm-3-1a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z"></path></svg>
                        </button>
                    </div>
                </div>

                <div x-show="link.isEditing && !showAddNewLinkForm">
                    <div class="p-5 mt-5">
                        <label class="input-label">Icon</label>
                        <input class="input-field-enhanced" x-model="link.icon">

                        <label class="input-label">URL</label>
                        <input class="input-field-enhanced" x-model="inputEditLinkValue">

                        <button type="button" @click="editLink(link.id)" class="upload-btn">Save</button>
                        <button type="button" @click="cancelEditLink()" class="upload-btn">Cancel</button>
                    </div>
                </div>
            </li>
        </template>
    </ul>

    <div x-show="links.length === 0" class="p-5 m-5 text-center bg-gray-200">
        No social links found.
    </div>
    <input type="hidden" name="social_links_list" x-model="linksJson" />
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
            } else {
                echo '<div class="flex items-center justify-center p-2 align-middle file-preview"> No File Uploaded </div>';
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
    
            $uploads_dir = wp_upload_dir();
            $ph_bio_dir = $uploads_dir['basedir'] . '/ph-bio';
            if (!file_exists($ph_bio_dir)) {
                wp_mkdir_p($ph_bio_dir); // Create directory if it doesn't exist
            }
    
            $timestamp = time();
            $new_filename = $field_name . '_user_' . $user_id . '_' . $timestamp . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $target_file_path = $ph_bio_dir . '/' . $new_filename;
    
            // Move the uploaded file
            if (move_uploaded_file($file['tmp_name'], $target_file_path)) {
                $file_url = $uploads_dir['baseurl'] . '/ph-bio/' . basename($new_filename);
    
                // Save the URL into the user's meta data
                update_user_meta($user_id, $field_name, $file_url);
    
                ?>
                <div class="toast active">
                    <div class="toast-content">
                        <svg class="check" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M14.72,8.79l-4.29,4.3L8.78,11.44a1,1,0,1,0-1.41,1.41l2.35,2.36a1,1,0,0,0,.71.29,1,1,0,0,0,.7-.29l5-5a1,1,0,0,0,0-1.42A1,1,0,0,0,14.72,8.79ZM12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"></path></svg>
                        <div class="message">
                            <span class="text text-1">Success</span>
                            <span class="text text-2">Your changes have been saved</span>
                        </div>
                    </div>
                    <div class="progress active"></div>
                </div>
                <script>
                    setTimeout(() => {
                        window.location.assign(window.location.href);
                    }, 1500);
                </script>
                <?php
            } else {
                echo '<p class="error">Failed to upload image.</p>';
            }
        } else {
            echo '<p class="error">Something went wrong.</p>';
        }
    }
    
    
    
}
    

new Plugin_Name_Builder();