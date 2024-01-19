<?php

class Plugin_Name_Builder {
    
    const ERROR_MSG= "<a href='/upgrade' class='text-gray-700 no-underline font-semi-bold' target='_blank'>Unlock this feature instantly by <span class='text-[#F1441E] font-bold'>Going PRO</span>.</a>";
    const ERROR_MAX_LINK_MSG= "<a href='/upgrade' class='text-gray-700 no-underline font-semi-bold' target='_blank'>You have hit the link limit. <span class='text-[#F1441E] font-bold'>Upgrade Now</span>.</a>";
    const ERROR_LINK_SCHEDULING= "<a href='/upgrade' class='text-gray-700 no-underline font-semi-bold' target='_blank'>Unlock links scheduling option. <span class='text-[#F1441E] font-bold'>Go PRO now</span>!</a>";
    
    

    public static function text_field($name, $value, $isValue, $label, $icon, $capability, $target_user_id, $hasLimit = true, $templateIncluded = true) {
        $data = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
        if (!$data && $isValue) $data = $value;
        
        if(!$templateIncluded) {
            echo Plugin_Name_Utilities::is_not_included_field($label);
        }
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
                echo '<p class="description">' . self::ERROR_MSG . '</p>';
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
            class="mt-10"
            x-data="{ typingTimer: '', doneTypingInterval: 2000,  copied: false, charCount: <?= strlen($data) ?>, charLimit: <?= $char_limit ?>, username: '<?= esc_attr($data) ?>', secureUsername: '<?= esc_attr($data) ?>', isAvailable: false, isLoading: false, message: '', hasChecked: false }" 
            x-init="() => {
                
                isValidUsername = () => {
                    return /^[a-zA-Z0-9-_]+$/.test(username);
                };
                copyToClipboard = () => {
                    const el = document.createElement('textarea');
                    el.value = '<?php echo esc_js(site_url('/bio')); ?>/' + secureUsername;
                    document.body.appendChild(el);
                    el.select();
                    document.execCommand('copy');
                    document.body.removeChild(el);
                    copied = true;
                    setTimeout(() => { copied = false; }, 2000); // Reset after 2 seconds
                };
                navigateToLink = () => {
                    const url = '<?php echo esc_js(site_url('/bio')); ?>/' + secureUsername;
                    window.open(url, '_blank');
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
                    formData.append('type', 'hb-user-profile');
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
                onInput = () => {
                    // Clear the existing timer if there is one
                    clearTimeout(typingTimer);
                    
                    // Set a new timer
                    typingTimer = setTimeout(checkAvailability, doneTypingInterval);
                };
                }">
    
            <label for="<?php echo $name; ?>" class="mt-3 input-label"><?php echo $label; ?></label>
            <div x-text="message" x-bind:style="'visibility: ' + (hasChecked ? 'visible' : 'hidden')" :class="{'text-blue-400': isAvailable, 'text-red-500': !isAvailable && message !== ''}" ></div>
    
            <div class="input-container">
                <?php
                // SVG icon
                echo $icon;
    
                // Display the field
                if (!Plugin_Name_Utilities::check_user_capability($capability)) {
                    echo '<input type="text" name="' . esc_attr($name . '_visible') . '" id="' . esc_attr($name) . '" x-model="username" x-on:input="onInput" value="' . esc_attr($data) . '" class="input-field" placeholder="' . esc_attr($data) . '"' . ($hasLimit ? ' maxlength="' . esc_attr($char_limit) . '"' : '') . ' :disabled="isLoading" disabled />';
                    echo '<p class="description">' . self::ERROR_MSG . '</p>';
                } else {
                    echo '<input type="text" name="' . esc_attr($name . '_visible') . '" id="' . esc_attr($name) . '" x-model="username" x-on:input="onInput" value="' . esc_attr($data) . '" class="input-field" placeholder="' . esc_attr($data) . '"' . ($hasLimit ? ' maxlength="' . esc_attr($char_limit) . '"' : '') . ' :disabled="isLoading" />';
                }
                
    
                // The hidden input which holds the real value to be saved
                echo '<input type="hidden" name="' . esc_attr($name) . '" x-model="secureUsername" />';
                if ($hasLimit) {
                    echo '<span class="char-counter" x-text="`${charCount} / ${charLimit}`"></span>';
                }
                ?>
            </div>
            
<div class="flex flex-col items-start gap-3 my-5 mb-6 sm:items-center sm:flex-row" x-show="secureUsername">
<span class="block mt-0 text-sm text-gray-500 hover:text-gray-700" style="margin: 0px;" x-text="`<?php echo esc_js(site_url('/bio')); ?>/` + secureUsername"></span>
<div class="flex flex-row gap-4 sm:gap-2">
<svg @click="copyToClipboard" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-5 h-5 cursor-pointer hover:text-gray-700" viewBox="0 0 24 24" fill="currentColor"><path d="M21,8.94a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19.32.32,0,0,0-.09,0A.88.88,0,0,0,14.05,2H10A3,3,0,0,0,7,5V6H6A3,3,0,0,0,3,9V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V9S21,9,21,8.94ZM15,5.41,17.59,8H16a1,1,0,0,1-1-1ZM15,19a1,1,0,0,1-1,1H6a1,1,0,0,1-1-1V9A1,1,0,0,1,6,8H7v7a3,3,0,0,0,3,3h5Zm4-4a1,1,0,0,1-1,1H10a1,1,0,0,1-1-1V5a1,1,0,0,1,1-1h3V7a3,3,0,0,0,3,3h3Z"></path></svg>
<svg @click="navigateToLink" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 cursor-pointer hover:text-gray-700" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18,10.82a1,1,0,0,0-1,1V19a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V8A1,1,0,0,1,5,7h7.18a1,1,0,0,0,0-2H5A3,3,0,0,0,2,8V19a3,3,0,0,0,3,3H16a3,3,0,0,0,3-3V11.82A1,1,0,0,0,18,10.82Zm3.92-8.2a1,1,0,0,0-.54-.54A1,1,0,0,0,21,2H15a1,1,0,0,0,0,2h3.59L8.29,14.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L20,5.41V9a1,1,0,0,0,2,0V3A1,1,0,0,0,21.92,2.62Z"></path></svg>

<span x-show="copied" class="ml-2 text-sm text-gray-700">Copied!</span>
</div>
</div>
        </div>
        <?php
    }
    
    
    
    
    public static function textarea_field($name, $value, $label, $capability, $target_user_id, $hasLimit = true, $templateIncluded = true) {
        if(!$templateIncluded) {
            echo Plugin_Name_Utilities::is_not_included_field($label);;
        }
        
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
    
        echo '<label for="' . esc_attr($name) . '" class="input-label">' . esc_html($label) . '</label>';
    
        if ($hasLimit && $char_limit) {
            echo '<div class="textarea-container" x-data="{ charCount: ' . strlen($data) . ', charLimit: ' . $char_limit . ' }">';
        } else {
            echo '<div class="textarea-container">';
        }
    
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<textarea name="' . esc_attr($name) . '" id="' . esc_attr($name) . '" class="textarea-field" rows="4" disabled style="resize: none !important;">' . esc_textarea($data) . '</textarea>';
            echo '<p class="description">' . self::ERROR_MSG . '</p>';
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

        // Error Message if disabled
        if (!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<div class="cursor-not-allowed" style="margin-bottom: 2rem; opacity: 0.7;">';

            // Toggle Switcher with Label
            echo '<label class="cursor-not-allowed toggle-label">';
            echo '<input class="cursor-not-allowed" type="checkbox disabled" style="display: none !important">';
            echo '<div class="mr-4 toggle">';
            echo '<div class="cursor-not-allowed toggle__line"></div>';
            echo '<div class="cursor-not-allowed toggle__dot"></div>';
            echo '</div>';
            echo  esc_html($label);
            echo '</label>';

            echo '</div>';
        } else {
        
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
        
        
        
        echo '</div>'; // Closing div for x-data

        }
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
        
        public static function social_links_list_field($label, $capability, $target_user_id) {
            $value = Plugin_Name_Utilities::handle_user_meta('social_links_list', $capability, $target_user_id);
            $decodedString = urldecode($value);
            $linksArray = json_decode($decodedString, true);
            $reIndexedArray = array_values(is_array($linksArray) ? $linksArray : []);
            $links_json = htmlspecialchars(json_encode($reIndexedArray), ENT_QUOTES, 'UTF-8');
            
            $fontAwesomeIconList = get_option('fontAwesomeIconList');
            $fontAwesomeIconListUser = get_user_meta( $target_user_id, 'fontAwesomeIconListUser', true );
            $iconsJson = htmlspecialchars(json_encode($fontAwesomeIconList), ENT_QUOTES, 'UTF-8');
            
            ob_start();
            
            if (!Plugin_Name_Utilities::check_user_capability($capability)) {
                echo '<p class="description">' . self::ERROR_MSG . '</p>';
            } else {
                ?>

                <main x-data="socialLinks({initLinks: <?php echo $links_json; ?>})">
               

                    <button type="button" x-show="links.length < maxLinks" @click="showAddNewLink()" class="add-link-btn">Add New Social Icon</button>
                    <div x-show="showAddNewLinkForm" @input="console.log($event.detail); newLink.title = $event.detail">
                        <div class="relative p-10 my-5 bg-gray-50 mx-0 sm:mx-5 border-solid rounded-[10px] border-[1px] border-[#F1441E]">

                            <button @click.prevent="showAddNewLinkForm = false" class="absolute text-gray-700 border-0 cursor-pointer top-5 right-5 bg-inherit ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 448 512" fill="currentColor"><path d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>                           
                            
                            </button>

                            <div class="flex flex-row items-center gap-2 mb-10 text-xs font-semibold uppercase sm:text-sm md:text-base">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="text-[#f1441e] text-[14px]" fill="currentColor"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>
                                <span>You're adding a new icon</span>
                            </div>
                            <label class="input-label">Select Icon</label>
                            <div class="px-4 py-4 mt-4 mb-8 bg-white rounded shadow-og" x-data="dropdown({selected: '', initIcons: <?php echo $iconsJson; ?>})" x-init="$watch('selected', value => { console.log('Dispatching', value); $dispatch('input', value) })" >
                                <div @click="isOpen = !isOpen" class="relative cursor-pointer">
                                    <div class="flex items-center">
                                        <span x-show="!selected" class="mr-2 text-gray-500">Select an icon</span>
                                        <i x-show="selected" :class="'fa fa-2x ' + selected" class="mr-2 "></i>
                                    </div>
                                    <div x-show="isOpen" class="absolute z-10 w-full bg-white border">
                                        <input type="text" x-model="search" placeholder="Search..." class="w-full p-2" @click.stop />
                                        <template x-for="option in filteredOptions()" :key="option">
                                            <div @click.stop="selectOption(option)" class="flex items-center p-2 cursor-pointer hover:bg-gray-200" :class="{'bg-gray-100': selected === option}">
                                                <i :class="'fa fa-2x ' + option" class="mr-2"></i>
                                            </div>
                                        </template>
                                        <div x-show="!filteredOptions().length" class="p-2">No results found</div>
                                    </div>
                                </div>
                                <input type="hidden" name="fontAwesomeIconListUser" x-model="selected">
                            </div>

        
                            
                            <label class="input-label">URL</label>
                            <div class="input-container-enhanced input-container shadow-og">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F1441E]" fill="currentColor" viewBox="0 0 640 512"><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>
                                <input class="input-field-enhanced" x-model="inputAddLinkValue" @input.stop>
                            </div>

                            <div x-text="linkError" class="text-danger"></div>

                            <button type="button" @click="addLink()" class="upload-btn">Add Link</button>
                        </div>
                    </div>
                    
                    <ul x-ref="list" class="lists_container">
                        <template x-for="link in links">
                            <li draggable="true" @dragenter="draggedOverLinkId = link.id" @dragleave="draggedOverLinkId = null" @dragover="handleDragOver($event)" @drop="handleDrop($event, link.id)" :class="{
                                'drag-over': draggedOverLinkId === link.id,
                                'hidden-link-class': linkIsHidden(link.id),
                                'dragging-class': linkIsDragging(link.id),
                                'on-edit': link.isEditing
                            }",
                           
                            
                            >
                                <div x-show="!link.isEditing" class="flex items-center justify-between" >
                                <div 
                                    x-bind:draggable="!link.isEditing && !isInputFocused" 
                                    @dragstart="handleDragStart($event, link.id)" 
                                    @dragend="handleDragEnd($event, link.id)" 
                                    class="drag-handle"
                                >⠿</div>
                                    <div class="flex flex-col flex-auto text-2xl sm:ml-5">
                                        <i :class="'fa '+ link.title" class=" font-semibold  text-[#F1441E]"></i>
                                    </div>
                                    <div class="flex items-center">
                                        <button type="button" class="border-0 cursor-pointer bg-inherit" @click="showEditLinkForm(link.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class=" text-gray-700 w-3.5 sm:w-[1.15rem]" viewBox="0 0 512 512" fill="currentColor"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                                        </button>
                                        <button type="button" class="border-0 cursor-pointer bg-inherit" @click="removeLink(link.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class=" text-gray-700 w-3.5 sm:w-[1.1rem]" viewBox="0 0 448 512" fill="currentColor"><path d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>                           

                                        </button>
                                    </div>
                                </div>
                                <div id="editionForm" x-show="link.isEditing">
                                    <div class="p-5 mt-5">

                                        <div class="flex flex-row items-center gap-2 mb-10 text-xs font-semibold uppercase sm:text-sm md:text-base">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="text-[#f1441e] text-[14px]" fill="currentColor"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>
                                            <span>You're editing your icon</span>
                                        </div>
                                        <label class="input-label">Icon</label>
                                        
                                       
                                        <div class="px-4 py-4 mt-4 mb-8 bg-white rounded shadow-og" @input="inputEditTitleValue = $event.detail; " x-data="dropdown({selected: '', initIcons: <?php echo $iconsJson; ?>})" x-init="$watch('selected', value => { console.log('Dispatching', value); $dispatch('input', value) })">
                                            <div @click="isOpen = !isOpen" class="relative cursor-pointer">
                                                <div class="flex items-center">
                                                    <span x-show="!selected" class="mr-2 text-gray-500">Change Icon</span>
                                                    <i x-show="selected" :class="'fa fa-2x ' + selected" class="mr-2"></i>
                                                </div>
                                                <div x-show="isOpen" class="absolute z-10 w-full bg-white border">
                                                    <input type="text" x-model="search" placeholder="Search..." class="w-full p-2" @click.stop />
                                                    <template x-for="option in filteredOptions()" :key="option">
                                                        <div @click.stop="selectOption(option)" class="flex items-center p-2 cursor-pointer hover:bg-gray-200" :class="{'bg-gray-100': selected === option}">
                                                            <i :class="'fa fa-2x ' + option" class="mr-2"></i>
                                                        </div>
                                                    </template>
                                                    <div x-show="!filteredOptions().length" class="p-2">No results found</div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="fontAwesomeIconListUser" x-model="selected">
                                        </div>
                                        <label class="input-label">URL</label>
                                        <div class="input-container-enhanced input-container shadow-og">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F1441E]" fill="currentColor" viewBox="0 0 640 512"><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>
                                            <input class="input-field-enhanced" x-model="inputEditLinkValue" @input.stop>
                                        </div>
                                        <div x-text="linkError" class="text-danger"></div>

                                        <button type="button" @click="editLink(link.id)" class="upload-btn">Save</button>
                                        <button type="button" @click="cancelEditLink()" class="bg-[#171717] border-[#171717] upload-btn">Cancel</button>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                    <div x-show="links.length === 0" class="p-5 m-5 text-center">No links found.</div>
                    <input type="hidden" name="social_links_list" x-model="linksJson" />
                </main>
                <?php
            }
            $content = ob_get_clean();
            echo $content;
        }
        
        public static function link_list_field($label, $capability, $target_user_id) {
            $value = Plugin_Name_Utilities::handle_user_meta('links_list', $capability, $target_user_id);
            
            $decodedString = urldecode($value);
            $linksArray = json_decode($decodedString, true);
            
            /** Re-index to fix the above issue */
            $reIndexedArray = array_values(is_array($linksArray) ? $linksArray : []);
            
            $links_json = htmlspecialchars(json_encode($reIndexedArray), ENT_QUOTES, 'UTF-8');
            $links_limit = Plugin_Name_Utilities::get_user_maxLinks($target_user_id);
            

            $image_urls = get_user_meta($target_user_id, 'img_gallery_urls', true); // Fetching existing URLs
            $image_urls = $image_urls ? json_decode($image_urls, true) : array(); // Decoding the JSON string to an array

            // Start the output buffering
            ob_start();
            
            // Check capability
            if (!Plugin_Name_Utilities::check_user_capability($capability)) {
                echo '<p class="description">' . self::ERROR_MSG . '</p>';
            } else {
                ?>
                <main x-data="dataList({initLinks: <?php echo $links_json; ?>, initMax: <?php echo $links_limit; ?>})" x-init="applyScheduling()">
            
                    <!-- New Add New Link button -->
                    <button type="button" x-show="links.length < maxLinks" @click="showAddNewLink()" class="add-link-btn">Add New Link</button>
                   
                    <!-- New form that appears when the Add New Link button is clicked -->
                    <div x-show="showAddNewLinkForm">
                        <div class="relative p-10 my-5 bg-gray-50 m-0 sm:m-5 border-solid rounded-[10px] border-[1px] border-[#F1441E]">
                            <button @click.prevent="showAddNewLinkForm = false" class="absolute text-gray-700 border-0 cursor-pointer top-5 right-5 bg-inherit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 448 512" fill="currentColor"><path d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>                                               
                            </button>
                            
                            <div class="flex flex-row items-center gap-2 mb-10 text-xs font-semibold uppercase sm:text-sm md:text-base">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="text-[#f1441e] text-[14px]" fill="currentColor"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>
                                <span>You're adding a new link</span>
                            </div>
                            <label class="input-label">Title</label>
                            <div class="input-container-enhanced input-container shadow-og">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F1441E]" fill="currentColor" viewBox="0 0 512 512"><path d="M453.3 19.3l39.4 39.4c25 25 25 65.5 0 90.5l-52.1 52.1 0 0-1-1 0 0-16-16-96-96-17-17 52.1-52.1c25-25 65.5-25 90.5 0zM241 114.9c-9.4-9.4-24.6-9.4-33.9 0L105 217c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9L173.1 81c28.1-28.1 73.7-28.1 101.8 0L288 94.1l17 17 96 96 16 16 1 1-17 17L229.5 412.5c-48 48-109.2 80.8-175.8 94.1l-25 5c-7.9 1.6-16-.9-21.7-6.6s-8.1-13.8-6.6-21.7l5-25c13.3-66.6 46.1-127.8 94.1-175.8L254.1 128 241 114.9z"/></svg>

                                <input class="input-field-enhanced" x-model="newLink.title">
                            </div>
                            <label class="input-label">URL</label>
                            <div class="input-container-enhanced input-container shadow-og">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F1441E]" fill="currentColor" viewBox="0 0 640 512"><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>
                                <input class=" input-field-enhanced" x-model="inputAddLinkValue" x-bind:required="showAddNewLinkForm">
                            </div>

                            <div x-text="linkError" class="text-danger"></div>

                            <?php 
                            if (!Plugin_Name_Utilities::check_user_capability(Plugin_Name_Capabilities::CAN_SCHEDULE_LINK)) {
                                echo '<div class="warning-message"><svg xmlns="http://www.w3.org/2000/svg" class="warning-icon" width="24" height="24" viewBox="0 0 448 512" fill="currentColor"><path d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z"></path></svg><span>' . self::ERROR_LINK_SCHEDULING . '</span></div>';

                            } else { ?>
                            <div class="my-3 text-sm sm:text-[15px]">
                                <label>
                                    <input type="checkbox" x-model="newLink.isScheduled">
                                    Enable Scheduling
                                </label>
                            </div>
                            <div class="my-3 text-sm sm:text-[15px]" x-show="newLink.isScheduled">
                                <label>
                                    <input type="checkbox" x-model="newLink.isEndScheduled">
                                    <span>Enable End Time</span>
                                </label>
                            </div>
                            
                            <div class="flex flex-col w-full gap-5 my-5 md:gap-7 md:flex-row" >
                                <div class="flex flex-col items-baseline gap-1 md:gap-3 md:flex-row md:w-fit" x-show="newLink.isScheduled">
                                    <label class="w-full input-label md:text-sm max-w-fit"> Start Time</label>
                                    <input class="schedule_time" class="w-full md:max-w-fit" type="datetime-local" x-model="newLink.start_time" value="<?php echo date("Y-m-d\TH:i:s"); ?>" min="<?php echo date("Y-m-d\TH:i"); ?>">             
                                </div>
        
                                <div class="flex flex-col items-baseline gap-1 md:gap-3 md:flex-row md:w-fit" x-show="newLink.isEndScheduled">
                                    <label class="w-full input-label md:text-sm max-w-fit"> End Time</label>
                                    <input class="schedule_time" class="w-full md:max-w-fit" type="datetime-local" x-model="newLink.end_time" value="<?php echo date("Y-m-d\TH:i:s"); ?>" min="<?php echo date("Y-m-d\TH:i"); ?>">
                                </div>
                            </div>
                            
                            <?php } ?>

                            
                            

                                
                            
                            <button type="button" @click="addLink()" class="upload-btn">Add Link</button>
                        </div>
                    </div>
                    
                    
                    <div x-show="links.length >= maxLinks" class="warning-message">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 warning-icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12,2A10,10,0,1,0,22,12,10.01114,10.01114,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8.00917,8.00917,0,0,1,12,20Zm0-8.5a1,1,0,0,0-1,1v3a1,1,0,0,0,2,0v-3A1,1,0,0,0,12,11.5Zm0-4a1.25,1.25,0,1,0,1.25,1.25A1.25,1.25,0,0,0,12,7.5Z"></path>
                        </svg>
                        <span><?php echo self::ERROR_MAX_LINK_MSG; ?></span> 
                    </div>
        
                    <!-- Existing links display -->
                    <ul class="lists_container">
                        <template x-for="link in links">
                        <li  
                            @drop="handleDrop($event, link.id)" 
                            @dragover="handleDragOver($event)"
                            @dragenter="draggedOverLinkId = link.id" 
                            @dragleave="draggedOverLinkId = null"
                            :class="{
                                'drag-over': draggedOverLinkId === link.id,
                                'hidden-link-class': linkIsHidden(link.id),
                                'highlight-link-class': linkIsHighlighted(link.id),
                                'dragging-class': linkIsDragging(link.id),
                                'on-edit': link.isEditing
                            }"
                        >
                            <div x-show="!link.isEditing" class="flex items-center justify-between">
                            <div 
                                x-bind:draggable="!link.isEditing && !isInputFocused" 
                                @dragstart="handleDragStart($event, link.id)" 
                                @dragend="handleDragEnd($event, link.id)" 
                                class="drag-handle"
                            >⠿</div>
                             <div class="flex flex-row flex-auto gap-2 sm:gap-4 sm:ml-5">
                                <div class="flex mb-0 sm:mb-4 self-baseline" x-data="{ switchState: !link.isHidden, init() {
            this.$watch('link.isHidden', (value) => {
                this.switchState = !value;
            });
        } }" x-init="init()">
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
                                    <div class="flex flex-col items-baseline gap-4 ">
                                    <div>
                                        <span x-text="link.title" class="text-sm font-semibold sm:text-lg"></span>
                                        <span x-text="link.text" class="hidden text-gray-600 sm:block sm:pt-2"></span>
                                     </div>
                                        <div class="flex flex-row items-start gap-4">
                                            
                                        <svg xmlns="http://www.w3.org/2000/svg" class=" w-4 cursor-pointer sm:w-[1.45rem] icon-state" :class="{ 'icon-active': link.imageFile}" @click="showEditLinkForm(link.id)" viewBox="0 0 576 512" fill="currentColor">
<path d="M160 32c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H160zM396 138.7l96 144c4.9 7.4 5.4 16.8 1.2 24.6S480.9 320 472 320H328 280 200c-9.2 0-17.6-5.3-21.6-13.6s-2.9-18.2 2.9-25.4l64-80c4.6-5.7 11.4-9 18.7-9s14.2 3.3 18.7 9l17.3 21.6 56-84C360.5 132 368 128 376 128s15.5 4 20 10.7zM192 128a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM48 120c0-13.3-10.7-24-24-24S0 106.7 0 120V344c0 75.1 60.9 136 136 136H456c13.3 0 24-10.7 24-24s-10.7-24-24-24H136c-48.6 0-88-39.4-88-88V120z"/>
</svg>
<svg xmlns="http://www.w3.org/2000/svg" @click="showEditLinkForm(link.id)" class="w-3 cursor-pointer sm:w-4 icon-state" :class="{ 'icon-active': link.isScheduled }"  viewBox="0 0 448 512" fill="currentColor"><path d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z"/></svg>

<svg xmlns="http://www.w3.org/2000/svg" @click="showEditLinkForm(link.id)" class="w-3.5 sm:w-[1.15rem] text-gray-700 cursor-pointer" viewBox="0 0 512 512" fill="currentColor"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>

<svg xmlns="http://www.w3.org/2000/svg" @click="removeLink(link.id)" class="pt-[0.5px] sm:pt-[0.8px] md:pt-0 w-3 sm:w-[1rem] text-gray-700 cursor-pointer" viewBox="0 0 448 512" fill="currentColor"><path d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>                           

                                        </div>
                                     
                                    </div>
                             </div>
                             <div class="flex items-center">
                                 
                                    

                                    <?php 
                                    if (!Plugin_Name_Utilities::check_user_capability(Plugin_Name_Capabilities::HIGHLIGHT_LINK)) {
                                        // DO NOTHING
                                    } else { ?>
                                        <button class="hidden md:inline-block px-3 mt-0 sm:px-5 upload-btn text-[15px]" type="button" x-show="!link.isHidden" @click="toggleHighlightLink(link.id)">
                                            <span class="text-xs sm:text-sm" x-text="link.highlight ? 'Unhighlight' : 'Highlight'"></span>
                                        </button>
                                        <svg @click="toggleHighlightLink(link.id)" xmlns="http://www.w3.org/2000/svg" class="cursor-pointer md:hidden px-3 mt-0 sm:px-5 text-[15px] w-[1.1rem] sm:w-[1.3rem] text-[#f1441e]" fill="currentColor" viewBox="0 0 576 512">
                                            <path x-show="!link.highlight" d="M287.9 0c9.2 0 17.6 5.2 21.6 13.5l68.6 141.3 153.2 22.6c9 1.3 16.5 7.6 19.3 16.3s.5 18.1-5.9 24.5L433.6 328.4l26.2 155.6c1.5 9-2.2 18.1-9.6 23.5s-17.3 6-25.3 1.7l-137-73.2L151 509.1c-8.1 4.3-17.9 3.7-25.3-1.7s-11.2-14.5-9.7-23.5l26.2-155.6L31.1 218.2c-6.5-6.4-8.7-15.9-5.9-24.5s10.3-14.9 19.3-16.3l153.2-22.6L266.3 13.5C270.4 5.2 278.7 0 287.9 0zm0 79L235.4 187.2c-3.5 7.1-10.2 12.1-18.1 13.3L99 217.9 184.9 303c5.5 5.5 8.1 13.3 6.8 21L171.4 443.7l105.2-56.2c7.1-3.8 15.6-3.8 22.6 0l105.2 56.2L384.2 324.1c-1.3-7.7 1.2-15.5 6.8-21l85.9-85.1L358.6 200.5c-7.8-1.2-14.6-6.1-18.1-13.3L287.9 79z"/>
                                            <path x-show="link.highlight" d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>
                                        </svg>
                                        
                                    <?php } ?>

                                    
                                   

                                    

                                    </div>
                                </div>
                                <div 
                                    id="editionForm" 
                                    x-show="link.isEditing"
                                >
                                    <div class="p-5 mt-5 ">
                                        <div class="flex flex-row items-center gap-2 mb-10 text-xs font-semibold uppercase sm:text-sm md:text-base">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="text-[#f1441e] text-[14px]" fill="currentColor"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>
                                            <span>You're editing your link</span>
                                        </div>
                                        <label class="input-label">Title</label>
                                        <div class="input-container-enhanced input-container shadow-og">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#F1441E]" fill="currentColor" viewBox="0 0 512 512"><path d="M453.3 19.3l39.4 39.4c25 25 25 65.5 0 90.5l-52.1 52.1 0 0-1-1 0 0-16-16-96-96-17-17 52.1-52.1c25-25 65.5-25 90.5 0zM241 114.9c-9.4-9.4-24.6-9.4-33.9 0L105 217c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9L173.1 81c28.1-28.1 73.7-28.1 101.8 0L288 94.1l17 17 96 96 16 16 1 1-17 17L229.5 412.5c-48 48-109.2 80.8-175.8 94.1l-25 5c-7.9 1.6-16-.9-21.7-6.6s-8.1-13.8-6.6-21.7l5-25c13.3-66.6 46.1-127.8 94.1-175.8L254.1 128 241 114.9z"/></svg>
                                            <input class="input-field-enhanced" x-model="inputEditTitleValue" >
                                        </div>
                                        <label class="input-label">URL</label>
                                        <div class="input-container-enhanced input-container shadow-og">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F1441E]" fill="currentColor" viewBox="0 0 640 512"><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></svg>
                                            <input class="input-field-enhanced" x-model="inputEditLinkValue" >
                                        </div>

                                        <div x-text="linkError" class="text-danger"></div>


                                       

                                        <?php 
                                        if (!Plugin_Name_Utilities::check_user_capability(Plugin_Name_Capabilities::CAN_SCHEDULE_LINK)) {
                                            echo '<div class="warning-message"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 warning-icon" viewBox="0 0 448 512" fill="currentColor"><path d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z"></path></svg><span>' . self::ERROR_LINK_SCHEDULING . '</span></div>';

                                        } else { ?>
                                         <div class="mt-5 mb-7 text-[15px]">
                                            <input type="checkbox" x-model="link.isScheduled"> Enable Scheduling
                                         </div>
                                         <div class="mt-5 mb-7 text-[15px]" x-show="link.isScheduled">
                                            <input type="checkbox" x-model="link.isScheduled"> 
                                            <span>Enable End Time</span>
                                         </div>
                                        <div class="flex flex-col w-full gap-5 my-5 md:gap-7 md:flex-row">
                                            <div class="flex flex-col items-baseline gap-1 md:gap-3 md:flex-row md:w-fit" x-show="link.isScheduled">
                                                <label class="w-full input-label md:text-sm max-w-fit"> Start Time</label>
                                                <input type="datetime-local" class="w-full md:max-w-fit" x-model="link.start_time" value="<?php echo date("Y-m-d\TH:i"); ?>" min="<?php echo date("Y-m-d\TH:i"); ?>">             
                                            </div>

                                            <div class="flex flex-col items-baseline gap-1 md:gap-3 md:flex-row md:w-fit" x-show="link.isEndScheduled">
                                                <label class="w-full input-label md:text-sm max-w-fit"> End Time</label>
                                                <input type="datetime-local" class="w-full md:max-w-fit" x-model="link.end_time" value="<?php echo date("Y-m-d\TH:i"); ?>" min="link.start_time">
                                            </div>
                                        </div>
                                        
                                        <?php } ?>

                                        
                                        
                                       

                                        <!-- Image Upload -->
                                        <div class="mt-6 upload-container">
                                            <img x-show="link.imageFile" :src="link.imageFile" alt="Uploaded File" class="object-cover w-32 file-preview h-36">
                                            <div x-show="!link.imageFile" class="flex items-center justify-center p-2 align-middle file-preview">No Image Uploaded</div>
                                            <div class="upload-content">
                                                <form method="post" enctype="multipart/form-data">
                                                    <label for="link_image" class="block upload-label">Upload Link Image</label>
                                                    <input type="file" name="link_image" class="absolute inset-0 w-full h-full opacity-0"  accept="image/jpeg,image/png,image/tiff" @change="uploadImage(link.id)" :data-link-id="link.id" />
                                                    <input type="hidden" x-model="inputEditImageFile" />
                                                </form>
                                            </div>
                                        </div>
                                        <hr>
                                        
                                        <button type="button" @click="editLink(link.id)" class="upload-btn">Save</button>
                                        <button type="button" @click="cancelEditLink()" class="bg-[#171717] border-[#171717] upload-btn">Cancel</button>
                                    </div>

                                </div>
                          
                          
                            </li>
                        </template>
                    </ul>
            
                    <div x-show="links.length === 0" class="p-5 m-5 text-center">
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
        
    
        public static function social_links_list_field__old($label, $capability, $target_user_id) {
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
                echo '<p class="description">' . self::ERROR_MSG . '</p>';
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

            <div x-text="linkError" class="text-danger"></div>

            <button type="button" @click="addLink()" class="upload-btn">Add Link</button>
        </div>
    </div>

    <!-- Displaying Error Messages -->
    <div x-show="links.length >= maxLinks" class="warning-message">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 warning-icon" viewBox="0 0 448 512" fill="currentColor">
            <path d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z"></path>
        </svg>
        <span><?php echo self::ERROR_MAX_LINK_MSG; ?></span> 
    </div>


    <!-- Existing Social Links Display -->
    <ul class="lists_container">
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
                        <i :class="link.icon" class="text-lg"></i>
                       
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
                        <button type="button" @click="cancelEditLink()" class="bg-[#171717] border-[#171717] upload-btn">Cancel</button>
                    </div>
                </div>
            </li>
        </template>
    </ul>

    <div x-show="links.length === 0" class="p-5 m-5 text-center ">
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
        
    public static function upload_field($field_name, $label, $capability, $allowed_types = array('image/jpeg', 'image/png', 'image/tiff'), $max_size = 2 * 1024 * 102,  $target_user_id, $templateIncluded = true) {
        

        if(!$templateIncluded) {
            echo Plugin_Name_Utilities::is_not_included_field($label);;
        }
        $name = $field_name . '_url';
        $image_url = Plugin_Name_Utilities::handle_user_meta($name, Plugin_Name_Capabilities::EDIT_PROFILE_PICTURE,  $target_user_id);
        
        if(!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<div class="warning-message"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 warning-icon" viewBox="0 0 576 512" fill="#f1441e"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/> </svg><span>' . self::ERROR_MSG . '</span></div>';

        }
        
         
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
    
    public static function upload_gallery_field($field_name, $label, $capability, $allowed_types = array('image/jpeg', 'image/png', 'image/tiff'), $max_size = 2 * 1024 * 1024, $target_user_id = null) {
        $name = $field_name . '_urls';
        $image_urls = get_user_meta($target_user_id, $name, true);
        $image_urls = $image_urls ? json_decode($image_urls, true) : array();
    
        if(!Plugin_Name_Utilities::check_user_capability($capability)) {
            echo '<div class="warning-message"><span>' . self::ERROR_MSG . '</span></div>';
            return;
        }
        ?>
        
        <div x-data="linkManager" class="flex flex-col gap-2 my-5">

            <div @click="isOpen = !isOpen" class="text-sm cursor-pointer input-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18,15V5a3,3,0,0,0-3-3H5A3,3,0,0,0,2,5V15a3,3,0,0,0,3,3H15A3,3,0,0,0,18,15ZM4,5A1,1,0,0,1,5,4H15a1,1,0,0,1,1,1V9.36L14.92,8.27a2.56,2.56,0,0,0-1.81-.75h0a2.58,2.58,0,0,0-1.81.75l-.91.91-.81-.81a2.93,2.93,0,0,0-4.11,0L4,9.85Zm.12,10.45A.94.94,0,0,1,4,15V12.67L6.88,9.79a.91.91,0,0,1,1.29,0L9,10.6Zm8.6-5.76a.52.52,0,0,1,.39-.17h0a.52.52,0,0,1,.39.17L16,12.18V15a1,1,0,0,1-1,1H6.4ZM21,6a1,1,0,0,0-1,1V17a3,3,0,0,1-3,3H7a1,1,0,0,0,0,2H17a5,5,0,0,0,5-5V7A1,1,0,0,0,21,6Z"></path></svg>
                    Manage Image Gallery
            </div>
            
            <div x-show="isOpen" class="flex flex-col gap-2 p-4 bg-gray-100">
                <div class="py-2 upload-content">
                    <form method="post" enctype="multipart/form-data">
                        <label class="relative block transition-all duration-300 cursor-pointer upload-label hover:bg-gray-200">
                        <svg class="cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.71,11.29a1,1,0,0,0-.33-.21,1,1,0,0,0-.76,0,1,1,0,0,0-.33.21l-2,2a1,1,0,0,0,1.42,1.42l.29-.3V17a1,1,0,0,0,2,0V14.41l.29.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42ZM20,8.94a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19l-.1,0A1.1,1.1,0,0,0,13.06,2H7A3,3,0,0,0,4,5V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V9S20,9,20,8.94ZM14,5.41,16.59,8H15a1,1,0,0,1-1-1ZM18,19a1,1,0,0,1-1,1H7a1,1,0,0,1-1-1V5A1,1,0,0,1,7,4h5V7a3,3,0,0,0,3,3h3Z"></path></svg>
                            <input type="file" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="<?php echo implode(',', $allowed_types); ?>" onchange="this.form.submit()" />
                        </label>
                        <input type="submit" value="Upload" class="hidden px-3 py-1 mt-2 text-white transition-all duration-300 bg-blue-500 rounded upload-btn hover:bg-blue-600" />
                    </form>
                </div>
                <?php
                if(!empty($image_urls)) {
                    echo '<div class="flex flex-wrap gap-2 overflow-auto max-h-96">';
                    foreach ($image_urls as $key => $image_url) {
                        echo '<div class="relative w-1/8">';
                        echo '<img src="' . esc_attr($image_url) . '" alt="Uploaded File" class="object-cover w-24 h-24 file-preview">';
                        echo '<svg @click="removeImage(' . $key . ')" class="absolute top-0 right-0 w-6 h-6 text-red-500 cursor-pointer" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<div class="flex items-start p-2 align-left"> No Files Uploaded </div>';
                }
                ?>
            </div>
        </div>
        <?php
    
        if(isset($_FILES[$name]) && Plugin_Name_Utilities::check_user_capability($capability)) {
            self::handle_gallery_upload($name, $allowed_types, $max_size, $target_user_id);
        }
    }
    
    
    
    
    
    private static function handle_gallery_upload($name, $allowed_types, $max_size, $target_user_id = null) {
        $user_id = $target_user_id ? $target_user_id : get_current_user_id();
        
        if(isset($_FILES[$name])) {
            $file = $_FILES[$name];
            
            if(!in_array($file['type'], $allowed_types)) {
                echo '<p class="error">Invalid file type. Only JPG, JPEG, PNG, and TIFF are allowed.</p>';
                return;
            }
            
            if($file['size'] > $max_size) {
                echo '<p class="error">File size exceeded. Maximum file size is ' . ($max_size / 1024) . 'KB.</p>';
                return;
            }
            
            $uploads_dir = wp_upload_dir();
            $ph_bio_dir = $uploads_dir['basedir'] . '/ph-bio';
            
            if(!file_exists($ph_bio_dir)) {
                wp_mkdir_p($ph_bio_dir);
            }
            
            $timestamp = time();
            $new_filename = $name . '_user_' . $user_id . '_' . $timestamp . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $target_file_path = $ph_bio_dir . '/' . $new_filename;
            
            if(move_uploaded_file($file['tmp_name'], $target_file_path)) {
                $file_url = $uploads_dir['baseurl'] . '/ph-bio/' . basename($new_filename);
                
                $image_urls = get_user_meta($user_id, $name, true);
                $image_urls = $image_urls ? json_decode($image_urls, true) : array();
                $image_urls[] = $file_url;
                
                update_user_meta($user_id, $name, json_encode($image_urls));
            } else {
                echo '<p class="error">Failed to upload image.</p>';
            }
        } else {
            echo '<p class="error">Something went wrong.</p>';
        }
    }
    
    
    
    
    
    
}
    

new Plugin_Name_Builder();