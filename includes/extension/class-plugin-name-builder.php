<?php


class Press_Kit_Builder {

    const ERROR_LANG_PRO= "<a href='/pricing' class='text-gray-700 no-underline font-semi-bold' target='___blank'>Unlock an additional language instantly by <span class='text-[#F1441E] font-bold'>Going PRO </span></a>";

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
                    el.value = '<?php echo esc_js(site_url('/presskit')); ?>/' + secureUsername;
                    document.body.appendChild(el);
                    el.select();
                    document.execCommand('copy');
                    document.body.removeChild(el);
                    copied = true;
                    setTimeout(() => { copied = false; }, 2000); // Reset after 2 seconds
                };
                navigateToLink = () => {
                    const url = '<?php echo esc_js(site_url('/presskit')); ?>/' + secureUsername;
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
                    formData.append('type', 'hb-user-pkit');
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
            
<div class="flex flex-col items-start gap-3 mb-6 sm:items-center sm:flex-row" x-show="secureUsername">
<span class="block text-sm text-gray-500 hover:text-gray-700" x-text="`<?php echo esc_js(site_url('/presskit')); ?>/` + secureUsername"></span>
<div class="flex flex-row gap-4 sm:gap-2">
<svg @click="copyToClipboard" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-5 h-5 cursor-pointer hover:text-gray-700" viewBox="0 0 24 24" fill="currentColor"><path d="M21,8.94a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19.32.32,0,0,0-.09,0A.88.88,0,0,0,14.05,2H10A3,3,0,0,0,7,5V6H6A3,3,0,0,0,3,9V19a3,3,0,0,0,3,3h8a3,3,0,0,0,3-3V18h1a3,3,0,0,0,3-3V9S21,9,21,8.94ZM15,5.41,17.59,8H16a1,1,0,0,1-1-1ZM15,19a1,1,0,0,1-1,1H6a1,1,0,0,1-1-1V9A1,1,0,0,1,6,8H7v7a3,3,0,0,0,3,3h5Zm4-4a1,1,0,0,1-1,1H10a1,1,0,0,1-1-1V5a1,1,0,0,1,1-1h3V7a3,3,0,0,0,3,3h3Z"></path></svg>
<svg @click="navigateToLink" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 cursor-pointer hover:text-gray-700" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18,10.82a1,1,0,0,0-1,1V19a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V8A1,1,0,0,1,5,7h7.18a1,1,0,0,0,0-2H5A3,3,0,0,0,2,8V19a3,3,0,0,0,3,3H16a3,3,0,0,0,3-3V11.82A1,1,0,0,0,18,10.82Zm3.92-8.2a1,1,0,0,0-.54-.54A1,1,0,0,0,21,2H15a1,1,0,0,0,0,2h3.59L8.29,14.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L20,5.41V9a1,1,0,0,0,2,0V3A1,1,0,0,0,21.92,2.62Z"></path></svg>

<span x-show="copied" class="ml-2 text-sm text-gray-700">Copied!</span>
</div>
</div>
        </div>
        <?php
    }
    
    public static function language_select($name, $value, $label, $capability, $target_user_id) {

        if(!Plugin_Name_Utilities::is_full_version($target_user_id)) {
            echo '<div class="warning-message"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 warning-icon" viewBox="0 0 576 512" fill="#f1441e"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/> </svg><span>' . self::ERROR_LANG_PRO . '</span></div>';

        }  

        $data = Plugin_Name_Utilities::handle_user_meta($name, $capability, $target_user_id);
       
        if (!$data) $data = $value;
        
        ?>
        <label for="<?php echo $name; ?>" class="input-label"><?php echo $label; ?></label>
    
        <div class="input-container">
        <div x-data="lang({ selected: '<?php echo $data; ?>', allowMultiple: <?php echo Plugin_Name_Utilities::is_full_version($target_user_id) ? 'true' : 'false';  ?> })" class="input-field">
            <div @click="if (canOpenDropdown()) { isOpen = !isOpen }" class="cursor-pointer ">
                <div x-show="selected.length > 0" class="flex flex-wrap">
                    <template x-for="(option, index) in selected" :key="index">
                        <span class="inline-flex items-center px-2 py-0.5 m-1 rounded text-sm font-medium bg-red-100 text-[#F1441E]">
                            <span x-text="options[option]"></span>
                            <button 
                                @click="removeOption(option)" 
                                type="button" 
                                class="flex items-center justify-center bg-transparent border-0 p-0 ml-0.5 h-4 w-4 rounded-full text-[#F1441E] hover:text-[#F1441E] focus:outline-none focus:text-[#F1441E]"
                            >
                                <span class="sr-only">Remove this language</span>
                                <svg class="w-2 h-2" stroke="#F1441E" fill="none" viewBox="0 0 8 8">
                                    <line x1="1" y1="1" x2="7" y2="7" stroke-width="1"></line>
                                    <line x1="1" y1="7" x2="7" y2="1" stroke-width="1"></line>
                                </svg>
                            </button>


                        </span>
                    </template>
                </div>
                <span x-show="selected.length === 0" class="text-white ">Select a Language</span>
            </div>
            <ul x-show="isOpen" @click.away="isOpen = false" class="absolute mt-2 overflow-y-auto bg-white border rounded shadow max-h-64">
                <template x-for="(entry, index) in Object.entries(options)" :key="index">
                    <li @click="selectOption(entry[0])" class="p-2 cursor-pointer hover:bg-gray-200" :class="{'bg-[#F1441E] text-white': isSelected(entry[0]), 'bg-gray-100': !isSelected(entry[0]) && (index+1) % 2 === 0}">
                        <span x-text="entry[1]"></span>
                    </li>
                </template>
            </ul>
            <!-- Hidden input to hold the selected value, if you're using this in a form -->
            <input type="hidden" name="<?php echo $name; ?>" x-model="selectedAsString">
        </div>
        </div>
    
        <?php
    }
    
}

new Press_Kit_Builder();