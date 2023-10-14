<?php


class Press_Kit_Builder {

    const ERROR_LANG_PRO= "<a href='/pricing' class='text-gray-700 no-underline font-semi-bold' target='___blank'>Unlock an additional language instantly by <span class='text-[#F1441E] font-bold'>Going PRO </span></a>";

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